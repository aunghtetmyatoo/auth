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

class RemoteWithdrawRequestController extends Controller
{
    use ApiResponse;

    public function refundWithdraw(Request $request, WithdrawRequest $withdraw_request)
    {

        $invalid_status = DB::transaction(function () use ($withdraw_request, $request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($withdraw_request->id);

            if ($withdraw_request->status != 'Requested') {
                return true;
            }

            $wdl_payable_locked = ExchangeGl::lockForUpdate()->whereReferenceId('WDL_PAYABLE')->first();
            $wdl_income_locked = ExchangeGl::lockForUpdate()->whereReferenceId('WDL_INCOME')->first();
            $pay_user_locked = Pay_user::lockForUpdate()->find($withdraw_request_locked->pay_user->id);
            $transaction = WithdrawTransaction::lockForUpdate()->where('withdraw_request_id', $withdraw_request_locked->id)->first();

            $invalid_log = (new MonitorTransaction([$pay_user_locked]))->execute();
            if ($invalid_log) {
                throw new TransactionFailedException();
            }

            $payable_before = $wdl_payable_locked->amount;
            $payable_after = (float) bcsub($payable_before, $transaction->amount, 4);

            $income_before = $wdl_income_locked->amount;
            $income_after = (float) bcsub($income_before, $transaction->handling_fee, 4);

            $to_amount_before = $pay_user_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, bcadd($transaction->amount, $transaction->handling_fee, 4), 4);

            $pay_user_locked->update([
                'amount' => $to_amount_after,
            ]);

            $wdl_payable_locked->update([
                'amount' => $payable_after,
            ]);

            $wdl_income_locked->update([
                'amount' => $income_after,
            ]);

            $transaction_type = Transaction_type::whereType('Withdraw Refund')->first();

            $complete_transaction = $withdraw_request_locked->withdraw_transactions()->create([
                'transaction_type_id' => $transaction_type->id,
                'user_id' => auth()->id(),
                'transaction_id' => Str::uuid(),
                'amount' => $transaction->amount,
                'handling_fee' => $transaction->handling_fee,
                'remark' => 'Refund',
            ]);

            $complete_transaction->refresh();
            $complete_transaction->update([
                'transaction_id' => (new ReferenceId())->execute('WDF', $transaction->id),
            ]);

            $transaction->pay_user_transaction_log()->create([
                'pay_user_id' => $pay_user_locked->id,
                'last_amount' => $to_amount_before,
                'current_amount' => $to_amount_after,
                'transaction_amount' => (float) bcadd($complete_transaction->amount, $complete_transaction->handling_fee, 4),
            ]);

            $withdraw_request_locked->record->update([
                'status' => 'Failed',
            ]);

            $withdraw_request_locked->record()->create([
                'pay_user_id' => $pay_user_locked->id,
                'status' => 'Refunded',
            ]);

            $withdraw_request_locked->update([
                'status' => 'Refunded',
                'description' => $request->description
            ]);
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'Cannot refund!, this request is in ' . $withdraw_request->status . ' status!'
            ], 400);
        }

        $withdraw_request->refresh();
        WithdrawStatusUpdated::dispatch('StatusRefunded', $withdraw_request);
    }

    public function confirmWithdraw(Request $request, WithdrawRequest $withdraw_request)
    {
        $invalid_status = DB::transaction(function () use ($withdraw_request, $request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($withdraw_request->id);
            if ($withdraw_request_locked->status != Status::REQUESTED) {
                return true;
            }

            $withdraw_request_locked->update([
                'status' => Status::CONFIRMED,
                'transferred_amount' => (float) bcmul($withdraw_request_locked->amount, $withdraw_request_locked->rate),
                'description' => $request->description,
                'confirmed_at' => now(),
                'confirmed_by' => 1,
                'read_at' => null,
            ]);

            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                // if (app()->environment() === 'production') {
                $file->storeAs(
                    'Exchange/Withdraw/Requests/' . $withdraw_request_locked->id,
                    'screenshot.' . $file->extension(),
                    // 's3_private'
                );
                // } else {
                //     $file->storeAs('Exchange/Recharge/Requests/' . $created_request->id, 'screenshot.' . $file->extension());
                // }

                $withdraw_request_locked->update([
                    'screenshot' => 'screenshot.' . $file->extension(),
                ]);
            }
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'This request is not in Requested status!'
            ], 400);
        }

        return 'done';

        // $withdraw_request->refresh();
        // WithdrawStatusUpdated::dispatch('StatusConfirmed', $withdraw_request);
    }

    public function completeWithdraw(Request $request, WithdrawRequest $withdraw_request)
    {
        if (!Hash::check($request->password, auth()->user()->id)) {
            return response()->json([
                'message' => 'Your password is incorrect.'
            ], 400);
        }

        $invalid_status = DB::transaction(function () use ($withdraw_request) {
            $withdraw_request_locked = WithdrawRequest::lockForUpdate()->find($withdraw_request->id);
            if ($withdraw_request_locked->status != Status::CONFIRMED) {
                return true;
            }

            $cash_out_locked = GlAccount::lockForUpdate()->whereReferenceId('CASH_OUT')->first();
            $cash_in_locked = GlAccount::lockForUpdate()->whereReferenceId('CASH_IN')->first();
            // $om_locked = User::lockForUpdate()->whereHas('roles', function ($query) {
            //     $query->where('name', 'Operation Manager');
            // })->first();
            $om_locked = User::lockForUpdate()->first();
            $transaction = WithdrawTransaction::lockForUpdate()->where('withdraw_request_id', $withdraw_request_locked->id)->first();

            $invalid_log = (new MonitorTransaction([$om_locked]))->execute();
            if ($invalid_log) {
                throw new TransactionFailedException();
            }

            $payable_before = $cash_out_locked->amount;
            $payable_after = (float) bcsub($payable_before, $transaction->amount, 4);

            $income_before = $cash_in_locked->amount;
            $income_after = (float) bcsub($income_before, $transaction->handling_fee, 4);

            $to_amount_before = $om_locked->amount;
            $to_amount_after = (float) bcadd($to_amount_before, bcadd($transaction->amount, $transaction->handling_fee, 4), 4);

            $om_locked->update([
                'amount' => $to_amount_after,
            ]);

            $cash_out_locked->update([
                'amount' => $payable_after,
            ]);

            $cash_in_locked->update([
                'amount' => $income_after,
            ]);

            $transaction_type = Transaction_type::whereType('Withdraw Complete')->first();

            $complete_transaction = $withdraw_request_locked->withdraw_transactions()->create([
                'transaction_type_id' => $transaction_type->id,
                'user_id' => auth()->id(),
                'transaction_id' => Str::uuid(),
                'amount' => $transaction->amount,
                'handling_fee' => $transaction->handling_fee,
                'remark' => 'Withdraw Complete',
            ]);

            $complete_transaction->refresh();
            $complete_transaction->update([
                'transaction_id' => (new ReferenceId())->execute('WDC', $transaction->id),
            ]);

            $transaction->user_transaction_log()->create([
                'user_id' => $om_locked->id,
                'last_amount' => $to_amount_before,
                'current_amount' => $to_amount_after,
                'transaction_amount' => (float) bcadd($complete_transaction->amount, $complete_transaction->handling_fee, 4),
            ]);

            $withdraw_request_locked->record->update([
                'status' => 'Succeed',
                'completed_by' => auth()->id(),
            ]);

            $withdraw_request_locked->update([
                'status' => 'Completed'
            ]);
        }, 5);

        if ($invalid_status) {
            return response()->json([
                'message' => 'This request is not in Confirmed status!'
            ], 400);
        }

        // $withdraw_request->refresh();
        // WithdrawStatusCompleted::dispatch($withdraw_request);
    }
}
