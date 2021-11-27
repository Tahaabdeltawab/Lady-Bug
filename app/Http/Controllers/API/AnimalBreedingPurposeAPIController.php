<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAnimalBreedingPurposeAPIRequest;
use App\Http\Requests\API\UpdateAnimalBreedingPurposeAPIRequest;
use App\Models\AnimalBreedingPurpose;
use App\Repositories\AnimalBreedingPurposeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AnimalBreedingPurposeResource;
use Response;

/**
 * Class AnimalBreedingPurposeController
 * @package App\Http\Controllers\API
 */

class AnimalBreedingPurposeAPIController extends AppBaseController
{
    /** @var  AnimalBreedingPurposeRepository */
    private $animalBreedingPurposeRepository;

    public function __construct(AnimalBreedingPurposeRepository $animalBreedingPurposeRepo)
    {
        $this->animalBreedingPurposeRepository = $animalBreedingPurposeRepo;

        $this->middleware('permission:animal_breeding_purposes.store')->only(['store']);
        $this->middleware('permission:animal_breeding_purposes.update')->only(['update']);
        $this->middleware('permission:animal_breeding_purposes.destroy')->only(['destroy']);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalBreedingPurposes",
     *      summary="Get a listing of the AnimalBreedingPurposes.",
     *      tags={"AnimalBreedingPurpose"},
     *      description="Get all AnimalBreedingPurposes",
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
     *                  @SWG\Items(ref="#/definitions/AnimalBreedingPurpose")
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
        $animalBreedingPurposes = $this->animalBreedingPurposeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => AnimalBreedingPurposeResource::collection($animalBreedingPurposes)], 'Animal Breeding Purposes retrieved successfully');
    }

    /**
     * @param CreateAnimalBreedingPurposeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/animalBreedingPurposes",
     *      summary="Store a newly created AnimalBreedingPurpose in storage",
     *      tags={"AnimalBreedingPurpose"},
     *      description="Store AnimalBreedingPurpose",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalBreedingPurpose that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalBreedingPurpose")
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
     *                  ref="#/definitions/AnimalBreedingPurpose"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAnimalBreedingPurposeAPIRequest $request)
    {
        $input = $request->validated();

        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->save_localized($input);

        return $this->sendResponse(new AnimalBreedingPurposeResource($animalBreedingPurpose), 'Animal Breeding Purpose saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalBreedingPurposes/{id}",
     *      summary="Display the specified AnimalBreedingPurpose",
     *      tags={"AnimalBreedingPurpose"},
     *      description="Get AnimalBreedingPurpose",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalBreedingPurpose",
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
     *                  ref="#/definitions/AnimalBreedingPurpose"
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
        /** @var AnimalBreedingPurpose $animalBreedingPurpose */
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            return $this->sendError('Animal Breeding Purpose not found');
        }

        return $this->sendResponse(new AnimalBreedingPurposeResource($animalBreedingPurpose), 'Animal Breeding Purpose retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAnimalBreedingPurposeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/animalBreedingPurposes/{id}",
     *      summary="Update the specified AnimalBreedingPurpose in storage",
     *      tags={"AnimalBreedingPurpose"},
     *      description="Update AnimalBreedingPurpose",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalBreedingPurpose",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalBreedingPurpose that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalBreedingPurpose")
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
     *                  ref="#/definitions/AnimalBreedingPurpose"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateAnimalBreedingPurposeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalBreedingPurpose $animalBreedingPurpose */
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            return $this->sendError('Animal Breeding Purpose not found');
        }

        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->save_localized($input, $id);

        return $this->sendResponse(new AnimalBreedingPurposeResource($animalBreedingPurpose), 'AnimalBreedingPurpose updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/animalBreedingPurposes/{id}",
     *      summary="Remove the specified AnimalBreedingPurpose from storage",
     *      tags={"AnimalBreedingPurpose"},
     *      description="Delete AnimalBreedingPurpose",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalBreedingPurpose",
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
        /** @var AnimalBreedingPurpose $animalBreedingPurpose */
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            return $this->sendError('Animal Breeding Purpose not found');
        }

        $animalBreedingPurpose->delete();

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
