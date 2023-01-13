<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTaskTypeAPIRequest;
use App\Http\Requests\API\UpdateTaskTypeAPIRequest;
use App\Models\TaskType;
use App\Repositories\TaskTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TaskTypeResource;
use App\Models\Task;
use Response;

/**
 * Class TaskTypeController
 * @package App\Http\Controllers\API
 */

class TaskTypeAPIController extends AppBaseController
{
    /** @var  TaskTypeRepository */
    private $taskTypeRepository;

    public function __construct(TaskTypeRepository $taskTypeRepo)
    {
        $this->taskTypeRepository = $taskTypeRepo;

        $this->middleware('permission:task_types.store')->only(['store']);
        $this->middleware('permission:task_types.update')->only(['update']);
        $this->middleware('permission:task_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $taskTypes = $this->taskTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => TaskTypeResource::collection($taskTypes['all']), 'meta' => $taskTypes['meta']], 'Task Types retrieved successfully');
    }

    public function store(CreateTaskTypeAPIRequest $request)
    {
        $input = $request->validated();

        $taskType = $this->taskTypeRepository->create($input);

        return $this->sendResponse(new TaskTypeResource($taskType), 'Task Type saved successfully');
    }

    public function show($id)
    {
        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        return $this->sendResponse(new TaskTypeResource($taskType), 'Task Type retrieved successfully');
    }

    public function update($id, CreateTaskTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        $taskType = $this->taskTypeRepository->update($input, $id);

        return $this->sendResponse(new TaskTypeResource($taskType), 'TaskType updated successfully');
    }

    public function destroy($id)
    {
        if(in_array($id, Task::$used_task_types))
            return $this->sendError('Used Task Types are not deletable');

        try
        {
        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        $taskType->delete();

          return $this->sendSuccess('Model deleted successfully');
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
