<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessFieldAPIRequest;
use App\Http\Requests\API\UpdateBusinessFieldAPIRequest;
use App\Models\BusinessField;
use App\Repositories\BusinessFieldRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessFieldResource;
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
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BusinessFieldResource::collection($businessFields), 'Business Fields retrieved successfully');
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
        /** @var BusinessField $businessField */
        $businessField = $this->businessFieldRepository->find($id);

        if (empty($businessField)) {
            return $this->sendError('Business Field not found');
        }

        $businessField->delete();

        return $this->sendSuccess('Business Field deleted successfully');
    }
}
