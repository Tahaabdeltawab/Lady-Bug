<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOfflineConsultancyPlanAPIRequest;
use App\Http\Requests\API\UpdateOfflineConsultancyPlanAPIRequest;
use App\Models\OfflineConsultancyPlan;
use App\Repositories\OfflineConsultancyPlanRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\OfflineConsultancyPlanResource;
use Response;

/**
 * Class OfflineConsultancyPlanController
 * @package App\Http\Controllers\API
 */

class OfflineConsultancyPlanAPIController extends AppBaseController
{
    /** @var  OfflineConsultancyPlanRepository */
    private $offlineConsultancyPlanRepository;

    public function __construct(OfflineConsultancyPlanRepository $offlineConsultancyPlanRepo)
    {
        $this->offlineConsultancyPlanRepository = $offlineConsultancyPlanRepo;
    }

    /**
     * Display a listing of the OfflineConsultancyPlan.
     * GET|HEAD /offlineConsultancyPlans
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $offlineConsultancyPlans = $this->offlineConsultancyPlanRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(OfflineConsultancyPlanResource::collection($offlineConsultancyPlans), 'Offline Consultancy Plans retrieved successfully');
    }

    /**
     * Store a newly created OfflineConsultancyPlan in storage.
     * POST /offlineConsultancyPlans
     *
     * @param CreateOfflineConsultancyPlanAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateOfflineConsultancyPlanAPIRequest $request)
    {
        $input = $request->all();

        $offlineConsultancyPlan = $this->offlineConsultancyPlanRepository->create($input);

        return $this->sendResponse(new OfflineConsultancyPlanResource($offlineConsultancyPlan), 'Offline Consultancy Plan saved successfully');
    }

    /**
     * Display the specified OfflineConsultancyPlan.
     * GET|HEAD /offlineConsultancyPlans/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var OfflineConsultancyPlan $offlineConsultancyPlan */
        $offlineConsultancyPlan = $this->offlineConsultancyPlanRepository->find($id);

        if (empty($offlineConsultancyPlan)) {
            return $this->sendError('Offline Consultancy Plan not found');
        }

        return $this->sendResponse(new OfflineConsultancyPlanResource($offlineConsultancyPlan), 'Offline Consultancy Plan retrieved successfully');
    }

    /**
     * Update the specified OfflineConsultancyPlan in storage.
     * PUT/PATCH /offlineConsultancyPlans/{id}
     *
     * @param int $id
     * @param UpdateOfflineConsultancyPlanAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOfflineConsultancyPlanAPIRequest $request)
    {
        $input = $request->all();

        /** @var OfflineConsultancyPlan $offlineConsultancyPlan */
        $offlineConsultancyPlan = $this->offlineConsultancyPlanRepository->find($id);

        if (empty($offlineConsultancyPlan)) {
            return $this->sendError('Offline Consultancy Plan not found');
        }

        $offlineConsultancyPlan = $this->offlineConsultancyPlanRepository->update($input, $id);

        return $this->sendResponse(new OfflineConsultancyPlanResource($offlineConsultancyPlan), 'OfflineConsultancyPlan updated successfully');
    }

    /**
     * Remove the specified OfflineConsultancyPlan from storage.
     * DELETE /offlineConsultancyPlans/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var OfflineConsultancyPlan $offlineConsultancyPlan */
        $offlineConsultancyPlan = $this->offlineConsultancyPlanRepository->find($id);

        if (empty($offlineConsultancyPlan)) {
            return $this->sendError('Offline Consultancy Plan not found');
        }

        $offlineConsultancyPlan->delete();

        return $this->sendSuccess('Offline Consultancy Plan deleted successfully');
    }
}
