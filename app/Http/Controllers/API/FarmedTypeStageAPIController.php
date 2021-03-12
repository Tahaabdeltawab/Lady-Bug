<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeStageAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeStageAPIRequest;
use App\Models\FarmedTypeStage;
use App\Repositories\FarmedTypeStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeStageResource;
use Response;

/**
 * Class FarmedTypeStageController
 * @package App\Http\Controllers\API
 */

class FarmedTypeStageAPIController extends AppBaseController
{
    /** @var  FarmedTypeStageRepository */
    private $farmedTypeStageRepository;

    public function __construct(FarmedTypeStageRepository $farmedTypeStageRepo)
    {
        $this->farmedTypeStageRepository = $farmedTypeStageRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeStages",
     *      summary="Get a listing of the FarmedTypeStages.",
     *      tags={"FarmedTypeStage"},
     *      description="Get all FarmedTypeStages",
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
     *                  @SWG\Items(ref="#/definitions/FarmedTypeStage")
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
        $farmedTypeStages = $this->farmedTypeStageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeStageResource::collection($farmedTypeStages)], 'Farmed Type Stages retrieved successfully');
    }

    /**
     * @param CreateFarmedTypeStageAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmedTypeStages",
     *      summary="Store a newly created FarmedTypeStage in storage",
     *      tags={"FarmedTypeStage"},
     *      description="Store FarmedTypeStage",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeStage that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeStage")
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
     *                  ref="#/definitions/FarmedTypeStage"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmedTypeStageAPIRequest $request)
    {
        $input = $request->validated();

        $farmedTypeStage = $this->farmedTypeStageRepository->save_localized($input);

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'Farmed Type Stage saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeStages/{id}",
     *      summary="Display the specified FarmedTypeStage",
     *      tags={"FarmedTypeStage"},
     *      description="Get FarmedTypeStage",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeStage",
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
     *                  ref="#/definitions/FarmedTypeStage"
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
        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'Farmed Type Stage retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmedTypeStageAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmedTypeStages/{id}",
     *      summary="Update the specified FarmedTypeStage in storage",
     *      tags={"FarmedTypeStage"},
     *      description="Update FarmedTypeStage",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeStage",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeStage that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeStage")
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
     *                  ref="#/definitions/FarmedTypeStage"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateFarmedTypeStageAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        $farmedTypeStage = $this->farmedTypeStageRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmedTypeStageResource($farmedTypeStage), 'FarmedTypeStage updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmedTypeStages/{id}",
     *      summary="Remove the specified FarmedTypeStage from storage",
     *      tags={"FarmedTypeStage"},
     *      description="Delete FarmedTypeStage",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeStage",
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
        /** @var FarmedTypeStage $farmedTypeStage */
        $farmedTypeStage = $this->farmedTypeStageRepository->find($id);

        if (empty($farmedTypeStage)) {
            return $this->sendError('Farmed Type Stage not found');
        }

        $farmedTypeStage->delete();

        return $this->sendSuccess('Farmed Type Stage deleted successfully');
    }
}
