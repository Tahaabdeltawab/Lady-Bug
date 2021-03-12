<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WorkableAuthRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $Aroles)
    {
        if(!empty($request->id)){ //means that this middleware will be applied of routes that have an id as a parameter like all resource methods except index
            $auth_user = auth()->user();
            //if the request is update-roles, this means that the request->id refers to the workable id not the farm id as other routes
            $farm_id = !request()->is('*/update-roles/*')? \App\Models\Farm::findorfail($request->id)->id : \App\Models\Workable::findorfail($request->id)->workable_id;

            //check if the user is a member in this workable
            $workable = \App\Models\Workable::with(['workable_roles', 'workable_roles.workable_permissions'])->where([['worker_id',$auth_user->id], ['workable_id',$farm_id], ['workable_type','App\Models\Farm']])->first();
            abort_if(!$workable, 403,'You are not a member in this farm!');

            //check if the user has the right roles in this workable
            $allowed_roles_names = explode('|', $Aroles);
            $workable_roles_names = $workable->workable_roles->pluck('name')->all();// roles of auth user in this farm
            $readable_allowed_roles = str_replace('|', ' or ', $Aroles);
            abort_if(!array_intersect($allowed_roles_names, $workable_roles_names), 403, "You are not $readable_allowed_roles in this farm!");
        }

        return $next($request);
    }
}
