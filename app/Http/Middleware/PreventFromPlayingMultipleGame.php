<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Auth\ApiResponse;

class PreventFromPlayingMultipleGame
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
        if (!Auth::user()) {
            return $this->responseUnauthenticated();
        }
        if (Auth::user()->play !== "FREE") {
            return $this->responseUnprocessableEntity(message: "You cannot play more than one game at one time");
        }
        return $next($request);
    }
}
