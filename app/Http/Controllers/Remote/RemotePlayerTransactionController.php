<?php

namespace App\Http\Controllers\Remote;

use App\Actions\GenerateReferenceId;
use App\Constants\TransactionTypeConstant;
use App\Exceptions\GeneralError;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\PlayerTransaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RemotePlayerTransactionController extends Controller
{
    public function index(Request $request)
    {

        $transaction_type_id = TransactionType::where('name', TransactionTypeConstant::Play_Transaction)->pluck('id')->first();
        $user = User::find($request->player_id);
        $banker = User::find($request->banker_id);

        DB::beginTransaction();
        try {
            $playerTransaction= PlayerTransaction::create([
                "reference_id" => (new GenerateReferenceId())->execute(),
                "player_id" => $request->player_id,
                "banker_id" => $request->banker_id,
                "coin" => $request->transaction_amount,
                "game_type_id" => $request->game_type_id,
                "match_id" => $request->match_id,
            ]);

            // For Bettor
            $player_history = new History;
            $player_history->transaction_type_id = $transaction_type_id;
            $player_history->reference_id = (new GenerateReferenceId())->execute();
            $player_history->transaction_amount = $request->transaction_amount;
            $player_history->amount_before_transaction = $request->bettor_amount_before_transaction;
            $player_history->amount_after_transaction = $request->bettor_amount_after_transaction;
            $player_history->is_from = $request->bettor_win;
            $player_history->historiable()->associate($playerTransaction);
            $player_history->transactionable()->associate($user);
            $player_history->save();

            // For Banker
            $banker_history = new History;
            $banker_history->transaction_type_id = $transaction_type_id;
            $banker_history->reference_id = (new GenerateReferenceId())->execute();
            $banker_history->transaction_amount = $request->transaction_amount;
            $banker_history->amount_before_transaction =  $request->banker_amount_before_transaction;
            $banker_history->amount_after_transaction = $request->banker_amount_after_transaction;
            $banker_history->is_from = $request->banker_win;
            $banker_history->historiable()->associate($playerTransaction);
            $banker_history->transactionable()->associate($banker);
            $banker_history->save();

        DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new GeneralError();
        }

    }
}
