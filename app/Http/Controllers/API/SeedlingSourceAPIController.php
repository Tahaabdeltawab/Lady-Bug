<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSeedlingSourceAPIRequest;
use App\Http\Requests\API\UpdateSeedlingSourceAPIRequest;
use App\Models\SeedlingSource;
use App\Repositories\SeedlingSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SeedlingSourceResource;
use Response;

/**
 * Class SeedlingSourceController
 * @package App\Http\Controllers\API
 */

class SeedlingSourceAPIController extends AppBaseController
{
    /** @var  SeedlingSourceRepository */
    private $seedlingSourceRepository;

    public function __construct(SeedlingSourceRepository $seedlingSourceRepo)
    {
        $this->seedlingSourceRepository = $seedlingSourceRepo;

        $this->middleware('permission:seedling_sources.store')->only(['store']);
        $this->middleware('permission:seedling_sources.update')->only(['update']);
        $this->middleware('permission:seedling_sources.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $seedlingSources = $this->seedlingSourceRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => SeedlingSourceResource::collection($seedlingSources['all']), 'meta' => $seedlingSources['meta']], 'Seedling Sources retrieved successfully');
    }

    public function store(CreateSeedlingSourceAPIRequest $request)
    {
        $input = $request->validated();

        $seedlingSource = $this->seedlingSourceRepository->create($input);

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'Seedling Source saved successfully');
    }

    public function show($id)
    {
        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'Seedling Source retrieved successfully');
    }

    public function update($id, CreateSeedlingSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        $seedlingSource = $this->seedlingSourceRepository->update($input, $id);

        return $this->sendResponse(new SeedlingSourceResource($seedlingSource), 'SeedlingSource updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var SeedlingSource $seedlingSource */
        $seedlingSource = $this->seedlingSourceRepository->find($id);

        if (empty($seedlingSource)) {
            return $this->sendError('Seedling Source not found');
        }

        $seedlingSource->delete();

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
