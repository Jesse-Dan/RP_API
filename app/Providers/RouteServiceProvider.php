<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Session;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot()
    {
        if (!(Session()->has('loginId'))) {
            $user = User::where('id', '=', Session::get('loginId'))->first();
            if ($user) {$user->revoke();

                return  response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Unauthenticated'
                ], 300);

                        } else {
                RateLimiter::for('web', function (Request $request) {
                    return Limit::perMinute(/*60*/20)->by($request->user()?->id ?: $request->ip());
                });

                $this->routes(function () {
                    Route::middleware('web')
                        ->prefix('web')
                        ->group(base_path('routes/web.php'));

                    Route::middleware('web')
                        ->group(base_path('routes/web.php'));
                });
            }
        }

    }
}
