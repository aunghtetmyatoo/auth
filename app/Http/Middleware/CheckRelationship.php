<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Friend;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Exceptions\GeneralError;

class CheckRelationship
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
            'friend_id' => ['required', 'uuid'],
        ]);

        $added = Friend::where('user_id', auth()->user()->id)->where('friend_id', $request->friend_id)->where('confirm_status', Status::ADDED_FRIEND)->first();

        $confirmed = Friend::where('user_id', auth()->user()->id)->where('friend_id', $request->friend_id)->where('confirm_status', Status::CONFIRMED_FRIEND)->first();

        if ($added || $confirmed) {
            throw new GeneralError();
        }

        return $next($request);
    }
}
