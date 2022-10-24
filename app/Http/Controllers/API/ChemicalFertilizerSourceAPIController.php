<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChemicalFertilizerSourceAPIRequest;
use App\Http\Requests\API\UpdateChemicalFertilizerSourceAPIRequest;
use App\Models\ChemicalFertilizerSource;
use App\Repositories\ChemicalFertilizerSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ChemicalFertilizerSourceResource;
use Response;

/**
 * Class ChemicalFertilizerSourceController
 * @package App\Http\Controllers\API
 */

class ChemicalFertilizerSourceAPIController extends AppBaseController
{
    /** @var  ChemicalFertilizerSourceRepository */
    private $chemicalFertilizerSourceRepository;

    public function __construct(ChemicalFertilizerSourceRepository $chemicalFertilizerSourceRepo)
    {
        $this->chemicalFertilizerSourceRepository = $chemicalFertilizerSourceRepo;

        $this->middleware('permission:chemical_fertilizer_sources.store')->only(['store']);
        $this->middleware('permission:chemical_fertilizer_sources.update')->only(['update']);
        $this->middleware('permission:chemical_fertilizer_sources.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $chemicalFertilizerSources = $this->chemicalFertilizerSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => ChemicalFertilizerSourceResource::collection($chemicalFertilizerSources)], 'Chemical Fertilizer Sources retrieved successfully');
    }

    public function store(CreateChemicalFertilizerSourceAPIRequest $request)
    {
        $input = $request->validated();

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->create($input);

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'Chemical Fertilizer Source saved successfully');
    }

    public function show($id)
    {
        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'Chemical Fertilizer Source retrieved successfully');
    }

    public function update($id, CreateChemicalFertilizerSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->update($input, $id);

        return $this->sendResponse(new ChemicalFertilizerSourceResource($chemicalFertilizerSource), 'ChemicalFertilizerSource updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var ChemicalFertilizerSource $chemicalFertilizerSource */
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            return $this->sendError('Chemical Fertilizer Source not found');
        }

        $chemicalFertilizerSource->delete();

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
