<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateServiceTableAPIRequest;
use App\Http\Requests\API\UpdateServiceTableAPIRequest;
use App\Models\ServiceTable;
use App\Repositories\ServiceTableRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ServiceTableResource;
use Response;

use Illuminate\Support\Facades\DB;

/**
 * Class ServiceTableController
 * @package App\Http\Controllers\API
 */

class ServiceTableAPIController extends AppBaseController
{
    /** @var  ServiceTableRepository */
    private $serviceTableRepository;

    public function __construct(ServiceTableRepository $serviceTableRepo)
    {
        $this->serviceTableRepository = $serviceTableRepo;
    }

    public function index(Request $request)
    {
        $serviceTables = $this->serviceTableRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage'),
            ['tasks']
        );

        return $this->sendResponse(['all' => ServiceTableResource::collection($serviceTables)], 'Service Tables retrieved successfully');
    }

    public function duplicate($id)
    {
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        $serviceTable->tasks()->update(
            [
            'done' => 0,
            'start_at'=> DB::raw('TIMESTAMPADD(YEAR,1,`start_at`)'),
            'notify_at'=> DB::raw('TIMESTAMPADD(YEAR,1,`notify_at`)')
            ]);

        return $this->sendSuccess(__('Table duplicated for the next year successfully'));
    }
    public function store(CreateServiceTableAPIRequest $request)
    {
        $input = $request->validated();

        $serviceTable = $this->serviceTableRepository->create($input);

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'Service Table saved successfully');
    }

    public function show($id)
    {
        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'Service Table retrieved successfully');
    }

    public function update($id, UpdateServiceTableAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        $serviceTable = $this->serviceTableRepository->update($input, $id);

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'ServiceTable updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        $serviceTable->tasks()->delete();
        $serviceTable->delete();

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
