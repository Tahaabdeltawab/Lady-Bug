<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessPartAPIRequest;
use App\Http\Requests\API\UpdateBusinessPartAPIRequest;
use App\Models\BusinessPart;
use App\Repositories\BusinessPartRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessPartResource;
use App\Models\Business;
use Response;

/**
 * Class BusinessPartController
 * @package App\Http\Controllers\API
 */

class BusinessPartAPIController extends AppBaseController
{
    /** @var  BusinessPartRepository */
    private $businessPartRepository;

    public function __construct(BusinessPartRepository $businessPartRepo)
    {
        $this->businessPartRepository = $businessPartRepo;
    }

    /**
     * Display a listing of the BusinessPart.
     * GET|HEAD /businessParts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $businessParts = $this->businessPartRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BusinessPartResource::collection($businessParts), 'Business Parts retrieved successfully');
    }


    public function business_parts_by_business_id(Request $request)
    {
        $businessParts = BusinessPart::where('type', $request->type)->where('business_id', $request->business)->get();

        return $this->sendResponse(BusinessPartResource::collection($businessParts), 'Business Parts retrieved successfully');
    }

    public function toggle_finish($id)
    {
        $businessPart = BusinessPart::find($id);
        if (empty($businessPart))
            return $this->sendError('Business part not found');

        $msg = $businessPart->done ? 'Business part became not completed' : 'Business part has been completed';
        $businessPart->done = !$businessPart->done;
        $businessPart->save();

        return $this->sendSuccess($msg);
    }

    /**
     * Store a newly created BusinessPart in storage.
     * POST /businessParts
     *
     * @param CreateBusinessPartAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessPartAPIRequest $request)
    {
        $business = Business::find($request->business_id);
        if(!auth()->user()->hasPermission("create-$request->type", $business))
            return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

        $input = $request->validated();

        $businessPart = $this->businessPartRepository->create($input);

        return $this->sendResponse(new BusinessPartResource($businessPart), 'Business Part saved successfully');
    }

    /**
     * Display the specified BusinessPart.
     * GET|HEAD /businessParts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BusinessPart $businessPart */
        $businessPart = $this->businessPartRepository->find($id);

        if (empty($businessPart)) {
            return $this->sendError('Business Part not found');
        }

        return $this->sendResponse(new BusinessPartResource($businessPart), 'Business Part retrieved successfully');
    }

    /**
     * Update the specified BusinessPart in storage.
     * PUT/PATCH /businessParts/{id}
     *
     * @param int $id
     * @param UpdateBusinessPartAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessPartAPIRequest $request)
    {
        $input = $request->validated();

        /** @var BusinessPart $businessPart */
        $businessPart = $this->businessPartRepository->find($id);

        if (empty($businessPart))
            return $this->sendError('Business Part not found');

        $business = Business::find($businessPart->business_id);
        if(!auth()->user()->hasPermission("edit-$request->type", $business))
            return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));

        $businessPart = $this->businessPartRepository->update($input, $id);

        return $this->sendResponse(new BusinessPartResource($businessPart), 'BusinessPart updated successfully');
    }

    /**
     * Remove the specified BusinessPart from storage.
     * DELETE /businessParts/{id}
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
        /** @var BusinessPart $businessPart */
        $businessPart = $this->businessPartRepository->find($id);

        if (empty($businessPart)) {
            return $this->sendError('Business Part not found');
        }

        $businessPart->delete();

        return $this->sendSuccess('Business Part deleted successfully');
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
