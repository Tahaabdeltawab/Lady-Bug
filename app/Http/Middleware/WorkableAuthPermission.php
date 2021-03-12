<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WorkableAuthPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $Apermissions)
    {
       

        return $next($request);
    }
}
