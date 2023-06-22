<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\GameType;
use App\Constants\Status;
use App\Models\GameTypeUser;
use App\Models\AmountCoinLog;
use App\Models\TransactionType;
use App\Enums\TransactionType as TransactionTypeEnum;
use App\Traits\Auth\ApiResponse;
use App\Actions\ConvertCoinAmount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Transfer\TransferToPlayRequest;
use App\Http\Requests\Api\Transfer\TransferFromPlayRequest;

class TransferPlayController extends Controller
{
    use ApiResponse;

    public function __construct(private ConvertCoinAmount $convertCoinAmount)
    {
    }

    public function amountToCoin(TransferToPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'amount' => $amount] = $request->all();

        $converted_coin = $this->convertCoinAmount->handle(currency: $amount, condition: Status::CONVERT_COIN);

        $user = User::lockForUpdate()->find(auth()->user()->id);

        $game_type_user = GameTypeUser::lockForUpdate()->where('user_id', auth()->user()->id)->where('game_type_id', $game_type_id)->first();

        DB::transaction(function () use ($user, $game_type_user, $amount, $converted_coin, $game_type_id) {
            $user->update([
                'amount' => $user->amount - $amount,
            ]);

            if (!$game_type_user) {
                GameType::find($game_type_id)->users()->attach(auth()->user()->id, [
                    'coin' => $converted_coin
                ]);
            } else {
                $game_type_user->update([
                    'coin' => $game_type_user->coin + $converted_coin,
                ]);
            }

            $transaction_type_id = TransactionType::where('name', TransactionTypeEnum::AmountToCoin)->pluck('id')->first();

            AmountCoinLog::create([
                'user_id' => $user->id,
                'transaction_type_id' => $transaction_type_id,
                'amount' => $amount,
                'coin' => $converted_coin,
            ]);
        });

        return $this->responseSucceed([
            "coin" => $game_type_user->coin,
            "amount" => $user->amount,
        ]);
    }

    public function coinToAmount(TransferFromPlayRequest $request)
    {
        ['game_type_id' => $game_type_id, 'coin' => $coin] = $request->all();

        $converted_amount = $this->convertCoinAmount->handle(currency: $coin, condition: Status::CONVERT_AMOUNT);

        $user = User::lockForUpdate()->find(auth()->user()->id);

        $game_type_user = GameTypeUser::lockForUpdate()->where('user_id', auth()->user()->id)->where('game_type_id', $game_type_id)->first();

        DB::transaction(function () use ($user, $game_type_user, $coin, $converted_amount) {
            $game_type_user->update([
                'coin' => $game_type_user->coin - $coin,
            ]);

            $user->update([
                'amount' => $user->amount + $converted_amount,
            ]);

            $transaction_type_id = TransactionType::where('name', TransactionTypeEnum::CoinToAmount)->pluck('id')->first();

            AmountCoinLog::create([
                'user_id' => $user->id,
                'transaction_type_id' => $transaction_type_id,
                'amount' => $converted_amount,
                'coin' => $coin,
            ]);
        });

        return $this->responseSucceed([
            "coin" => $game_type_user->coin,
            "amount" => $user->amount,
        ]);
    }
}
