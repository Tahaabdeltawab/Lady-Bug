<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateServiceTaskAPIRequest;
use App\Http\Requests\API\UpdateServiceTaskAPIRequest;
use App\Models\ServiceTask;
use App\Repositories\ServiceTaskRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ServiceTaskResource;
use Response;

use App\Http\Helpers\CheckPermission;

/**
 * Class ServiceTaskController
 * @package App\Http\Controllers\API
 */

class ServiceTaskAPIController extends AppBaseController
{
    /** @var  ServiceTaskRepository */
    private $serviceTaskRepository;

    public function __construct(ServiceTaskRepository $serviceTaskRepo)
    {
        $this->serviceTaskRepository = $serviceTaskRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTasks",
     *      summary="Get a listing of the ServiceTasks.",
     *      tags={"ServiceTask"},
     *      description="Get all ServiceTasks",
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
     *                  @SWG\Items(ref="#/definitions/ServiceTask")
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
        $serviceTasks = $this->serviceTaskRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ServiceTaskResource::collection($serviceTasks)], 'Service Tasks retrieved successfully');
    }

    public function toggle_finish($id)
    {
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        $msg = $serviceTask->done ? 'Task unfinished successfully' : 'Task finished successfully' ;
        $this->serviceTaskRepository->save_localized(['done' => !$serviceTask->done], $id);

        return $this->sendSuccess($msg);
    }
    /**
     * @param CreateServiceTaskAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/serviceTasks",
     *      summary="Store a newly created ServiceTask in storage",
     *      tags={"ServiceTask"},
     *      description="Store ServiceTask",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceTask that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceTask")
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
     *                  ref="#/definitions/ServiceTask"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateServiceTaskAPIRequest $request)
    {
        $input = $request->validated();
        $input['done'] = 0;

        $serviceTask = $this->serviceTaskRepository->save_localized($input);

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'Service Task saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTasks/{id}",
     *      summary="Display the specified ServiceTask",
     *      tags={"ServiceTask"},
     *      description="Get ServiceTask",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTask",
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
     *                  ref="#/definitions/ServiceTask"
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
        /** @var ServiceTask $serviceTask */
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'Service Task retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateServiceTaskAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/serviceTasks/{id}",
     *      summary="Update the specified ServiceTask in storage",
     *      tags={"ServiceTask"},
     *      description="Update ServiceTask",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTask",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceTask that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceTask")
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
     *                  ref="#/definitions/ServiceTask"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateServiceTaskAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ServiceTask $serviceTask */
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        $serviceTask = $this->serviceTaskRepository->save_localized($input, $id);

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'ServiceTask updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/serviceTasks/{id}",
     *      summary="Remove the specified ServiceTask from storage",
     *      tags={"ServiceTask"},
     *      description="Delete ServiceTask",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTask",
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
        try
        {
        /** @var ServiceTask $serviceTask */
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        $serviceTask->delete();

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
