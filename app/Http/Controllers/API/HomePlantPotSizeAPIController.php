<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHomePlantPotSizeAPIRequest;
use App\Http\Requests\API\UpdateHomePlantPotSizeAPIRequest;
use App\Models\HomePlantPotSize;
use App\Repositories\HomePlantPotSizeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HomePlantPotSizeResource;
use Response;

/**
 * Class HomePlantPotSizeController
 * @package App\Http\Controllers\API
 */

class HomePlantPotSizeAPIController extends AppBaseController
{
    /** @var  HomePlantPotSizeRepository */
    private $homePlantPotSizeRepository;

    public function __construct(HomePlantPotSizeRepository $homePlantPotSizeRepo)
    {
        $this->homePlantPotSizeRepository = $homePlantPotSizeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/homePlantPotSizes",
     *      summary="Get a listing of the HomePlantPotSizes.",
     *      tags={"HomePlantPotSize"},
     *      description="Get all HomePlantPotSizes",
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
     *                  @SWG\Items(ref="#/definitions/HomePlantPotSize")
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
        $homePlantPotSizes = $this->homePlantPotSizeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(HomePlantPotSizeResource::collection($homePlantPotSizes), 'Home Plant Pot Sizes retrieved successfully');
    }

    /**
     * @param CreateHomePlantPotSizeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/homePlantPotSizes",
     *      summary="Store a newly created HomePlantPotSize in storage",
     *      tags={"HomePlantPotSize"},
     *      description="Store HomePlantPotSize",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="HomePlantPotSize that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/HomePlantPotSize")
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
     *                  ref="#/definitions/HomePlantPotSize"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateHomePlantPotSizeAPIRequest $request)
    {
        $input = $request->all();

        $homePlantPotSize = $this->homePlantPotSizeRepository->create($input);

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'Home Plant Pot Size saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/homePlantPotSizes/{id}",
     *      summary="Display the specified HomePlantPotSize",
     *      tags={"HomePlantPotSize"},
     *      description="Get HomePlantPotSize",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantPotSize",
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
     *                  ref="#/definitions/HomePlantPotSize"
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
        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'Home Plant Pot Size retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateHomePlantPotSizeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/homePlantPotSizes/{id}",
     *      summary="Update the specified HomePlantPotSize in storage",
     *      tags={"HomePlantPotSize"},
     *      description="Update HomePlantPotSize",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantPotSize",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="HomePlantPotSize that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/HomePlantPotSize")
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
     *                  ref="#/definitions/HomePlantPotSize"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateHomePlantPotSizeAPIRequest $request)
    {
        $input = $request->all();

        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        $homePlantPotSize = $this->homePlantPotSizeRepository->update($input, $id);

        return $this->sendResponse(new HomePlantPotSizeResource($homePlantPotSize), 'HomePlantPotSize updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/homePlantPotSizes/{id}",
     *      summary="Remove the specified HomePlantPotSize from storage",
     *      tags={"HomePlantPotSize"},
     *      description="Delete HomePlantPotSize",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of HomePlantPotSize",
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
        /** @var HomePlantPotSize $homePlantPotSize */
        $homePlantPotSize = $this->homePlantPotSizeRepository->find($id);

        if (empty($homePlantPotSize)) {
            return $this->sendError('Home Plant Pot Size not found');
        }

        $homePlantPotSize->delete();

        return $this->sendSuccess('Home Plant Pot Size deleted successfully');
    }
}
