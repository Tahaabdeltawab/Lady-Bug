<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInformationAPIRequest;
use App\Http\Requests\API\UpdateInformationAPIRequest;
use App\Models\Information;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InformationResource;
use Response;

/**
 * Class InformationController
 * @package App\Http\Controllers\API
 */

class InformationAPIController extends AppBaseController
{
    /** @var  InformationRepository */
    private $informationRepository;

    public function __construct(InformationRepository $informationRepo)
    {
        $this->informationRepository = $informationRepo;

        $this->middleware('permission:information.store')->only(['store']);
        $this->middleware('permission:information.update')->only(['update']);
        $this->middleware('permission:information.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $information = $this->informationRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => InformationResource::collection($information['all']), 'meta' => $information['meta']], 'Information retrieved successfully');
    }

    public function store(CreateInformationAPIRequest $request)
    {
        $input = $request->validated();

        $information = $this->informationRepository->create($input);

        return $this->sendResponse(new InformationResource($information), 'Information saved successfully');
    }

    public function show($id)
    {
        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        return $this->sendResponse(new InformationResource($information), 'Information retrieved successfully');
    }

    public function update($id, CreateInformationAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        $information = $this->informationRepository->update($input, $id);

        return $this->sendResponse(new InformationResource($information), 'Information updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var Information $information */
        $information = $this->informationRepository->find($id);

        if (empty($information)) {
            return $this->sendError('Information not found');
        }

        $information->delete();

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
