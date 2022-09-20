<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Post;

use App\Http\Helpers\CheckPermission;
use App\Http\Controllers\AppBaseController;
use App\Models\Farm;
use App\Models\FarmReport;
use App\Models\Product;
use App\Models\Task;

class BusinessRole
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

        if (isset($request->business) || isset($request->business_id))
        {
            $business_id = $request->business ?? $request->business_id;
        }

        elseif(isset($request->task) || isset($request->farm_report) || isset($request->post) || isset($request->farm) || isset($request->product))

        {
            // business relations [post, service task, table], tables that have business_id
            // check for "business_id" in these relations and the auth user
            if (isset($request->task))
            {
                $task = Task::find($request->task);
                if(empty($task))
                {
                    return $app_base_controller->sendError('Not found task');
                }
                $business_id = $task->business_id;
            }

            elseif(isset($request->farm_report))

            {
                $farm_report = FarmReport::find($request->farm_report);
                if(empty($farm_report))
                {
                    return $app_base_controller->sendError('Not found report');
                }
                $business_id = $farm_report->business_id;
            }

            elseif(isset($request->post))

            {
                $post = Post::find($request->post);
                if(empty($post))
                {
                    return $app_base_controller->sendError('Not found Post');
                }
                $business_id = $post->business_id; // may be null because post may not belong to a business so have no business_id
            }
            elseif(isset($request->farm))

            {
                $farm = Farm::find($request->farm);
                if(empty($farm))
                {
                    return $app_base_controller->sendError('Not found farm');
                }
                $business_id = $farm->business_id; // may be null
            }
            elseif(isset($request->product))

            {
                $product = Product::find($request->product);
                if(empty($product))
                {
                    return $app_base_controller->sendError('Not found product');
                }
                $business_id = $product->business_id; // may be null
            }
        }


        if(isset($business_id))
        {
            if(!CheckPermission::instance()->check_business_permission($business_id, auth()->user())['success'])
            {
                return response()->json(CheckPermission::instance()->check_business_permission($business_id, auth()->user()));
            }
        }

        return $next($request);
    }
}
