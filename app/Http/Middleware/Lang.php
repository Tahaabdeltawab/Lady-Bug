<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Lang
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
        if($request->header('Accept-Language') == 'ar')
        {
            app()->setLocale('ar');
        }
        elseif($request->header('Accept-Language') == 'en')
        {
            app()->setLocale('en');
        }
        
        return $next($request);
    }
}
