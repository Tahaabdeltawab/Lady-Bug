<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateResidenceAPIRequest;
use App\Http\Requests\API\UpdateResidenceAPIRequest;
use App\Models\Residence;
use App\Repositories\ResidenceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ResidenceResource;
use Response;

/**
 * Class ResidenceController
 * @package App\Http\Controllers\API
 */

class ResidenceAPIController extends AppBaseController
{
    /** @var  ResidenceRepository */
    private $residenceRepository;

    public function __construct(ResidenceRepository $residenceRepo)
    {
        $this->residenceRepository = $residenceRepo;
    }

    /**
     * Display a listing of the Residence.
     * GET|HEAD /residences
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $residences = $this->residenceRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => ResidenceResource::collection($residences['all']), 'meta' => $residences['meta']], 'Residences retrieved successfully');
    }

    /**
     * Store a newly created Residence in storage.
     * POST /residences
     *
     * @param CreateResidenceAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateResidenceAPIRequest $request)
    {
        $input = $request->validated();

        $residence = $this->residenceRepository->create($input);

        return $this->sendResponse(new ResidenceResource($residence), 'Residence saved successfully');
    }

    /**
     * Display the specified Residence.
     * GET|HEAD /residences/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Residence $residence */
        $residence = $this->residenceRepository->find($id);

        if (empty($residence)) {
            return $this->sendError('Residence not found');
        }

        return $this->sendResponse(new ResidenceResource($residence), 'Residence retrieved successfully');
    }

    /**
     * Update the specified Residence in storage.
     * PUT/PATCH /residences/{id}
     *
     * @param int $id
     * @param UpdateResidenceAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResidenceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Residence $residence */
        $residence = $this->residenceRepository->find($id);

        if (empty($residence)) {
            return $this->sendError('Residence not found');
        }

        $residence = $this->residenceRepository->update($input, $id);

        return $this->sendResponse(new ResidenceResource($residence), 'Residence updated successfully');
    }

    /**
     * Remove the specified Residence from storage.
     * DELETE /residences/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var Residence $residence */
        $residence = $this->residenceRepository->find($id);

        if (empty($residence)) {
            return $this->sendError('Residence not found');
        }

        $residence->delete();

        return $this->sendSuccess('Residence deleted successfully');
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
