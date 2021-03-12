<?php

namespace App\Http\Middleware\Spatie;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if (app('auth')->guard($guard)->guest()) {
            if($request->wantsJson()){
                return response()->json(['error'=>'Unauthenticated']);
            }
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (app('auth')->guard($guard)->user()->can($permission)) {
                return $next($request);
            }
        }
        if($request->wantsJson()){
            return response()->json(['error'=>'Unauthorized']);
        }
        throw UnauthorizedException::forPermissions($permissions);
    }
}
