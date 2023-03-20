<?php

namespace App\Http\Controllers\Remote;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Enums\TransactionType;
use App\Models\BotTransaction;
use App\Traits\Auth\ApiResponse;
use App\Models\PlayerTransaction;
use Illuminate\Support\Facades\DB;
use App\Actions\GenerateReferenceId;
use App\Http\Controllers\Controller;
use App\Actions\Transaction\LogTransaction;
use App\Models\TransactionType as TransactionTypeModel;

class RemoteTransactionController extends Controller
{
    use ApiResponse;

    public function playerTransaction(Request $request)
    {
        DB::transaction(function () use ($request) {
            $transaction = PlayerTransaction::create([
                'reference_id' => (new GenerateReferenceId())->execute(),
                'player_id' => $request->player_id,
                'banker_id' => $request->banker_id,
                'coin' => $request->coin,
                'game_type_id' => $request->game_type_id,
                'game_match_id' => $request->game_match_id,
            ]);

            $this->history($request, $transaction, Status::USER);
        });

        return $this->responseSucceed(
            message: 'Successfully created log!',
        );
    }

    public function botTransaction(Request $request)
    {
        DB::transaction(function () use ($request) {
            $transaction = BotTransaction::create([
                'reference_id' => (new GenerateReferenceId())->execute(),
                'player_id' => $request->player_id,
                'banker_id' => $request->banker_id,
                'coin' => $request->coin,
                'game_type_id' => $request->game_type_id,
                'game_match_id' => $request->game_match_id,
            ]);

            $this->history($request, $transaction, Status::BOT);
        });

        return $this->responseSucceed(
            message: 'Successfully created log!',
        );
    }

    public function history($request, $transaction, $status)
    {
        $transaction_type_id = TransactionTypeModel::whereName($status == Status::USER ? TransactionType::Player : TransactionType::Bot)->pluck('id')->first();

        (new LogTransaction(
            $transaction->history(),
            [
                // For Bettor
                'historiable_id' => $request->player_id,
                'historiable_type' => 'App\Models\User',
                'transactionable_id' => $transaction->id,
                'transactionable_type' => get_class($transaction),
                'transaction_type_id' => $transaction_type_id,
                'reference_id' => (new GenerateReferenceId())->execute(),
                'transaction_amount' => $request->coin,
                'amount_before_transaction' => $request->before_bettor,
                'amount_after_transaction' => $request->after_bettor,
                'is_from' => $request->bettor_is_from,
            ],
            $transaction->history(),
            [
                // For Banker
                'historiable_id' => $request->banker_id,
                'historiable_type' => 'App\Models\User',
                'transactionable_id' => $transaction->id,
                'transactionable_type' => get_class($transaction),
                'transaction_type_id' => $transaction_type_id,
                'reference_id' => (new GenerateReferenceId())->execute(),
                'transaction_amount' => $request->coin,
                'amount_before_transaction' => $request->before_banker,
                'amount_after_transaction' => $request->after_banker,
                'is_from' => $request->banker_is_from,
            ]
        ))->execute();
    }
}
