<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseAPIRequest;
use App\Http\Requests\API\UpdateDiseaseAPIRequest;
use App\Models\Disease;
use App\Repositories\DiseaseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseLgResource;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\DiseaseSmResource;
use App\Http\Resources\DiseaseXsResource;
use App\Http\Resources\PathogenResource;
use App\Models\Country;
use App\Models\Pathogen;
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

        $this->middleware('permission:diseases.index')->only(['admin_index']);
        $this->middleware('permission:diseases.show')->only(['admin_show']);
        $this->middleware('permission:diseases.store')->only(['store']);
        $this->middleware('permission:diseases.update')->only(['update']);
        $this->middleware('permission:diseases.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the Disease.
     * GET|HEAD /diseases
     *
     * @param Request $request
     * @return Response
     */
    public function admin_index(Request $request)
    {
        return $this->index($request);
    }

    public function admin_show($id)
    {
        return $this->show($id);
    }

    public function index(Request $request)
    {
        $diseases = $this->diseaseRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => DiseaseSmResource::collection($diseases['all']), 'meta' => $diseases['meta']], 'Diseases retrieved successfully');
    }

    public function getRelations()
    {
        return $this->sendResponse([
            'countries' => Country::all(),
            'pathogens' => PathogenResource::collection(Pathogen::all()),
        ], 'relations retieved successfully');
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
        $input = $request->validated();

        $disease = $this->diseaseRepository->create($input);
        $disease->countries()->attach($request->countries);
        $disease->pathogens()->attach($request->pathogens);

        return $this->sendResponse(new DiseaseLgResource($disease), 'Disease saved successfully');
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

        return $this->sendResponse(new DiseaseLgResource($disease), 'Disease retrieved successfully');
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
        $input = $request->validated();

        /** @var Disease $disease */
        $disease = $this->diseaseRepository->find($id);

        if (empty($disease))
            return $this->sendError('Disease not found');

        $disease = $this->diseaseRepository->update($input, $id);
        $disease->countries()->sync($request->countries);
        $disease->pathogens()->sync($request->pathogens);

        return $this->sendResponse(new DiseaseLgResource($disease), 'Disease updated successfully');
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
        try
        {
        /** @var Disease $disease */
        $disease = $this->diseaseRepository->find($id);

        if (empty($disease)) {
            return $this->sendError('Disease not found');
        }

        $disease->delete();

        return $this->sendSuccess('Disease deleted successfully');
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
