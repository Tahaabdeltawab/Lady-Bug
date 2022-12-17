<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTaskAPIRequest;
use App\Http\Requests\API\UpdateTaskAPIRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AcWithInsecticideResource;
use App\Http\Resources\FertilizerSmResource;
use App\Http\Resources\TaskResource;
use App\Models\Ac;
use App\Models\Business;
use App\Models\Fertilizer;
use App\Models\NutElemValue;
use App\Models\TaskType;
use Illuminate\Support\Facades\Validator;
use Response;

/**
 * Class TaskController
 * @package App\Http\Controllers\API
 */

class TaskAPIController extends AppBaseController
{
    /** @var  TaskRepository */
    private $taskRepository;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepository = $taskRepo;
    }

    /**
     * Display a listing of the Task.
     * GET|HEAD /tasks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tasks = $this->taskRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => TaskResource::collection($tasks['all']), 'meta' => $tasks['meta']], 'Tasks retrieved successfully');
    }


    public function toggle_finish($id)
    {
        $task = Task::find($id);
        if (empty($task))
            return $this->sendError('Task not found');
        if(!auth()->user()->hasPermission("finish-task", $task->business_id))
            return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));
        $msg = $task->done ? 'Task unfinished successfully' : 'Task finished successfully' ;
        $task->done = !$task->done;
        $task->save();

        return $this->sendSuccess($msg);
    }


    public function getRelations($task_type_id = null, $nut_elem = null)
    {
        if($task_type_id == null){
            return $this->sendResponse([
                'task_types' => TaskType::all()
            ],
            'task types retrieved successfully'
            );
        }
        // مكافحة
        if($task_type_id == 1){
            $acs = Ac::with(['insecticides' => function($q){
                $q->select('insecticides.id', 'insecticides.name', 'insecticides.producer', 'insecticides.mix_rate');
            }])->whereHas('insecticides')->get(['acs.id', 'acs.name']);
            return $this->sendResponse([
                'acs' => AcWithInsecticideResource::collection($acs),
                'units' => [
                    ['value' => 'kilo', 'name' => app()->getLocale()=='ar' ?  'كيلو' : 'Kilo'],
                    ['value' => 'gram', 'name' => app()->getLocale()=='ar' ?  'جرام' : 'Gram'],
                ]
            ], '');
        }
        // تسميد
        elseif($task_type_id == 3){
            // يبعت العنصر علشان ياخد الأسمدة التي تحتويه
            // ولو مبعتوش ياخد العناصر علشان يبعت ما بينها
            if($nut_elem){
                if(! in_array($nut_elem, array_keys(NutElemValue::$rules)))
                    return $this->sendError('Invalid nutritional element!');
                $ferts = Fertilizer::whereHas('nutElemValue', function($q) use($nut_elem){
                    return $q->where($nut_elem, '>', 0);
                })->get(['id', 'name', 'producer', 'usage_rate', 'nut_elem_value_id']);
                return $this->sendResponse([
                    'fertilizers' => FertilizerSmResource::collection($ferts),
                ], '');

            }else{
                return $this->sendResponse([
                    'nut_elem_values' => array_map(function($elem){return [
                        'name' => __($elem),
                        'value' => $elem
                    ];}, array_keys(NutElemValue::$rules)),
                    'units' => [
                        ['value' => 'kilo', 'name' => app()->getLocale()=='ar' ?  'كيلو' : 'Kilo'],
                        ['value' => 'gram', 'name' => app()->getLocale()=='ar' ?  'جرام' : 'Gram'],
                    ]
                ], '');
            }
        }
        // ري أو عمليات حقلية 2و4
        else{
            return $this->getRelations();
        }
    }


    /**
     * Store a newly created Task in storage.
     * POST /tasks
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if($request->tasks){
            $validator = Validator::make($request->all(), Task::$mass_rules);
            if ($validator->fails())
                return $this->sendError($validator->errors()->first());

            $input = $validator->validated();
            $tasks = [];
            foreach ($input['tasks'] as $t) {
                $tasks[] = $this->taskRepository->create($t);
            }
            return $this->sendResponse(TaskResource::collection($tasks), 'Tasks saved successfully');
        }

        $validator = Validator::make($request->all(), Task::$rules);
        if ($validator->fails())
            return $this->sendError($validator->errors()->first());

        $input = $validator->validated();
        $task = $this->taskRepository->create($input);

        return $this->sendResponse(new TaskResource($task), 'Task saved successfully');
    }

    /**
     * Display the specified Task.
     * GET|HEAD /tasks/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        return $this->sendResponse(new TaskResource($task), 'Task retrieved successfully');
    }

    /**
     * Update the specified Task in storage.
     * PUT/PATCH /tasks/{id}
     *
     * @param int $id
     * @param UpdateTaskAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTaskAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        $task = $this->taskRepository->update($input, $id);

        return $this->sendResponse(new TaskResource($task), 'Task updated successfully');
    }

    /**
     * Remove the specified Task from storage.
     * DELETE /tasks/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        $task->delete();

        return $this->sendSuccess('Task deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
