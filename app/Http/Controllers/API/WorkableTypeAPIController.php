<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWorkableTypeAPIRequest;
use App\Http\Requests\API\UpdateWorkableTypeAPIRequest;
use App\Models\WorkableType;
use App\Repositories\WorkableTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WorkableTypeResource;
use Response;

/**
 * Class WorkableTypeController
 * @package App\Http\Controllers\API
 */

class WorkableTypeAPIController extends AppBaseController
{
    /** @var  WorkableTypeRepository */
    private $workableTypeRepository;

    public function __construct(WorkableTypeRepository $workableTypeRepo)
    {
        $this->workableTypeRepository = $workableTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/workableTypes",
     *      summary="Get a listing of the WorkableTypes.",
     *      tags={"WorkableType"},
     *      description="Get all WorkableTypes",
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
     *                  @SWG\Items(ref="#/definitions/WorkableType")
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
        $workableTypes = $this->workableTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => WorkableTypeResource::collection($workableTypes)], 'Workable Types retrieved successfully');
    }

    /**
     * @param CreateWorkableTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/workableTypes",
     *      summary="Store a newly created WorkableType in storage",
     *      tags={"WorkableType"},
     *      description="Store WorkableType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkableType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkableType")
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
     *                  ref="#/definitions/WorkableType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateWorkableTypeAPIRequest $request)
    {
        $input = $request->validated();

        $workableType = $this->workableTypeRepository->save_localized($input);

        return $this->sendResponse(new WorkableTypeResource($workableType), 'Workable Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/workableTypes/{id}",
     *      summary="Display the specified WorkableType",
     *      tags={"WorkableType"},
     *      description="Get WorkableType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableType",
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
     *                  ref="#/definitions/WorkableType"
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
        /** @var WorkableType $workableType */
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            return $this->sendError('Workable Type not found');
        }

        return $this->sendResponse(new WorkableTypeResource($workableType), 'Workable Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateWorkableTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/workableTypes/{id}",
     *      summary="Update the specified WorkableType in storage",
     *      tags={"WorkableType"},
     *      description="Update WorkableType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkableType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkableType")
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
     *                  ref="#/definitions/WorkableType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateWorkableTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var WorkableType $workableType */
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            return $this->sendError('Workable Type not found');
        }

        $workableType = $this->workableTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new WorkableTypeResource($workableType), 'WorkableType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/workableTypes/{id}",
     *      summary="Remove the specified WorkableType from storage",
     *      tags={"WorkableType"},
     *      description="Delete WorkableType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableType",
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
        /** @var WorkableType $workableType */
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            return $this->sendError('Workable Type not found');
        }

        $workableType->delete();

        return $this->sendSuccess('Workable Type deleted successfully');
    }
}
