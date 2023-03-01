<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // auth api routes
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/v1/index.php'));

            // game api routes
            // Route::middleware(['api', 'auth:player', 'spam', 'multi-game'])
            Route::middleware(['api', 'auth:player'])
                ->prefix('api/v1/game')
                ->group(base_path('routes/api/v1/game.php'));

            // remote api routes
            Route::middleware('api')
                ->prefix('api/v1/remote')
                ->group(base_path('routes/api/v1/remote.php'));

            // user api routes
            Route::middleware('api')
                ->prefix('api/v1/user')
                ->group(base_path('routes/api/v1/user.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
