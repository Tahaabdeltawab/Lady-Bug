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
use App\Models\Fertilizer;
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
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully');
    }


    public function toggle_finish($id)
    {
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Service Task not found');
        }

        $msg = $task->done ? 'Task unfinished successfully' : 'Task finished successfully' ;
        $this->taskRepository->save_localized(['done' => !$task->done], $id);

        return $this->sendSuccess($msg);
    }


    public function getRelations($task_type_id = null)
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
            $ferts = Fertilizer::get(['id', 'name', 'producer', 'usage_rate']);
            return $this->sendResponse([
                'fertilizers' => FertilizerSmResource::collection($ferts),
                'units' => [
                    ['value' => 'kilo', 'name' => app()->getLocale()=='ar' ?  'كيلو' : 'Kilo'],
                    ['value' => 'gram', 'name' => app()->getLocale()=='ar' ?  'جرام' : 'Gram'],
                ]
            ], '');
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
            foreach ($input['tasks'] as $t) {
                $this->taskRepository->create($t);
            }
            return $this->sendSuccess('tasks created successfully');
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
        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        $task->delete();

        return $this->sendSuccess('Task deleted successfully');
    }
}
