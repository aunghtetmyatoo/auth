<?php

namespace App\Actions\Transaction;

use App\Constants\Transaction;
use App\Models\TransactionType;
use App\Models\ITToITHeadFillLog;
use App\Enums\TransactionType as EnumsTransactionType;

class MonitorTransaction
{
    public function __construct(private $accounts, private bool $stop_on_first_failure = true)
    {
    }
    public function execute(int $monitor_count = Transaction::MONITOR_COUNT): array|null
    {

        // $type_exceptions = TransactionType::whereIn('name', [EnumsTransactionType::SuperAdminToBank])->pluck('id')->toArray();

        $failed_account_details = [];

        foreach ($this->accounts as $account) {

            if ($account->role != 'IT Head') {

                $transaction_logs = $account->histories()->latest()->take($monitor_count)->get();

                // For first transaction , there is no history yet
                // if (!count($transaction_logs) && $account->amount > 0) {
                //   return null;
                //   //// if ($this->stop_on_first_failure) {
                //   ////     return [null, $account];
                //   //// }
                //   //// array_push($failed_account_details, [null, $account]);
                // }
                $previous_amount = 0;

                foreach ($transaction_logs as $key => $transaction_log) {

                    $invalid = false;
                    $log_description = '';
                    $difference_amount = 0;
                    // if amounts were negative
                    if (
                        $transaction_log->amount_before_transaction < 0
                        || $transaction_log->amount_after_transaction < 0
                        || $transaction_log->transaction_amount < 0
                    ) {
                        $invalid = true;
                        $log_description = 'Nagative Amount.';
                        $difference_amount = -1;
                    }

                    // if user is transaction maker and substraction of transaction amount from before amount was different from after transaction amount
                    if (
                        !$transaction_log->is_from
                        &&
                        ((string)$transaction_log->amount_after_transaction
                            != (string)$transaction_log->amount_before_transaction - $transaction_log->transaction_amount
                        )
                        // && !in_array($transaction_log->transaction_type_id, $type_exceptions)
                    ) {
                        $invalid = true;
                        $log_description = 'User is transaction maker and substraction of transaction amount from before amount was different from after transaction amount ' . $account->name;
                        $difference_amount = $transaction_log->amount_before_transaction -  $transaction_log->amount_after_transaction;
                    }

                    // if user is not a transaction maker and amount addition of before and transaction was different from after amount
                    if (
                        $transaction_log->is_from
                        && ((string)$transaction_log->amount_after_transaction !=
                            (string)$transaction_log->amount_before_transaction + $transaction_log->transaction_amount
                        )
                    ) {
                        $invalid = true;
                        $log_description = 'User is not transaction maker and amount addition of before and transaction was different from after amount ' . $account->name;
                        $difference_amount = $transaction_log->amount_after_transaction -  $transaction_log->amount_before_transaction;
                    }

                    // if the transaction is not the latest and previous before transaction amount and current amount after transaction were different
                    if (
                        $key
                        && (string)$previous_amount != (string)$transaction_log->amount_after_transaction
                    ) {
                        $invalid = true;
                        $log_description = 'Previous before transaction amount and current amount after transaction were different ' . $account->name;
                        if ($previous_amount > $transaction_log->amount_after_transaction) {
                            $difference_amount = $previous_amount -  $transaction_log->amount_after_transaction;
                        } else {
                            $difference_amount = $transaction_log->amount_after_transaction - $previous_amount;
                        }
                    }


                    // if the transaction is latest row and amount were different
                    if (!$key && (string) $account->amount != (string)$transaction_log->amount_after_transaction) {
                        $invalid = true;
                        $log_description = 'Transaction is latest, user amount and amount after transaction were different in ' . $account->name;
                        if ($account->amount > $transaction_log->amount_after_transaction) {
                            $difference_amount = $account->amount -  $transaction_log->amount_after_transaction;
                        } else {
                            $difference_amount = $transaction_log->amount_after_transaction - $account->amount;
                        }
                    }

                    if ($invalid) {
                        // if ($this->stop_on_first_failure) {
                        //     return [$transaction_log, $account, $previous_amount, $account->amount, $log_description, $key];
                        // }

                        // array_push($failed_account_details, [$transaction_log, $account]);
                        array_push($failed_account_details, $transaction_log);
                        array_push($failed_account_details, $account);
                        array_push($failed_account_details, $log_description);
                        array_push($failed_account_details, $difference_amount);
                    }

                    $previous_amount = $transaction_log->amount_before_transaction;
                }
            }
        }

        if (count($failed_account_details)) {
            return $failed_account_details;
        }
        return null;
    }
}
