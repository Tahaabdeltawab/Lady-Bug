<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InfyOm\Generator\Utils\ResponseUtil;

class BlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return ( auth()->check() && auth()->user()->status != 'accepted' ) 
        ? response()->json(ResponseUtil::makeError('Blocked User', 511)) 
        : $next($request);
        
    }
}
