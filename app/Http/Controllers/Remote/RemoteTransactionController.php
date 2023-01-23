<?php

namespace App\Http\Controllers\Remote;

use Exception;
use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use App\Enums\TransactionType;
use App\Models\BotTransaction;
use App\Models\PlayerTransaction;
use Illuminate\Support\Facades\DB;
use App\Actions\GenerateReferenceId;
use App\Http\Controllers\Controller;
use App\Models\TransactionType as TransactionTypeModel;

class RemoteTransactionController extends Controller
{
    public function playerTransaction(Request $request)
    {
        DB::transaction(function () use ($request) {
            $player_transaction = PlayerTransaction::create([
                'reference_id' => (new GenerateReferenceId())->execute(),
                'player_id' => $request->player_id,
                'banker_id' => $request->banker_id,
                'coin' => $request->coin,
                'game_type_id' => $request->game_type_id,
                'game_match_id' => $request->game_match_id,
            ]);

            $this->history($request, $player_transaction);
        });
    }

    public function botTransaction(Request $request)
    {
        DB::transaction(function () use ($request) {
            $player_transaction = BotTransaction::create([
                'reference_id' => (new GenerateReferenceId())->execute(),
                'player_id' => $request->player_id,
                'banker_id' => $request->banker_id,
                'coin' => $request->coin,
                'game_type_id' => $request->game_type_id,
                'game_match_id' => $request->game_match_id,
            ]);

            $this->history($request, $player_transaction);
        });
    }

    public function history($request, $player_transaction)
    {
        $transaction_type_id = TransactionTypeModel::where('name', TransactionType::Player)->pluck('id')->first();
        $user = User::find($request->player_id);
        $banker = User::find($request->banker_id);

        // For Bettor
        $player_history = new History;
        $player_history->transaction_type_id = $transaction_type_id;
        $player_history->reference_id = (new GenerateReferenceId())->execute();
        $player_history->transaction_amount = $request->coin;
        $player_history->amount_before_transaction = $request->before_bettor;
        $player_history->amount_after_transaction = $request->after_bettor;
        $player_history->is_from = $request->bettor_is_from;
        $player_history->historiable()->associate($player_transaction);
        $player_history->transactionable()->associate($user);
        $player_history->save();

        // For Banker
        $banker_history = new History;
        $banker_history->transaction_type_id = $transaction_type_id;
        $banker_history->reference_id = (new GenerateReferenceId())->execute();
        $banker_history->transaction_amount = $request->coin;
        $banker_history->amount_before_transaction =  $request->before_banker;
        $banker_history->amount_after_transaction = $request->after_banker;
        $banker_history->is_from = $request->banker_is_from;
        $banker_history->historiable()->associate($player_transaction);
        $banker_history->transactionable()->associate($banker);
        $banker_history->save();
    }
}
