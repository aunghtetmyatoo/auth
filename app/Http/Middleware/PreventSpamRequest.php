<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\Auth;

class PreventSpamRequest
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$request->has('device_id')) {
            return $this->responseUnauthenticated();
        }
        if ($request->device_id != $user->device_id) {
            return $this->responseUnauthenticated();
        }
        if ($user->frozen_at || $user->password_mistook_at) {
            return $this->responseSomethingWentWrong(message: "passwords.freezed");
        }
        return $next($request);
    }
}
