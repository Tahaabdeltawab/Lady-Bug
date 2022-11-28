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

        $this->middleware('permission:animal_fodder_types.store')->only(['store']);
        $this->middleware('permission:animal_fodder_types.update')->only(['update']);
        $this->middleware('permission:animal_fodder_types.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $animalFodderTypes = $this->animalFodderTypeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => AnimalFodderTypeResource::collection($animalFodderTypes['all']), 'meta' => $animalFodderTypes['meta']], 'Animal Fodder Types retrieved successfully');
    }

    public function store(CreateAnimalFodderTypeAPIRequest $request)
    {
        $input = $request->validated();

        $animalFodderType = $this->animalFodderTypeRepository->create($input);

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'Animal Fodder Type saved successfully');
    }

    public function show($id)
    {
        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'Animal Fodder Type retrieved successfully');
    }

    public function update($id, CreateAnimalFodderTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        $animalFodderType = $this->animalFodderTypeRepository->update($input, $id);

        return $this->sendResponse(new AnimalFodderTypeResource($animalFodderType), 'AnimalFodderType updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var AnimalFodderType $animalFodderType */
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            return $this->sendError('Animal Fodder Type not found');
        }

        $animalFodderType->delete();

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
