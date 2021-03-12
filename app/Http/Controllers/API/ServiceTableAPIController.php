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

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTables",
     *      summary="Get a listing of the ServiceTables.",
     *      tags={"ServiceTable"},
     *      description="Get all ServiceTables",
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
     *                  @SWG\Items(ref="#/definitions/ServiceTable")
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
        $serviceTables = $this->serviceTableRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ServiceTableResource::collection($serviceTables)], 'Service Tables retrieved successfully');
    }

    /**
     * @param CreateServiceTableAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/serviceTables",
     *      summary="Store a newly created ServiceTable in storage",
     *      tags={"ServiceTable"},
     *      description="Store ServiceTable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceTable that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceTable")
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
     *                  ref="#/definitions/ServiceTable"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateServiceTableAPIRequest $request)
    {
        $input = $request->validated();

        $serviceTable = $this->serviceTableRepository->save_localized($input);

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'Service Table saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/serviceTables/{id}",
     *      summary="Display the specified ServiceTable",
     *      tags={"ServiceTable"},
     *      description="Get ServiceTable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTable",
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
     *                  ref="#/definitions/ServiceTable"
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
        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'Service Table retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateServiceTableAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/serviceTables/{id}",
     *      summary="Update the specified ServiceTable in storage",
     *      tags={"ServiceTable"},
     *      description="Update ServiceTable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTable",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="ServiceTable that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/ServiceTable")
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
     *                  ref="#/definitions/ServiceTable"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateServiceTableAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        $serviceTable = $this->serviceTableRepository->save_localized($input, $id);

        return $this->sendResponse(new ServiceTableResource($serviceTable), 'ServiceTable updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/serviceTables/{id}",
     *      summary="Remove the specified ServiceTable from storage",
     *      tags={"ServiceTable"},
     *      description="Delete ServiceTable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of ServiceTable",
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
        /** @var ServiceTable $serviceTable */
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            return $this->sendError('Service Table not found');
        }

        $serviceTable->delete();

        return $this->sendSuccess('Service Table deleted successfully');
    }
}
