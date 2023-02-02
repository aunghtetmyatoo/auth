<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Constants\Status;
use App\Actions\StoreFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\ServerPath;
use App\Actions\HandleEndpoint;
use App\Models\RechargeRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Constants\TelegramConstant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\Recharge\RechargeResource;
use App\Http\Resources\Recharge\RechargeCollection;
use App\Http\Requests\Api\Admin\Recharge\ConfirmRequest;
use App\Http\Requests\Api\Admin\Recharge\CompleteRequest;
use App\Http\Requests\Api\RechargeRequest\RechargeCreateRequest;

class RechargeRequestController extends Controller
{
    use ApiResponse;

    public function __construct(private HandleEndpoint $handleEndpoint)
    {
    }


    public function index(Request $request)
    {
        $rechargeRequest = RechargeRequest::where(function ($query) use ($request) {

            $request->has('user_name') && $request->user_name != null
            && $query->whereHas("user", function ($q) use ($request)  {
                $q->where("name", 'like', '%' . $request->user_name . '%');
            });

            $request->has('admin_name') && $request->admin_name != null
            && $query->whereHas("admin", function ($q) use ($request)  {
                $q->where("name", 'like', '%' . $request->admin_name . '%');
            });

            $request->has('payment_type') && $request->payment_type != null
            && $query->whereHas("payment_type", function ($q) use ($request)  {
                $q->whereIn("name",$request->payment_type);
            });

            $request->has('status') && $request->status != null
            && $query->whereIn('status', $request->status );

        });

        return $this->responseCollection(new RechargeCollection($rechargeRequest->paginate(5)));
    }

    public function confirm(ConfirmRequest $request, RechargeRequest $recharge_request)
    {
        $invalid_status = DB::transaction(function () use ($recharge_request, $request) {
            $recharge_request_locked = RechargeRequest::lockForUpdate()->find($recharge_request->id);

            if ($recharge_request_locked->status != Status::REQUESTED) {
                return true;
            }

            $recharge_request_locked->update([
                'status' => Status::CONFIRMED,
                'confirmed_amount' => $request->amount,
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
                'message' => 'This request is not in Requested status!'
            ], 400);
        }

        return "done";

        // $recharge_request->refresh();
        // RechargeStatusUpdated::dispatch('StatusConfirmed', $recharge_request);
    }

    public function reject(RechargeRequest $recharge_request)
    {
        $invalid_status = DB::transaction(function () use ($recharge_request) {
            $request_locked = RechargeRequest::lockForUpdate()->find($recharge_request->id);

            if ($request_locked->status != Status::REQUESTED) {
                return true;
            }

            $request_locked->update([
                'status' => Status::REJECTED,
            ]);
        }, 5);

        $recharge_request->refresh();

        if ($invalid_status) {
            return response()->json([
                'message' => 'Cannot reject!, this request is in ' . $recharge_request->status . ' status!'
            ], 400);
        }

        return "done";

        // RechargeStatusUpdated::dispatch('StatusRejected', $request);
    }

    public function request(RechargeRequest $recharge_request)
    {
        [$invalid_status, $requested] = DB::transaction(function () use ($recharge_request) {
            ['id' => $id, 'status' => $status, 'expired_at' => $expired_at] = $recharge_request;

            $request_locked = RechargeRequest::lockForUpdate()->find($id);
            if ($status != Status::CANCELLED && ($status == Status::REQUESTED  && !$recharge_request->expired_at->isFuture())) {
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

    public function complete(CompleteRequest $request, RechargeRequest $recharge_request)
    {
        return "a";
        // if (!Hash::check($request->password, backpack_user()->password)) {
        //     return response()->json([
        //         'message' => 'Your password is incorrect.'
        //     ], 400);
        // }

        // $invalid_status = DB::transaction(function () use ($recharge_request) {
        //     $recharge_request_locked = RechargeRequest::lockForUpdate()->find($recharge_request->id);

        //     if ($recharge_request_locked->status != 'Confirmed') {
        //         return true;
        //     }

        //     $pay_user_locked = Pay_user::lockForUpdate()->find($recharge_request_locked->pay_user->id);
        //     $om_locked = User::lockForUpdate()->whereHas('roles', function ($query) {
        //         $query->where('name', 'Operation Manager');
        //     })->first();

        //     if ($om_locked->amount < $recharge_request_locked->confirmed_amount) {
        //         throw new AmountNotEnoughException();
        //     }

        //     $transaction_type = Transaction_type::whereType('Recharge')->first();

        //     $invalid_log = (new MonitorTransaction([$pay_user_locked, $om_locked]))->execute();
        //     if ($invalid_log) {
        //         throw new TransactionFailedException();
        //     }

        //     $from_amount_before = $om_locked->amount;
        //     $from_amount_after = (float) bcsub($from_amount_before, $recharge_request_locked->confirmed_amount, 4);

        //     $to_amount_before = $pay_user_locked->amount;
        //     $to_amount_after = (float) bcadd($to_amount_before, $recharge_request_locked->confirmed_amount, 4);

        //     $om_locked->update([
        //         'amount' => $from_amount_after,
        //     ]);

        //     $pay_user_locked->update([
        //         'amount' => $to_amount_after,
        //     ]);

        //     $transaction = $recharge_request_locked->recharge_transaction()->create([
        //         'transaction_type_id' => $transaction_type->id,
        //         'user_id' => $om_locked->id,
        //         'transaction_id' => Str::uuid(),
        //         'amount' => $recharge_request_locked->confirmed_amount,
        //         'remark' => 'Recharge',
        //     ]);

        //     $transaction->refresh();
        //     $transaction->update([
        //         'transaction_id' => (new ReferenceId())->execute('RC', $transaction->id),
        //     ]);

        //     (new LogTransaction(
        //         $transaction->user_transaction_log(),
        //         [
        //             'user_id' => $om_locked->id,
        //             'last_amount' => $from_amount_before,
        //             'current_amount' => $from_amount_after,
        //             'transaction_amount' => $transaction->amount,
        //         ],
        //         $transaction->pay_user_transaction_log(),
        //         [
        //             'pay_user_id' => $pay_user_locked->id,
        //             'last_amount' => $to_amount_before,
        //             'current_amount' => $to_amount_after,
        //             'transaction_amount' => $transaction->amount,
        //             'to_account' => 1,
        //         ]
        //     ))->execute();

        //     $recharge_request_locked->update([
        //         'status' => 'Completed',
        //         'completed_by' => auth()->id()
        //     ]);

        //     $recharge_request_locked->record()->create([
        //         'pay_user_id' => $pay_user_locked->id,
        //         'status' => 'Succeed',
        //     ]);
        // }, 5);

        // if ($invalid_status) {
        //     return response()->json([
        //         'message' => 'This request is not in Confirmed status!'
        //     ], 400);
        // }

        // $recharge_request->refresh();
        // RechargeStatusCompleted::dispatch('StatusCompleted', $recharge_request);
    }

    public  function create(Request $request)
    {
        RechargeRequest::create([
            'user_id' => auth()->user()->id,
            'reference_id' => '12345',
            'recharge_cahnnel_id' => 1,
            'requested_amount' => 500,
            'rate' => 400,
        ]);

        return $this->responseSucceed(message: "Add Recharge Request Successfully");
    }
}
