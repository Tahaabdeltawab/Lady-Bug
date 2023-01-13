<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAnimalFodderSourceAPIRequest;
use App\Http\Requests\API\UpdateAnimalFodderSourceAPIRequest;
use App\Models\AnimalFodderSource;
use App\Repositories\AnimalFodderSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AnimalFodderSourceResource;
use Response;

/**
 * Class AnimalFodderSourceController
 * @package App\Http\Controllers\API
 */

class AnimalFodderSourceAPIController extends AppBaseController
{
    /** @var  AnimalFodderSourceRepository */
    private $animalFodderSourceRepository;

    public function __construct(AnimalFodderSourceRepository $animalFodderSourceRepo)
    {
        $this->animalFodderSourceRepository = $animalFodderSourceRepo;

        $this->middleware('permission:animal_fodder_sources.store')->only(['store']);
        $this->middleware('permission:animal_fodder_sources.update')->only(['update']);
        $this->middleware('permission:animal_fodder_sources.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $animalFodderSources = $this->animalFodderSourceRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => AnimalFodderSourceResource::collection($animalFodderSources['all']), 'meta' => $animalFodderSources['meta']], 'Animal Fodder Sources retrieved successfully');
    }

    public function store(CreateAnimalFodderSourceAPIRequest $request)
    {
        $input = $request->validated();

        $animalFodderSource = $this->animalFodderSourceRepository->create($input);

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'Animal Fodder Source saved successfully');
    }

    public function show($id)
    {
        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'Animal Fodder Source retrieved successfully');
    }

    public function update($id, CreateAnimalFodderSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        $animalFodderSource = $this->animalFodderSourceRepository->update($input, $id);

        return $this->sendResponse(new AnimalFodderSourceResource($animalFodderSource), 'AnimalFodderSource updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var AnimalFodderSource $animalFodderSource */
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            return $this->sendError('Animal Fodder Source not found');
        }

        $animalFodderSource->delete();

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
