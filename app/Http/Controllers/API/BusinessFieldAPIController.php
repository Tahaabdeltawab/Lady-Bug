<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessFieldAPIRequest;
use App\Http\Requests\API\UpdateBusinessFieldAPIRequest;
use App\Models\BusinessField;
use App\Repositories\BusinessFieldRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessFieldResource;
use App\Models\Business;
use Response;

/**
 * Class BusinessFieldController
 * @package App\Http\Controllers\API
 */

class BusinessFieldAPIController extends AppBaseController
{
    /** @var  BusinessFieldRepository */
    private $businessFieldRepository;

    public function __construct(BusinessFieldRepository $businessFieldRepo)
    {
        $this->businessFieldRepository = $businessFieldRepo;
    }

    /**
     * Display a listing of the BusinessField.
     * GET|HEAD /businessFields
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $businessFields = $this->businessFieldRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page'),
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => BusinessFieldResource::collection($businessFields['all']), 'meta' => $businessFields['meta']], 'Business Fields retrieved successfully');
    }

    /**
     * Store a newly created BusinessField in storage.
     * POST /businessFields
     *
     * @param CreateBusinessFieldAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessFieldAPIRequest $request)
    {
        $input = $request->validated();

        $businessField = $this->businessFieldRepository->create($input);

        return $this->sendResponse(new BusinessFieldResource($businessField), 'Business Field saved successfully');
    }

    /**
     * Display the specified BusinessField.
     * GET|HEAD /businessFields/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BusinessField $businessField */
        $businessField = $this->businessFieldRepository->find($id);

        if (empty($businessField)) {
            return $this->sendError('Business Field not found');
        }

        return $this->sendResponse(new BusinessFieldResource($businessField), 'Business Field retrieved successfully');
    }

    /**
     * Update the specified BusinessField in storage.
     * PUT/PATCH /businessFields/{id}
     *
     * @param int $id
     * @param UpdateBusinessFieldAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessFieldAPIRequest $request)
    {
        if(in_array($id, Business::$used_business_fields))
            return $this->sendError('Used Business Fields are not editable');

        $input = $request->validated();

        /** @var BusinessField $businessField */
        $businessField = $this->businessFieldRepository->find($id);

        if (empty($businessField)) {
            return $this->sendError('Business Field not found');
        }

        $businessField = $this->businessFieldRepository->update($input, $id);

        return $this->sendResponse(new BusinessFieldResource($businessField), 'BusinessField updated successfully');
    }

    /**
     * Remove the specified BusinessField from storage.
     * DELETE /businessFields/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        if(in_array($id, Business::$used_business_fields))
            return $this->sendError('Used Business Fields are not deletable');

        try
        {
        /** @var BusinessField $businessField */
        $businessField = $this->businessFieldRepository->find($id);

        if (empty($businessField)) {
            return $this->sendError('Business Field not found');
        }

        $businessField->delete();

        return $this->sendSuccess('Business Field deleted successfully');
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
