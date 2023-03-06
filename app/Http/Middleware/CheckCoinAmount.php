<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Constants\Coin;
use App\Models\GameTypeUser;
use Illuminate\Http\Request;
use App\Exceptions\CannotConvertCoin;
use App\Exceptions\GameCoinNotEnoughException;
use App\Exceptions\UserAmountNotEnoughException;
use App\Exceptions\UserNotExistException;

class CheckCoinAmount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'game_type_id'  =>  ['required', 'string'],
            'amount' =>  ['numeric'],
            'coin' =>  ['integer'],
        ]);

        $user = User::find(auth()->user()->id);

        if (!$user) {
            throw new UserNotExistException();
        }

        if ($request->amount) {
            $user_amount = $user->amount;

            if (!$user_amount || $user_amount < $request->amount) {
                throw new UserAmountNotEnoughException();
            }

            if (fmod($request->amount, Coin::ONECOIN) != 0) {
                throw new CannotConvertCoin();
            }
        }

        if ($request->coin) {
            $game_coin = GameTypeUser::whereUserId($user->id)->whereGameTypeId($request->game_type_id)->pluck('coin')->first();

            if (!$game_coin || $game_coin < $request->coin) {
                throw new GameCoinNotEnoughException();
            }
        }

        return $next($request);
    }
}
