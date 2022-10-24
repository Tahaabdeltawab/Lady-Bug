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

    public function index(Request $request)
    {
        $serviceTasks = $this->serviceTaskRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ServiceTaskResource::collection($serviceTasks)], 'Service Tasks retrieved successfully');
    }


    public function store(CreateServiceTaskAPIRequest $request)
    {
        $input = $request->validated();
        $input['done'] = 0;

        $serviceTask = $this->serviceTaskRepository->create($input);

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'Service Task saved successfully');
    }

    public function show($id)
    {
        /** @var ServiceTask $serviceTask */
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'Service Task retrieved successfully');
    }

    public function update($id, UpdateServiceTaskAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ServiceTask $serviceTask */
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            return $this->sendError('Service Task not found');
        }

        $serviceTask = $this->serviceTaskRepository->update($input, $id);

        return $this->sendResponse(new ServiceTaskResource($serviceTask), 'ServiceTask updated successfully');
    }

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
