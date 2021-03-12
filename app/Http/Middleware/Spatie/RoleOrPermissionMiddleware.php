<?php

namespace App\Http\Middleware\Spatie;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if($request->wantsJson()){
                return response()->json(['error'=>'Unauthenticated']);
            }
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! Auth::guard($guard)->user()->hasAnyRole($rolesOrPermissions) && ! Auth::guard($guard)->user()->hasAnyPermission($rolesOrPermissions)) {
            if($request->wantsJson()){
                return response()->json(['error'=>'Unauthorized']);
            }
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
    }
}
