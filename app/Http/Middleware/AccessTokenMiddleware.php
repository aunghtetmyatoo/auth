<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AccessToken;
use App\Traits\Auth\ApiResponse;

class AccessTokenMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $action)
    {
        if (!$request->has('token') || !$request->has('phone_number')) {
            return $this->responseUnauthenticated();
        }

        $token = AccessToken::whereIdentifier($request->phone_number)
            ->whereAction($action)->first();

        if (!$token) {
            return $this->responseUnauthenticated();
        }

        if (!Hash::check($request->token, $token->token)) {
            return $this->responseUnauthenticated();
        }

        if ($token->expired_at <= now()) {
            $token->delete();
            return $this->responseUnauthenticated();
        }
        return $next($request);
    }
}
