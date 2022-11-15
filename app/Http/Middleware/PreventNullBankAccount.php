<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Auth\ApiResponse;

class PreventNullBankAccount
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
        if ($user->payment_account_number == null || $user->payment_account_number == "" || $user->payment_account_name == null || $user->payment_account_name == "" || $user->payment_types_id == null || $user->payment_types_id == "") {
            return $this->responseWithCustomErrorCode(message: "Please Enter your bank account information", status_code: 512);
        }
        return $next($request);
    }
}
