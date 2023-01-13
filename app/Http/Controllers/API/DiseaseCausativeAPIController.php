<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDiseaseCausativeAPIRequest;
use App\Http\Requests\API\UpdateDiseaseCausativeAPIRequest;
use App\Models\DiseaseCausative;
use App\Repositories\DiseaseCausativeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DiseaseCausativeResource;
use Response;

/**
 * Class DiseaseCausativeController
 * @package App\Http\Controllers\API
 */

class DiseaseCausativeAPIController extends AppBaseController
{
    /** @var  DiseaseCausativeRepository */
    private $diseaseCausativeRepository;

    public function __construct(DiseaseCausativeRepository $diseaseCausativeRepo)
    {
        $this->diseaseCausativeRepository = $diseaseCausativeRepo;
    }

    /**
     * Display a listing of the DiseaseCausative.
     * GET|HEAD /diseaseCausatives
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $diseaseCausatives = $this->diseaseCausativeRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => DiseaseCausativeResource::collection($diseaseCausatives['all']), 'meta' => $diseaseCausatives['meta']], 'Disease Causatives retrieved successfully');
    }

    /**
     * Store a newly created DiseaseCausative in storage.
     * POST /diseaseCausatives
     *
     * @param CreateDiseaseCausativeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDiseaseCausativeAPIRequest $request)
    {
        $input = $request->validated();
        unset($input['disease_id']);
        $diseaseCausative = DiseaseCausative::updateOrCreate(['disease_id' => $request->disease_id], $input);
        return $this->sendResponse(new DiseaseCausativeResource($diseaseCausative), 'Disease Causative saved successfully');
    }

    /**
     * Display the specified DiseaseCausative.
     * GET|HEAD /diseaseCausatives/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DiseaseCausative $diseaseCausative */
        $diseaseCausative = $this->diseaseCausativeRepository->find($id);

        if (empty($diseaseCausative)) {
            return $this->sendError('Disease Causative not found');
        }

        return $this->sendResponse(new DiseaseCausativeResource($diseaseCausative), 'Disease Causative retrieved successfully');
    }

    /**
     * Update the specified DiseaseCausative in storage.
     * PUT/PATCH /diseaseCausatives/{id}
     *
     * @param int $id
     * @param UpdateDiseaseCausativeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDiseaseCausativeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var DiseaseCausative $diseaseCausative */
        $diseaseCausative = $this->diseaseCausativeRepository->find($id);

        if (empty($diseaseCausative)) {
            return $this->sendError('Disease Causative not found');
        }

        $diseaseCausative = $this->diseaseCausativeRepository->update($input, $id);

        return $this->sendResponse(new DiseaseCausativeResource($diseaseCausative), 'DiseaseCausative updated successfully');
    }

    /**
     * Remove the specified DiseaseCausative from storage.
     * DELETE /diseaseCausatives/{id}
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
        /** @var DiseaseCausative $diseaseCausative */
        $diseaseCausative = $this->diseaseCausativeRepository->find($id);

        if (empty($diseaseCausative)) {
            return $this->sendError('Disease Causative not found');
        }

        $diseaseCausative->delete();

        return $this->sendSuccess('Disease Causative deleted successfully');
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
