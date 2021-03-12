<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTaskTypeAPIRequest;
use App\Http\Requests\API\UpdateTaskTypeAPIRequest;
use App\Models\TaskType;
use App\Repositories\TaskTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TaskTypeResource;
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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/taskTypes",
     *      summary="Get a listing of the TaskTypes.",
     *      tags={"TaskType"},
     *      description="Get all TaskTypes",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/TaskType")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $taskTypes = $this->taskTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => TaskTypeResource::collection($taskTypes)], 'Task Types retrieved successfully');
    }

    /**
     * @param CreateTaskTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/taskTypes",
     *      summary="Store a newly created TaskType in storage",
     *      tags={"TaskType"},
     *      description="Store TaskType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="TaskType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/TaskType")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/TaskType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateTaskTypeAPIRequest $request)
    {
        $input = $request->validated();

        $taskType = $this->taskTypeRepository->save_localized($input);

        return $this->sendResponse(new TaskTypeResource($taskType), 'Task Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/taskTypes/{id}",
     *      summary="Display the specified TaskType",
     *      tags={"TaskType"},
     *      description="Get TaskType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of TaskType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/TaskType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        return $this->sendResponse(new TaskTypeResource($taskType), 'Task Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateTaskTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/taskTypes/{id}",
     *      summary="Update the specified TaskType in storage",
     *      tags={"TaskType"},
     *      description="Update TaskType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of TaskType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="TaskType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/TaskType")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/TaskType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateTaskTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        $taskType = $this->taskTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new TaskTypeResource($taskType), 'TaskType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/taskTypes/{id}",
     *      summary="Remove the specified TaskType from storage",
     *      tags={"TaskType"},
     *      description="Delete TaskType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of TaskType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var TaskType $taskType */
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            return $this->sendError('Task Type not found');
        }

        $taskType->delete();

        return $this->sendSuccess('Task Type deleted successfully');
    }
}
