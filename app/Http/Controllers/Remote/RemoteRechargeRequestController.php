<?php

namespace App\Http\Controllers\Remote;

use App\Models\GameType;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\RechargeRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\Remote\UpdateGameCoinRequest;
use App\Http\Requests\Api\Remote\CreateGameTypeUserRequest;

class RemoteRechargeRequestController extends Controller
{
    use ApiResponse;

    public function confirmRecharge(Request $request)
    {
        $invalid_status = DB::transaction(function () use ($request) {
            $recharge_request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($recharge_request_locked->status != Status::REQUESTED) {
                return true;
            }

            $recharge_request_locked->update([
                'status' => Status::CONFIRMED,
                'confirmed_amount' => $request->confirmed_amount,
                'received_amount' => $request->received_amount,
                'received_from' => $request->received_from,
                // 'rate' => $recharge_request_locked->channel->currency->sell_rate,
                'rate' => 400,
                'description' => $request->description,
                'confirmed_at' => now(),
                // 'completed_by' => auth()->user()->id,
                'completed_by' => 1,
                'read_at' => null,
            ]);
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'data' => [
                    'message' => 'This request is not in Requested status!',
                ],
                'code' => 400,
            ], 400);
        }

        return $this->responseSucceed(
            message: "Successfully confirmed!."
        );

        // $recharge_request->refresh();
        // RechargeStatusUpdated::dispatch('StatusConfirmed', $recharge_request);
    }

    public function rejectRecharge(Request $request)
    {
        $status = RechargeRequest::find($request->id)->pluck('status');
        dd($status);
        $invalid_status = DB::transaction(function () use ($request) {
            $request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($request_locked->status != Status::REQUESTED) {
                return true;
            }

            $request_locked->update([
                'status' => Status::REJECTED,
            ]);
        }, 5);

        $status = RechargeRequest::find($request->id)->pluck('status');

        if ($invalid_status) {
            return response()->json([
                'data' => [
                    'message' => 'Cannot reject!, this request is in ' . $status . ' status!',
                ],
            ], 400);
        }

        return "done";

        // RechargeStatusUpdated::dispatch('StatusRejected', $request);
    }

    public function requestRecharge(Request $request)
    {
        [$invalid_status, $requested] = DB::transaction(function () use ($request) {
            $request_locked = RechargeRequest::lockForUpdate()->find($request->id);
            if ($request_locked->status != Status::CANCELLED && ($request_locked->status == Status::REQUESTED  && !$request_locked->expired_at->isFuture())) {
                return [false, true];
            }

            $requested = $request_locked->user->recharge_request->whereHas('recharge_channel', function ($query) use ($request_locked) {
                $query->where('name', $request_locked->recharge_channel->name);
            })->whereIn('status', [Status::REQUESTED, Status::CONFIRMED])
                ->where('expired_at', '>=', now())->first();

            if ($requested) {
                return [false, true];
            }

            $request_locked->update([
                'status' => Status::REQUESTED,
                'expired_at' => now()->addMinutes($request_locked->recharge_channel->requests_expired_in),
            ]);
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'This request is not in cancelled or expired status!'
            ], 400);
        }

        if ($requested) {
            return response()->json([
                'message' => 'This user currently have a request in process!'
            ], 400);
        }

        return "done";

        // $request->refresh();
        // RechargeStatusUpdated::dispatch('StatusRequested', $request);
    }

    public function completeRecharge(CompleteRequest $request, RechargeRequest $recharge_request)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'message' => 'Your password is incorrect.'
            ], 400);
        }

        $invalid_status = DB::transaction(function () use ($recharge_request) {
            $recharge_request_locked = RechargeRequest::lockForUpdate()->find($recharge_request->id);

            if ($recharge_request_locked->status != Status::CONFIRMED) {
                return true;
            }

            $pay_user_locked = Pay_user::lockForUpdate()->find($recharge_request_locked->pay_user->id);
            $om_locked = User::lockForUpdate()->whereHas('roles', function ($query) {
                $query->where('name', 'Operation Manager');
            })->first();

            if ($om_locked->amount < $recharge_request_locked->confirmed_amount) {
                throw new AmountNotEnoughException();
            }

            $transaction_type = Transaction_type::whereType('Recharge')->first();

            $invalid_log = (new MonitorTransaction([$pay_user_locked, $om_locked]))->execute();
            if ($invalid_log) {
                throw new TransactionFailedException();
            }

            $from_amount_before = $om_locked->amount;
            $from_amount_after = (float) bcsub($from_amount_before, $recharge_request_locked->confirmed_amount, 4);

            $to_amount_before = $pay_user_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, $recharge_request_locked->confirmed_amount, 4);

            $om_locked->update([
                'amount' => $from_amount_after,
            ]);

            $pay_user_locked->update([
                'amount' => $to_amount_after,
            ]);

            $transaction = $recharge_request_locked->recharge_transaction()->create([
                'transaction_type_id' => $transaction_type->id,
                'user_id' => $om_locked->id,
                'transaction_id' => Str::uuid(),
                'amount' => $recharge_request_locked->confirmed_amount,
                'remark' => 'Recharge',
            ]);

            $transaction->refresh();
            $transaction->update([
                'transaction_id' => (new ReferenceId())->execute('RC', $transaction->id),
            ]);

            (new LogTransaction(
                $transaction->user_transaction_log(),
                [
                    'user_id' => $om_locked->id,
                    'last_amount' => $from_amount_before,
                    'current_amount' => $from_amount_after,
                    'transaction_amount' => $transaction->amount,
                ],
                $transaction->pay_user_transaction_log(),
                [
                    'pay_user_id' => $pay_user_locked->id,
                    'last_amount' => $to_amount_before,
                    'current_amount' => $to_amount_after,
                    'transaction_amount' => $transaction->amount,
                    'to_account' => 1,
                ]
            ))->execute();

            $recharge_request_locked->update([
                'status' => 'Completed',
                'completed_by' => auth()->id()
            ]);

            $recharge_request_locked->record()->create([
                'pay_user_id' => $pay_user_locked->id,
                'status' => 'Succeed',
            ]);
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'This request is not in Confirmed status!'
            ], 400);
        }

        $recharge_request->refresh();
        RechargeStatusCompleted::dispatch('StatusCompleted', $recharge_request);
    }
}
