<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeClassAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeClassAPIRequest;
use App\Models\FarmedTypeClass;
use App\Repositories\FarmedTypeClassRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeClassResource;
use Response;

/**
 * Class FarmedTypeClassController
 * @package App\Http\Controllers\API
 */

class FarmedTypeClassAPIController extends AppBaseController
{
    /** @var  FarmedTypeClassRepository */
    private $farmedTypeClassRepository;

    public function __construct(FarmedTypeClassRepository $farmedTypeClassRepo)
    {
        $this->farmedTypeClassRepository = $farmedTypeClassRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeClasses",
     *      summary="Get a listing of the FarmedTypeClasses.",
     *      tags={"FarmedTypeClass"},
     *      description="Get all FarmedTypeClasses",
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
     *                  @SWG\Items(ref="#/definitions/FarmedTypeClass")
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
        $farmedTypeClasses = $this->farmedTypeClassRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeClassResource::collection($farmedTypeClasses)], 'Farmed Type Classes retrieved successfully');
    }

    /**
     * @param CreateFarmedTypeClassAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmedTypeClasses",
     *      summary="Store a newly created FarmedTypeClass in storage",
     *      tags={"FarmedTypeClass"},
     *      description="Store FarmedTypeClass",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeClass that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeClass")
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
     *                  ref="#/definitions/FarmedTypeClass"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFarmedTypeClassAPIRequest $request)
    {
        $input = $request->validated();

        $farmedTypeClass = $this->farmedTypeClassRepository->save_localized($input);

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'Farmed Type Class saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypeClasses/{id}",
     *      summary="Display the specified FarmedTypeClass",
     *      tags={"FarmedTypeClass"},
     *      description="Get FarmedTypeClass",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeClass",
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
     *                  ref="#/definitions/FarmedTypeClass"
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
        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'Farmed Type Class retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmedTypeClassAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmedTypeClasses/{id}",
     *      summary="Update the specified FarmedTypeClass in storage",
     *      tags={"FarmedTypeClass"},
     *      description="Update FarmedTypeClass",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeClass",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedTypeClass that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedTypeClass")
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
     *                  ref="#/definitions/FarmedTypeClass"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFarmedTypeClassAPIRequest $request)
    {
        $input = $request->validated();

        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        $farmedTypeClass = $this->farmedTypeClassRepository->save_localized($input, $id);

        return $this->sendResponse(new FarmedTypeClassResource($farmedTypeClass), 'FarmedTypeClass updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmedTypeClasses/{id}",
     *      summary="Remove the specified FarmedTypeClass from storage",
     *      tags={"FarmedTypeClass"},
     *      description="Delete FarmedTypeClass",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedTypeClass",
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
        /** @var FarmedTypeClass $farmedTypeClass */
        $farmedTypeClass = $this->farmedTypeClassRepository->find($id);

        if (empty($farmedTypeClass)) {
            return $this->sendError('Farmed Type Class not found');
        }

        $farmedTypeClass->delete();

        return $this->sendSuccess('Farmed Type Class deleted successfully');
    }
}
