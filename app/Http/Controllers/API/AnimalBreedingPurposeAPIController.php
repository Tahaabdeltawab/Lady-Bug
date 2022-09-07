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

    public function index(Request $request)
    {
        $animalBreedingPurposes = $this->animalBreedingPurposeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => AnimalBreedingPurposeResource::collection($animalBreedingPurposes)], 'Animal Breeding Purposes retrieved successfully');
    }

    public function store(CreateAnimalBreedingPurposeAPIRequest $request)
    {
        $input = $request->validated();

        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->save_localized($input);

        return $this->sendResponse(new AnimalBreedingPurposeResource($animalBreedingPurpose), 'Animal Breeding Purpose saved successfully');
    }

    public function show($id)
    {
        /** @var AnimalBreedingPurpose $animalBreedingPurpose */
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            return $this->sendError('Animal Breeding Purpose not found');
        }

        return $this->sendResponse(new AnimalBreedingPurposeResource($animalBreedingPurpose), 'Animal Breeding Purpose retrieved successfully');
    }

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
