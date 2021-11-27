<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAnimalMedicineSourceAPIRequest;
use App\Http\Requests\API\UpdateAnimalMedicineSourceAPIRequest;
use App\Models\AnimalMedicineSource;
use App\Repositories\AnimalMedicineSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AnimalMedicineSourceResource;
use Response;

/**
 * Class AnimalMedicineSourceController
 * @package App\Http\Controllers\API
 */

class AnimalMedicineSourceAPIController extends AppBaseController
{
    /** @var  AnimalMedicineSourceRepository */
    private $animalMedicineSourceRepository;

    public function __construct(AnimalMedicineSourceRepository $animalMedicineSourceRepo)
    {
        $this->animalMedicineSourceRepository = $animalMedicineSourceRepo;

        $this->middleware('permission:animal_medicine_sources.store')->only(['store']);
        $this->middleware('permission:animal_medicine_sources.update')->only(['update']);
        $this->middleware('permission:animal_medicine_sources.destroy')->only(['destroy']);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalMedicineSources",
     *      summary="Get a listing of the AnimalMedicineSources.",
     *      tags={"AnimalMedicineSource"},
     *      description="Get all AnimalMedicineSources",
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
     *                  @SWG\Items(ref="#/definitions/AnimalMedicineSource")
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
        $animalMedicineSources = $this->animalMedicineSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => AnimalMedicineSourceResource::collection($animalMedicineSources)], 'Animal Medicine Sources retrieved successfully');
    }

    /**
     * @param CreateAnimalMedicineSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/animalMedicineSources",
     *      summary="Store a newly created AnimalMedicineSource in storage",
     *      tags={"AnimalMedicineSource"},
     *      description="Store AnimalMedicineSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalMedicineSource that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalMedicineSource")
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
     *                  ref="#/definitions/AnimalMedicineSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAnimalMedicineSourceAPIRequest $request)
    {
        $input = $request->validated();

        $animalMedicineSource = $this->animalMedicineSourceRepository->save_localized($input);

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'Animal Medicine Source saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/animalMedicineSources/{id}",
     *      summary="Display the specified AnimalMedicineSource",
     *      tags={"AnimalMedicineSource"},
     *      description="Get AnimalMedicineSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalMedicineSource",
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
     *                  ref="#/definitions/AnimalMedicineSource"
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
        /** @var AnimalMedicineSource $animalMedicineSource */
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            return $this->sendError('Animal Medicine Source not found');
        }

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'Animal Medicine Source retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAnimalMedicineSourceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/animalMedicineSources/{id}",
     *      summary="Update the specified AnimalMedicineSource in storage",
     *      tags={"AnimalMedicineSource"},
     *      description="Update AnimalMedicineSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalMedicineSource",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="AnimalMedicineSource that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AnimalMedicineSource")
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
     *                  ref="#/definitions/AnimalMedicineSource"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateAnimalMedicineSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalMedicineSource $animalMedicineSource */
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            return $this->sendError('Animal Medicine Source not found');
        }

        $animalMedicineSource = $this->animalMedicineSourceRepository->save_localized($input, $id);

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'AnimalMedicineSource updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/animalMedicineSources/{id}",
     *      summary="Remove the specified AnimalMedicineSource from storage",
     *      tags={"AnimalMedicineSource"},
     *      description="Delete AnimalMedicineSource",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of AnimalMedicineSource",
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
        /** @var AnimalMedicineSource $animalMedicineSource */
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            return $this->sendError('Animal Medicine Source not found');
        }

        $animalMedicineSource->delete();

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
