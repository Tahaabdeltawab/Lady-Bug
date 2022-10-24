<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessBranchAPIRequest;
use App\Http\Requests\API\UpdateBusinessBranchAPIRequest;
use App\Models\BusinessBranch;
use App\Repositories\BusinessBranchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessBranchResource;
use Response;

/**
 * Class BusinessBranchController
 * @package App\Http\Controllers\API
 */

class BusinessBranchAPIController extends AppBaseController
{
    /** @var  BusinessBranchRepository */
    private $businessBranchRepository;

    public function __construct(BusinessBranchRepository $businessBranchRepo)
    {
        $this->businessBranchRepository = $businessBranchRepo;
    }

    /**
     * Display a listing of the BusinessBranch.
     * GET|HEAD /businessBranches
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $businessBranches = $this->businessBranchRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BusinessBranchResource::collection($businessBranches), 'Business Branches retrieved successfully');
    }

    /**
     * Store a newly created BusinessBranch in storage.
     * POST /businessBranches
     *
     * @param CreateBusinessBranchAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessBranchAPIRequest $request)
    {
        $input = $request->validated();

        $businessBranch = $this->businessBranchRepository->create($input);

        return $this->sendResponse(new BusinessBranchResource($businessBranch), 'Business Branch saved successfully');
    }

    /**
     * Display the specified BusinessBranch.
     * GET|HEAD /businessBranches/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BusinessBranch $businessBranch */
        $businessBranch = $this->businessBranchRepository->find($id);

        if (empty($businessBranch)) {
            return $this->sendError('Business Branch not found');
        }

        return $this->sendResponse(new BusinessBranchResource($businessBranch), 'Business Branch retrieved successfully');
    }

    /**
     * Update the specified BusinessBranch in storage.
     * PUT/PATCH /businessBranches/{id}
     *
     * @param int $id
     * @param UpdateBusinessBranchAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessBranchAPIRequest $request)
    {
        $input = $request->validated();

        /** @var BusinessBranch $businessBranch */
        $businessBranch = $this->businessBranchRepository->find($id);

        if (empty($businessBranch)) {
            return $this->sendError('Business Branch not found');
        }

        $businessBranch = $this->businessBranchRepository->update($input, $id);

        return $this->sendResponse(new BusinessBranchResource($businessBranch), 'BusinessBranch updated successfully');
    }

    /**
     * Remove the specified BusinessBranch from storage.
     * DELETE /businessBranches/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BusinessBranch $businessBranch */
        $businessBranch = $this->businessBranchRepository->find($id);

        if (empty($businessBranch)) {
            return $this->sendError('Business Branch not found');
        }

        $businessBranch->delete();

        return $this->sendSuccess('Business Branch deleted successfully');
    }
}
