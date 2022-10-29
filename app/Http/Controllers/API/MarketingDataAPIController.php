<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMarketingDataAPIRequest;
use App\Http\Requests\API\UpdateMarketingDataAPIRequest;
use App\Models\MarketingData;
use App\Repositories\MarketingDataRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MarketingDataResource;
use Response;

/**
 * Class MarketingDataController
 * @package App\Http\Controllers\API
 */

class MarketingDataAPIController extends AppBaseController
{
    /** @var  MarketingDataRepository */
    private $marketingDataRepository;

    public function __construct(MarketingDataRepository $marketingDataRepo)
    {
        $this->marketingDataRepository = $marketingDataRepo;
    }

    /**
     * Display a listing of the MarketingData.
     * GET|HEAD /marketingDatas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $marketingDatas = $this->marketingDataRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(MarketingDataResource::collection($marketingDatas), 'Marketing Datas retrieved successfully');
    }

    /**
     * Store a newly created MarketingData in storage.
     * POST /marketingDatas
     *
     * @param CreateMarketingDataAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMarketingDataAPIRequest $request)
    {
        $input = $request->validated();
        $marketingData = MarketingData::updateOrCreate(['farmed_type_id' => $request->farmed_type_id], $input);

        return $this->sendResponse(new MarketingDataResource($marketingData), 'Marketing Data saved successfully');
    }

    /**
     * Display the specified MarketingData.
     * GET|HEAD /marketingDatas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MarketingData $marketingData */
        $marketingData = $this->marketingDataRepository->find($id);

        if (empty($marketingData)) {
            return $this->sendError('Marketing Data not found');
        }

        return $this->sendResponse(new MarketingDataResource($marketingData), 'Marketing Data retrieved successfully');
    }

    public function by_ft_id($id)
    {
        $marketingData = MarketingData::where('farmed_type_id', $id)->first();

        if (empty($marketingData)) {
            return $this->sendError('Marketing Data not found');
        }

        return $this->sendResponse(new MarketingDataResource($marketingData), 'Marketing Data retrieved successfully');
    }

    /**
     * Update the specified MarketingData in storage.
     * PUT/PATCH /marketingDatas/{id}
     *
     * @param int $id
     * @param UpdateMarketingDataAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMarketingDataAPIRequest $request)
    {
        $input = $request->validated();

        /** @var MarketingData $marketingData */
        $marketingData = $this->marketingDataRepository->find($id);

        if (empty($marketingData)) {
            return $this->sendError('Marketing Data not found');
        }

        $marketingData = $this->marketingDataRepository->update($input, $id);

        return $this->sendResponse(new MarketingDataResource($marketingData), 'MarketingData updated successfully');
    }

    /**
     * Remove the specified MarketingData from storage.
     * DELETE /marketingDatas/{id}
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
        /** @var MarketingData $marketingData */
        $marketingData = $this->marketingDataRepository->find($id);

        if (empty($marketingData)) {
            return $this->sendError('Marketing Data not found');
        }

        $marketingData->delete();

        return $this->sendSuccess('Marketing Data deleted successfully');
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
