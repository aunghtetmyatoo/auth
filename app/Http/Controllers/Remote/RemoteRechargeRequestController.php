<?php

namespace App\Http\Controllers\Remote;

use App\Models\User;
use App\Models\Admin;
use App\Constants\Status;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RechargeRequest;
use App\Models\TransactionType;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Constants\TelegramConstant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Actions\Transaction\ReferenceId;
use App\Actions\Transaction\LogTransaction;
use App\Exceptions\AmountNotEnoughException;
use App\Enums\TransactionType as EnumsTransactionType;

class RemoteRechargeRequestController extends Controller
{
    use ApiResponse;

    public function confirmRecharge(Request $request)
    {
        [$invalid_status, $recharge_request] = DB::transaction(function () use ($request) {
            $recharge_request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($recharge_request_locked->status != Status::REQUESTED) {
                return [true, $recharge_request_locked];
            }

            $recharge_request_locked->update([
                'status' => Status::CONFIRMED,
                'confirmed_amount' => $request->confirmed_amount,
                'received_amount' => $request->received_amount,
                'received_from' => $request->received_from,
                'rate' => $recharge_request_locked->recharge_channel->exchange_currency->sell_rate,
                'rate' => 400,
                'description' => $request->description,
                'confirmed_at' => now(),
                'completed_by' => $request->admin_id,
                'read_at' => null,
            ]);

            $recharge_request_locked->refresh();
            return [false, $recharge_request_locked];
        }, 5);

        if ($invalid_status) {
            return $this->responseBadRequest(
                message: "This request is not in Requested status!"
            );
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Recharge Requested' . "(" . $recharge_request->recharge_channel->name . ")" . PHP_EOL .
                'Status -' . $recharge_request->status . PHP_EOL .
                'Account Name - ' . $recharge_request->user->name . PHP_EOL .
                'Phone Number - ' . $recharge_request->user->phone_number . PHP_EOL .
                'Requested Amount -' . $recharge_request->requested_amount . PHP_EOL .
                'Confirmed Amount -' . $recharge_request->confirmed_amount . PHP_EOL .
                'Request Id -' . $recharge_request->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully confirmed!."
        );
    }

    public function rejectRecharge(Request $request)
    {
        [$invalid_status, $request_locked] = DB::transaction(function () use ($request) {
            $request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($request_locked->status != Status::REQUESTED) {
                return [true, $request_locked];
            }

            $request_locked->update([
                'status' => Status::REJECTED,
            ]);

            $request_locked->refresh();
            return [false, $request_locked];
        }, 5);

        if ($invalid_status) {
            return $this->responseBadRequest(
                message: "Cannot reject!, this request is in ' . $request_locked->status . ' status!"
            );
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Recharge Requested' . "(" . $request_locked->recharge_channel->name . ")" . PHP_EOL .
                'Status -' . $request_locked->status . PHP_EOL .
                'Account Name - ' . $request_locked->user->name . PHP_EOL .
                'Phone Number - ' . $request_locked->user->phone_number . PHP_EOL .
                'Requested Amount -' . $request_locked->requested_amount . PHP_EOL .
                'Request Id -' . $request_locked->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully rejected!."
        );
    }

    public function requestRecharge(Request $request)
    {
        [$invalid_status, $requested, $request_locked] = DB::transaction(function () use ($request) {
            $request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($request_locked->status != Status::CANCELLED && ($request_locked->status == Status::REQUESTED  && !$request_locked->expired_at->isFuture())) {
                return [true, false, $request_locked];
            }

            $requested = $request_locked->user->recharge_requests()->whereHas('recharge_channel', function ($query) use ($request_locked) {
                $query->where('name', $request_locked->recharge_channel->name);
            })->whereIn('status', [Status::REQUESTED, Status::CONFIRMED])
                ->where('expired_at', '>=', now())->first();

            if ($requested) {
                return [false, true, $request_locked];
            }

            $request_locked->update([
                'status' => Status::REQUESTED,
                'expired_at' => now()->addMinutes($request_locked->recharge_channel->requests_expired_in),
            ]);

            $request_locked->refresh();
            return [false, true, $request_locked];
        }, 5);

        if ($invalid_status) {
            return $this->responseBadRequest(
                message: "This request is not in cancelled or expired status!"
            );
        }

        if ($requested) {
            return $this->responseBadRequest(
                message: "This user currently have a request in process!"
            );
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Recharge Requested' . "(" . $request_locked->recharge_channel->name . ")" . PHP_EOL .
                'Status -' . $request_locked->status . PHP_EOL .
                'Account Name - ' . $request_locked->user->name . PHP_EOL .
                'Phone Number - ' . $request_locked->user->phone_number . PHP_EOL .
                'Requested Amount -' . $request_locked->requested_amount . PHP_EOL .
                'Request Id -' . $request_locked->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully requested!."
        );
    }

    public function completeRecharge(Request $request)
    {
        [$invalid_status, $recharge_request_locked] = DB::transaction(function () use ($request) {
            $recharge_request_locked = RechargeRequest::lockForUpdate()->find($request->id);

            if ($recharge_request_locked->status != Status::CONFIRMED) {
                return [true, $recharge_request_locked];
            }

            $user_locked = User::lockForUpdate()->find($recharge_request_locked->user->id);
            $om_locked = Admin::lockForUpdate()->whereId($request->admin_id)->whereRole('Operation Manager')->first();

            if ($om_locked->amount < $recharge_request_locked->confirmed_amount) {
                throw new AmountNotEnoughException();
            }

            $transaction_type = TransactionType::whereName(EnumsTransactionType::Recharge)->first();

            // $invalid_log = (new MonitorTransaction([$user_locked, $om_locked]))->execute();
            // if ($invalid_log) {
            //     throw new TransactionFailedException();
            //     throw new GeneralError();
            // }

            $from_amount_before = $om_locked->amount;
            $from_amount_after = (float) bcsub($from_amount_before, $recharge_request_locked->confirmed_amount, 4);

            $to_amount_before = $user_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, $recharge_request_locked->confirmed_amount, 4);

            $om_locked->update([
                'amount' => $from_amount_after,
            ]);

            $user_locked->update([
                'amount' => $to_amount_after,
            ]);

            $transaction = $recharge_request_locked->recharge_transaction()->create([
                'transaction_type_id' => $transaction_type->id,
                'recharge_request_id' => $recharge_request_locked->id,
                'user_id' => $user_locked->id,
                'reference_id' => Str::uuid(),
                'amount' => $recharge_request_locked->confirmed_amount,
                'remark' => 'Recharge',
            ]);

            $transaction->refresh();
            $transaction->update([
                'transaction_id' => (new ReferenceId())->execute('RC', $transaction->id),
            ]);

            (new LogTransaction(
                $transaction->history(),
                [
                    // For Operation Manager
                    'historiable_id' => $om_locked->id,
                    'historiable_type' => get_class($om_locked),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $recharge_request_locked->reference_id,
                    'transaction_amount' => $recharge_request_locked->confirmed_amount,
                    'amount_before_transaction' => $from_amount_before,
                    'amount_after_transaction' => $from_amount_after,
                    'is_from' => 0,
                ],
                $transaction->history(),
                [
                    // For User
                    'historiable_id' => $user_locked->id,
                    'historiable_type' => get_class($user_locked),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $recharge_request_locked->reference_id,
                    'transaction_amount' => $recharge_request_locked->confirmed_amount,
                    'amount_before_transaction' => $from_amount_before,
                    'amount_after_transaction' => $from_amount_after,
                    'is_from' => 1,
                ]
            ))->execute();

            $recharge_request_locked->update([
                'status' => Status::COMPLETED,
                'completed_by' => $request->admin_id,
            ]);

            $recharge_request_locked->refresh();
            return [false, $recharge_request_locked];
        }, 5);

        if ($invalid_status) {
            return $this->responseBadRequest(
                message: "This request is not in Confirmed status!"
            );
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Recharge Requested' . "(" . $recharge_request_locked->recharge_channel->name . ")" . PHP_EOL .
                'Status -' . $recharge_request_locked->status . PHP_EOL .
                'Account Name - ' . $recharge_request_locked->user->name . PHP_EOL .
                'Phone Number - ' . $recharge_request_locked->user->phone_number . PHP_EOL .
                'Requested Amount -' . $recharge_request_locked->requested_amount . PHP_EOL .
                'Confirmed Amount -' . $recharge_request_locked->confirmed_amount . PHP_EOL .
                'Request Id -' . $recharge_request_locked->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully completed!."
        );
    }
}
