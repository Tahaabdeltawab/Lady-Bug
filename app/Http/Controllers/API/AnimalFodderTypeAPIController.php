<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAnimalFodderTypeAPIRequest;
use App\Http\Requests\API\UpdateAnimalFodderTypeAPIRequest;
use App\Models\AnimalFodderType;
use App\Repositories\AnimalFodderTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AnimalFodderTypeResource;
use Response;

/**
 * Class AnimalFodderTypeController
 * @package App\Http\Controllers\API
 */

class AnimalFodderTypeAPIController extends AppBaseController
{
    /** @var  AnimalFodderTypeRepository */
    private $animalFodderTypeRepository;

    public function __construct(AnimalFodderTypeRepository $animalFodderTypeRepo)
    {
        $this->animalFodderTypeRepository = $animalFodderTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalFodderTypes",
     *      summary="Get a listing of the AnimalFodderTypes.",
     *      tags={"AnimalFodderType"},
     *      description="Get all AnimalFodderTypes",
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
     *                  @SWG\Items(ref="#/definitions/AnimalFodderType")
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
        $animalFodderTypes = $this->animalFodderTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => AnimalFodderTypeResource::collection($animalFodderTypes)], 'Animal Fodder Types retrieved successfully');
    }

    /**
     * @param CreateAnimalFodderTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/animalFodderTypes",
     *      summary="Store a newly created AnimalFodderType in storage",
     *      tags={"AnimalFodderType"},
     *      description="Store AnimalFodderType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalFodderType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalFodderType")
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
     *                  ref="#/definitions/AnimalFodderType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAnimalFodderTypeAPIRequest $request)
    {
        $input = $request->validated();

        $animalFodderType = $this->animalFodderTypeRepository->save_localized($input);

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'Animal Fodder Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalFodderTypes/{id}",
     *      summary="Display the specified AnimalFodderType",
     *      tags={"AnimalFodderType"},
     *      description="Get AnimalFodderType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderType",
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
     *                  ref="#/definitions/AnimalFodderType"
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
        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'Animal Fodder Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAnimalFodderTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/animalFodderTypes/{id}",
     *      summary="Update the specified AnimalFodderType in storage",
     *      tags={"AnimalFodderType"},
     *      description="Update AnimalFodderType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalFodderType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalFodderType")
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
     *                  ref="#/definitions/AnimalFodderType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateAnimalFodderTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        $animalFodderType = $this->animalFodderTypeRepository->save_localized($input, $id);

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'AnimalFodderType updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/animalFodderTypes/{id}",
     *      summary="Remove the specified AnimalFodderType from storage",
     *      tags={"AnimalFodderType"},
     *      description="Delete AnimalFodderType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalFodderType",
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
        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        $animalFodderType->delete();

        return $this->sendSuccess('Animal Fodder Type deleted successfully');
    }
}
