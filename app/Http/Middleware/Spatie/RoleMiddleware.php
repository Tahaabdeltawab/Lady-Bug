<?php

namespace App\Http\Middleware\Spatie;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if($request->wantsJson()){
                return response()->json(['error'=>'Unauthenticated']);
            }
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! Auth::guard($guard)->user()->hasAnyRole($roles)) {
            if($request->wantsJson()){
                return response()->json(['error'=>'Unauthorized']);
            }
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
