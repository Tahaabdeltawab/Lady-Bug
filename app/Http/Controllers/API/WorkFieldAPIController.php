<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWorkFieldAPIRequest;
use App\Http\Requests\API\UpdateWorkFieldAPIRequest;
use App\Models\WorkField;
use App\Repositories\WorkFieldRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WorkFieldResource;
use Response;

/**
 * Class WorkFieldController
 * @package App\Http\Controllers\API
 */

class WorkFieldAPIController extends AppBaseController
{
    /** @var  WorkFieldRepository */
    private $workFieldRepository;

    public function __construct(WorkFieldRepository $workFieldRepo)
    {
        $this->workFieldRepository = $workFieldRepo;
    }

    /**
     * Display a listing of the WorkField.
     * GET|HEAD /workFields
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $workFields = $this->workFieldRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => WorkFieldResource::collection($workFields['all']), 'meta' => $workFields['meta']], 'Work Fields retrieved successfully');
    }

    /**
     * Store a newly created WorkField in storage.
     * POST /workFields
     *
     * @param CreateWorkFieldAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkFieldAPIRequest $request)
    {
        $input = $request->validated();

        $workField = $this->workFieldRepository->create($input);

        return $this->sendResponse(new WorkFieldResource($workField), 'Work Field saved successfully');
    }

    /**
     * Display the specified WorkField.
     * GET|HEAD /workFields/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var WorkField $workField */
        $workField = $this->workFieldRepository->find($id);

        if (empty($workField)) {
            return $this->sendError('Work Field not found');
        }

        return $this->sendResponse(new WorkFieldResource($workField), 'Work Field retrieved successfully');
    }

    /**
     * Update the specified WorkField in storage.
     * PUT/PATCH /workFields/{id}
     *
     * @param int $id
     * @param UpdateWorkFieldAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkFieldAPIRequest $request)
    {
        $input = $request->validated();

        /** @var WorkField $workField */
        $workField = $this->workFieldRepository->find($id);

        if (empty($workField)) {
            return $this->sendError('Work Field not found');
        }

        $workField = $this->workFieldRepository->update($input, $id);

        return $this->sendResponse(new WorkFieldResource($workField), 'WorkField updated successfully');
    }

    /**
     * Remove the specified WorkField from storage.
     * DELETE /workFields/{id}
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
        /** @var WorkField $workField */
        $workField = $this->workFieldRepository->find($id);

        if (empty($workField)) {
            return $this->sendError('Work Field not found');
        }

        $workField->delete();

        return $this->sendSuccess('Work Field deleted successfully');
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
