<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWorkableAPIRequest;
use App\Http\Requests\API\UpdateWorkableAPIRequest;
use App\Models\Workable;
use App\Repositories\WorkableRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WorkableResource;
use Response;

/**
 * Class WorkableController
 * @package App\Http\Controllers\API
 */

class WorkableAPIController extends AppBaseController
{
    /** @var  WorkableRepository */
    private $workableRepository;

    public function __construct(WorkableRepository $workableRepo)
    {
        $this->workableRepository = $workableRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/workables",
     *      summary="Get a listing of the Workables.",
     *      tags={"Workable"},
     *      description="Get all Workables",
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
     *                  @SWG\Items(ref="#/definitions/Workable")
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
        $workables = $this->workableRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => WorkableResource::collection($workables)], 'Workables retrieved successfully');
    }

    /**
     * @param CreateWorkableAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/workables",
     *      summary="Store a newly created Workable in storage",
     *      tags={"Workable"},
     *      description="Store Workable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Workable that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Workable")
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
     *                  ref="#/definitions/Workable"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateWorkableAPIRequest $request)
    {
        $input = $request->validated();

        $workable = $this->workableRepository->save_localized($input);

        return $this->sendResponse(new WorkableResource($workable), 'Workable saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/workables/{id}",
     *      summary="Display the specified Workable",
     *      tags={"Workable"},
     *      description="Get Workable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Workable",
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
     *                  ref="#/definitions/Workable"
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
        /** @var Workable $workable */
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            return $this->sendError('Workable not found');
        }

        return $this->sendResponse(new WorkableResource($workable), 'Workable retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateWorkableAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/workables/{id}",
     *      summary="Update the specified Workable in storage",
     *      tags={"Workable"},
     *      description="Update Workable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Workable",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Workable that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Workable")
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
     *                  ref="#/definitions/Workable"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateWorkableAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Workable $workable */
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            return $this->sendError('Workable not found');
        }

        $workable = $this->workableRepository->save_localized($input, $id);

        return $this->sendResponse(new WorkableResource($workable), 'Workable updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/workables/{id}",
     *      summary="Remove the specified Workable from storage",
     *      tags={"Workable"},
     *      description="Delete Workable",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Workable",
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
        /** @var Workable $workable */
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            return $this->sendError('Workable not found');
        }

        $workable->delete();

        return $this->sendSuccess('Workable deleted successfully');
    }
}
