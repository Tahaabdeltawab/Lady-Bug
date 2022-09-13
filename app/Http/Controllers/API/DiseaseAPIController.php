<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseAPIRequest;
use App\Http\Requests\API\UpdateDiseaseAPIRequest;
use App\Models\Disease;
use App\Repositories\DiseaseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseResource;
use Response;

/**
 * Class DiseaseController
 * @package App\Http\Controllers\API
 */

class DiseaseAPIController extends AppBaseController
{
    /** @var  DiseaseRepository */
    private $diseaseRepository;

    public function __construct(DiseaseRepository $diseaseRepo)
    {
        $this->diseaseRepository = $diseaseRepo;
    }

    /**
     * Display a listing of the Disease.
     * GET|HEAD /diseases
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $diseases = $this->diseaseRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(DiseaseResource::collection($diseases), 'Diseases retrieved successfully');
    }

    /**
     * Store a newly created Disease in storage.
     * POST /diseases
     *
     * @param CreateDiseaseAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDiseaseAPIRequest $request)
    {
        $input = $request->all();

        $disease = $this->diseaseRepository->create($input);

        return $this->sendResponse(new DiseaseResource($disease), 'Disease saved successfully');
    }

    /**
     * Display the specified Disease.
     * GET|HEAD /diseases/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Disease $disease */
        $disease = $this->diseaseRepository->find($id);

        if (empty($disease)) {
            return $this->sendError('Disease not found');
        }

        return $this->sendResponse(new DiseaseResource($disease), 'Disease retrieved successfully');
    }

    /**
     * Update the specified Disease in storage.
     * PUT/PATCH /diseases/{id}
     *
     * @param int $id
     * @param UpdateDiseaseAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDiseaseAPIRequest $request)
    {
        $input = $request->all();

        /** @var Disease $disease */
        $disease = $this->diseaseRepository->find($id);

        if (empty($disease)) {
            return $this->sendError('Disease not found');
        }

        $disease = $this->diseaseRepository->update($input, $id);

        return $this->sendResponse(new DiseaseResource($disease), 'Disease updated successfully');
    }

    /**
     * Remove the specified Disease from storage.
     * DELETE /diseases/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Disease $disease */
        $disease = $this->diseaseRepository->find($id);

        if (empty($disease)) {
            return $this->sendError('Disease not found');
        }

        $disease->delete();

        return $this->sendSuccess('Disease deleted successfully');
    }
}
