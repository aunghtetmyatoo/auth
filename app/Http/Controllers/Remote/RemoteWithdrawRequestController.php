<?php

namespace App\Http\Controllers\Remote;

use App\Models\User;
use App\Models\Admin;
use App\Models\GameType;
use App\Constants\Status;
use App\Models\GlAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RechargeRequest;
use App\Models\TransactionType;
use App\Models\WithdrawRequest;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Constants\TelegramConstant;
use App\Models\WithdrawTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Actions\Transaction\ReferenceId;
use App\Actions\Transaction\LogTransaction;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\TransactionType as EnumsTransactionType;
use App\Http\Requests\Api\Remote\UpdateGameCoinRequest;
use App\Http\Requests\Api\Remote\CreateGameTypeUserRequest;

class RemoteWithdrawRequestController extends Controller
{
    use ApiResponse;

    public function refundWithdraw(Request $request)
    {
        [$invalid_status, $withdraw_request] = DB::transaction(function () use ($request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($request->id);

            if ($withdraw_request_locked->status != Status::REQUESTED) {
                return [true, $withdraw_request_locked];
            }

            $wdl_payable_locked = GlAccount::lockForUpdate()->whereReferenceId('WDL_PAYABLE')->first();
            $wdl_income_locked = GlAccount::lockForUpdate()->whereReferenceId('WDL_INCOME')->first();
            $user_locked = User::lockForUpdate()->find($withdraw_request_locked->user->id);
            $transaction = WithdrawTransaction::lockForUpdate()->where('withdraw_request_id', $withdraw_request_locked->id)->first();

            // $invalid_log = (new MonitorTransaction([$user_locked]))->execute();
            // if ($invalid_log) {
            //     throw new TransactionFailedException();
            // }

            $payable_before = $wdl_payable_locked->amount;
            $payable_after = (float) bcsub($payable_before, $transaction->amount, 4);

            $income_before = $wdl_income_locked->amount;
            $income_after = (float) bcsub($income_before, $transaction->handling_fees, 4);

            $to_amount_before = $user_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, bcadd($transaction->amount, $transaction->handling_fees, 4), 4);

            $user_locked->update([
                'amount' => $to_amount_after,
            ]);

            $wdl_payable_locked->update([
                'amount' => $payable_after,
            ]);

            $wdl_income_locked->update([
                'amount' => $income_after,
            ]);

            $transaction_type = TransactionType::whereName(EnumsTransactionType::Withdraw)->first();

            $complete_transaction = $withdraw_request_locked->withdraw_transaction()->create([
                'transaction_type_id' => $transaction_type->id,
                'user_id' => $transaction->user_id,
                'reference_id' => Str::uuid(),
                'amount' => $transaction->amount,
                'handling_fees' => $transaction->handling_fees,
                'remark' => 'Refund',
            ]);

            $complete_transaction->refresh();
            $complete_transaction->update([
                'reference_id' => (new ReferenceId())->execute('WDF', $transaction->id),
            ]);

            $withdraw_request_locked->update([
                'status' => Status::REFUNDED,
                'description' => $request->description ? $request->description : '',
            ]);

            return [false, $withdraw_request_locked];
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'Cannot refund!, this request is in ' . $withdraw_request->status . ' status!'
            ], 400);
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Withdraw Requested' . "(" . $withdraw_request->withdraw_channel->name . ")" . PHP_EOL .
                'Status -' . $withdraw_request->status . PHP_EOL .
                'Account Name - ' . $withdraw_request->user->name . PHP_EOL .
                'Phone Number - ' . $withdraw_request->user->phone_number . PHP_EOL .
                'Request Id -' . $withdraw_request->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            data: ["id" => $request->id],
            message: "Successfully refunded!."
        );
    }

    public function confirmWithdraw(Request $request)
    {
        [$invalid_status, $withdraw_request] = DB::transaction(function () use ($request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($request->id);
            if ($withdraw_request_locked->status != Status::REQUESTED) {
                return [true, $withdraw_request_locked];
            }

            $transferred_amount = (float) bcmul($withdraw_request_locked->amount, $withdraw_request_locked->rate, 4);

            $withdraw_request_locked->update([
                'status' => Status::CONFIRMED,
                'transferred_amount' => $transferred_amount,
                'description' => $request->description,
                'confirmed_at' => now(),
                'confirmed_by' => $request->admin_id,
                'read_at' => null,
            ]);

            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $file->storeAs(
                    'Exchange/Withdraw/Requests/' . $withdraw_request_locked->id,
                    'screenshot.' . $file->extension(),
                );

                $withdraw_request_locked->update([
                    'screenshot' => 'screenshot.' . $file->extension(),
                ]);
            }

            $withdraw_request_locked->refresh();
            return [false, $withdraw_request_locked];
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'This request is not in Requested status!'
            ], 400);
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Withdraw Requested' . "(" . $withdraw_request->withdraw_channel->name . ")" . PHP_EOL .
                'Status -' . $withdraw_request->status . PHP_EOL .
                'Account Name - ' . $withdraw_request->user->name . PHP_EOL .
                'Phone Number - ' . $withdraw_request->user->phone_number . PHP_EOL .
                'Requested Amount -' . $withdraw_request->requested_amount . PHP_EOL .
                'Confirmed Amount -' . $withdraw_request->confirmed_amount . PHP_EOL .
                'Request Id -' . $withdraw_request->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully confirmed!."
        );
    }

    public function completeWithdraw(Request $request)
    {
        [$invalid_status, $withdraw_request_locked] = DB::transaction(function () use ($request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($request->id);

            if ($withdraw_request_locked->status != Status::CONFIRMED) {
                return [true, $withdraw_request_locked];
            }

            $cash_out_locked = GlAccount::lockForUpdate()->whereReferenceId('CASH_OUT')->first();
            $cash_in_locked = GlAccount::lockForUpdate()->whereReferenceId('CASH_IN')->first();

            $om_locked = Admin::lockForUpdate()->whereId($request->admin_id)->whereRole('Operation Manager')->first();

            $transaction = WithdrawTransaction::lockForUpdate()->where('withdraw_request_id', $withdraw_request_locked->id)->first();

            // $invalid_log = (new MonitorTransaction([$om_locked]))->execute();
            // if ($invalid_log) {
            //     throw new TransactionFailedException();
            // }

            $payable_before = $cash_out_locked->amount;
            $payable_after = (float) bcsub($payable_before, $transaction->amount, 4);

            $income_before = $cash_in_locked->amount;
            $income_after = (float) bcsub($income_before, $transaction->handling_fees, 4);

            $to_amount_before = $om_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, bcadd($transaction->amount, $transaction->handling_fees, 4), 4);

            $om_locked->update([
                'amount' => $to_amount_after,
            ]);

            $cash_out_locked->update([
                'amount' => $payable_after,
            ]);

            $cash_in_locked->update([
                'amount' => $income_after,
            ]);

            $transaction_type = TransactionType::whereName(EnumsTransactionType::Withdraw)->first();

            $complete_transaction = $withdraw_request_locked->withdraw_transaction()->create([
                'user_id' => $transaction->user_id,
                'transaction_type_id' => $transaction_type->id,
                'reference_id' => Str::uuid(),
                'amount' => $transaction->amount,
                'handling_fees' => $transaction->handling_fees,
                'remark' => 'Withdraw Complete',
            ]);

            $complete_transaction->refresh();
            $complete_transaction->update([
                'reference_id' => (new ReferenceId())->execute('WDC', $transaction->id),
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
                    'reference_id' => $withdraw_request_locked->reference_id,
                    'transaction_amount' => $withdraw_request_locked->transferred_amount,
                    'amount_before_transaction' => $to_amount_before,
                    'amount_after_transaction' => $to_amount_after,
                    'is_from' => 0,
                ],
                $transaction->history(),
                [
                    // For User
                    'historiable_id' => $transaction->user_id,
                    'historiable_type' => get_class(User::find($transaction->user_id)),
                    'transactionable_id' => $transaction->id,
                    'transactionable_type' => get_class($transaction),
                    'transaction_type_id' => $transaction_type->id,
                    'reference_id' => $withdraw_request_locked->reference_id,
                    'transaction_amount' => $withdraw_request_locked->transferred_amount,
                    'amount_before_transaction' => $to_amount_before,
                    'amount_after_transaction' => $to_amount_after,
                    'is_from' => 1,
                ]
            ))->execute();

            $withdraw_request_locked->update([
                'status' => Status::COMPLETED,
                'completed_by' => $request->admin_id,
            ]);

            $withdraw_request_locked->refresh();
            return [false, $withdraw_request_locked];
        }, 5);

        if ($invalid_status) {
            return $this->responseBadRequest(
                message: "This request is not in Confirmed status!"
            );
        }

        Http::post('https://api.telegram.org/bot' . TelegramConstant::bot_token . '/sendMessage', [
            'chat_id' => TelegramConstant::chat_id,
            'text' =>  'Withdraw Requested' . "(" . $withdraw_request_locked->withdraw_channel->name . ")" . PHP_EOL .
                'Status -' . $withdraw_request_locked->status . PHP_EOL .
                'Account Name - ' . $withdraw_request_locked->user->name . PHP_EOL .
                'Phone Number - ' . $withdraw_request_locked->user->phone_number . PHP_EOL .
                'Transferred Amount -' . $withdraw_request_locked->transferred_amount . PHP_EOL .
                'Request Id -' . $withdraw_request_locked->reference_id . PHP_EOL .
                'Date -' . now()->format('Y-m-d H:i:s')
        ]);

        return $this->responseSucceed(
            message: "Successfully completed!."
        );
    }
}
