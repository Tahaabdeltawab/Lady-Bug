<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\Models\User;

class JWTGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {//this middleware works only if there is a token sent with the request

        $authorized = 0;
        try {

            $user = JWTAuth::parseToken()->toUser();// the parseToken here is the token sent in the request Authorization header
            if($request->identify == $user->email || $request->identify == $user->mobile )
            $authorized = 1;
        } catch (Exception $e) {
            //invalid token and not auth user
            $authorized = 0;
        }
        //authenticated user
        if($authorized){
            return response()->json('You are already logged in');
            return app('App\Http\Controllers\AuthController')->refresh();
        }
        return $next($request);
    }
}
