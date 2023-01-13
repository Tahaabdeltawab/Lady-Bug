<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChemicalDetailAPIRequest;
use App\Http\Requests\API\UpdateChemicalDetailAPIRequest;
use App\Models\ChemicalDetail;
use App\Repositories\ChemicalDetailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ChemicalDetailResource;
use Response;

/**
 * Class ChemicalDetailController
 * @package App\Http\Controllers\API
 */

class ChemicalDetailAPIController extends AppBaseController
{
    /** @var  ChemicalDetailRepository */
    private $chemicalDetailRepository;

    public function __construct(ChemicalDetailRepository $chemicalDetailRepo)
    {
        $this->chemicalDetailRepository = $chemicalDetailRepo;
    }

    public function index(Request $request)
    {
        $chemicalDetails = $this->chemicalDetailRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => ChemicalDetailResource::collection($chemicalDetails['all']), 'meta' => $chemicalDetails['meta']], 'Chemical Details retrieved successfully');
    }

    public function store(CreateChemicalDetailAPIRequest $request)
    {
        $input = $request->validated();

        $chemicalDetail = $this->chemicalDetailRepository->create($input);

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'Chemical Detail saved successfully');
    }

    public function show($id)
    {
        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'Chemical Detail retrieved successfully');
    }

    public function update($id, CreateChemicalDetailAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        $chemicalDetail = $this->chemicalDetailRepository->update($input, $id);

        return $this->sendResponse(new ChemicalDetailResource($chemicalDetail), 'ChemicalDetail updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var ChemicalDetail $chemicalDetail */
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            return $this->sendError('Chemical Detail not found');
        }

        $chemicalDetail->delete();

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
