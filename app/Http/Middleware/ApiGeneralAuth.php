<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InfyOm\Generator\Utils\ResponseUtil;

class ApiGeneralAuth
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
        // if( $request->api_general_auth !== env('API_GENERAL_AUTH','20LAdyx%ano@0o!#vXLZBUg65')){
        if( $request->header('x-api-key') !== env('API_GENERAL_AUTH','20LAdyx%ano@0o!#vXLZBUg65')){
            return response()->json(ResponseUtil::makeError('Unauthorized to use this API', 507));
        }
        return $next($request);
    }
}
