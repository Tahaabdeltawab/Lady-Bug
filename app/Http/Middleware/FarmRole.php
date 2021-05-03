<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Farm;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\ServiceTable;
use App\Models\ServiceTask;

use App\Http\Helpers\CheckPermission;
use App\Http\Controllers\AppBaseController;

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
        $app_base_controller = new AppBaseController;

        if (isset($request->farm) || isset($request->farm_id))
        {
            $farm_id = $request->farm ?? $request->farm_id;
        }

        elseif(isset($request->service_task) || isset($request->service_table) || isset($request->post))

        {
            // farm relations [post, service task, table], tables that have farm_id
            // check for "farm_id" in these relations and the auth user
            if (isset($request->service_task))
            {
                $service_task = ServiceTask::find($request->service_task);
                if(empty($service_task))
                {
                    return $app_base_controller->sendError('Not Found Service task');
                }
                $farm_id = $service_task->farm_id;
            }

            elseif(isset($request->service_table))

            {
                $service_table = ServiceTable::find($request->service_table);
                if(empty($service_table))
                {
                    return $app_base_controller->sendError('Not Found Service table');
                }
                $farm_id = $service_table->farm_id;
            }

            elseif(isset($request->post))
            
            {
                $post = Post::find($request->post);
                if(empty($post))
                {
                    return $app_base_controller->sendError('Not Found Post');
                }
                $farm_id = $post->farm_id; // may be null
            }
        }


        if($farm_id)
        {
            if(!CheckPermission::instance()->check_farm_permission($farm_id, auth()->user())['success'])
            {
                return response()->json(CheckPermission::instance()->check_farm_permission($farm_id, auth()->user()));
            }
        }

        return $next($request);
    }
}
