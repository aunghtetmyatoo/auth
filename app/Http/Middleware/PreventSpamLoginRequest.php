<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Traits\Auth\ApiResponse;

class PreventSpamLoginRequest
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
        if (!$request->has("phone_number")) {
            return $this->responseUnprocessableEntity();
        }
        $user = User::where("phone_number", $request->phone_number)->first();
        if (!$user) {
            return $this->responseUnprocessableEntity();
        }
        if ($user->frozen_at || $user->password_mistook_at) {
            return $this->responseSomethingWentWrong(message: "passwords.freezed");
        }
        return $next($request);
    }
}
