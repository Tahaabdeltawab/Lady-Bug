<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Farm;
use Illuminate\Http\Request;

class FarmRole
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
        if (isset($request->farm) || isset($request->farm_id))
        {
            $farm = Farm::where('id', $request->farm ?? $request->farm_id)->first();
    
            if (empty($farm))
            {
                return response()->json([
                    'success' => false,
                    'data' => (object)[],
                    'code' => 404,
                    'message' => 'Farm not found'
                ]);
            }
    
            if(!auth()->user()->hasRole(config('laratrust.taha.admin_role')))
            {
                $farm_id = $farm->id;
    
                $user_farm = auth()->user()->allTeams()->where('id', $farm_id)->first();
    
                if(!$user_farm)
                {
                    return response()->json([
                        'success' => false,
                        'data' => (object)[],
                        'code' => 989,
                        'message' => 'User is not a member in this farm'
                    ]);
                }
    
    
                $allowed_roles = config('laratrust.taha.edit_farm_allowed_roles');
    
                if(!auth()->user()->hasRole($allowed_roles, $farm_id))
                {
                    return response()->json([
                        'success' => false,
                        'data' => (object)[],
                        'code' => 499,
                        'message' => 'User does not have any of the necessary access rights.'
                    ]);
                }
                return $next($request);
            }
        }
        return $next($request);
    }
}
