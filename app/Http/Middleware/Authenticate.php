<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Session;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!(Session()->has('loginId'))) {

            $user = User::where('id', '=', Session::get('loginId'))->first();

            if ($user) {

                $user->revoke();
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No Active session',
                ], 404);

            } else {

                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Unauthenticated',
                ], 400);

            }
        }
        return $next($request);
    }
}
