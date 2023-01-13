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

    public function index(Request $request)
    {
        $animalMedicineSources = $this->animalMedicineSourceRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => AnimalMedicineSourceResource::collection($animalMedicineSources['all']), 'meta' => $animalMedicineSources['meta']], 'Animal Medicine Sources retrieved successfully');
    }

    public function store(CreateAnimalMedicineSourceAPIRequest $request)
    {
        $input = $request->validated();

        $animalMedicineSource = $this->animalMedicineSourceRepository->create($input);

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'Animal Medicine Source saved successfully');
    }

    public function show($id)
    {
        /** @var AnimalMedicineSource $animalMedicineSource */
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            return $this->sendError('Animal Medicine Source not found');
        }

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'Animal Medicine Source retrieved successfully');
    }

    public function update($id, CreateAnimalMedicineSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalMedicineSource $animalMedicineSource */
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            return $this->sendError('Animal Medicine Source not found');
        }

        $animalMedicineSource = $this->animalMedicineSourceRepository->update($input, $id);

        return $this->sendResponse(new AnimalMedicineSourceResource($animalMedicineSource), 'AnimalMedicineSource updated successfully');
    }

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
