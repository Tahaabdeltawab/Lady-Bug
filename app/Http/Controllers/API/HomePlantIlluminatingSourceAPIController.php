<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHomePlantIlluminatingSourceAPIRequest;
use App\Http\Requests\API\UpdateHomePlantIlluminatingSourceAPIRequest;
use App\Models\HomePlantIlluminatingSource;
use App\Repositories\HomePlantIlluminatingSourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HomePlantIlluminatingSourceResource;
use Response;

/**
 * Class HomePlantIlluminatingSourceController
 * @package App\Http\Controllers\API
 */

class HomePlantIlluminatingSourceAPIController extends AppBaseController
{
    /** @var  HomePlantIlluminatingSourceRepository */
    private $homePlantIlluminatingSourceRepository;

    public function __construct(HomePlantIlluminatingSourceRepository $homePlantIlluminatingSourceRepo)
    {
        $this->homePlantIlluminatingSourceRepository = $homePlantIlluminatingSourceRepo;

        $this->middleware('permission:home_plant_illuminating_sources.store')->only(['store']);
        $this->middleware('permission:home_plant_illuminating_sources.update')->only(['update']);
        $this->middleware('permission:home_plant_illuminating_sources.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $homePlantIlluminatingSources = $this->homePlantIlluminatingSourceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => HomePlantIlluminatingSourceResource::collection($homePlantIlluminatingSources)], 'Home Plant Illuminating Sources retrieved successfully');
    }

    public function store(CreateHomePlantIlluminatingSourceAPIRequest $request)
    {
        $input = $request->validated();

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->create($input);

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'Home Plant Illuminating Source saved successfully');
    }

    public function show($id)
    {
        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'Home Plant Illuminating Source retrieved successfully');
    }

    public function update($id, CreateHomePlantIlluminatingSourceAPIRequest $request)
    {
        $input = $request->validated();

        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->update($input, $id);

        return $this->sendResponse(new HomePlantIlluminatingSourceResource($homePlantIlluminatingSource), 'HomePlantIlluminatingSource updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var HomePlantIlluminatingSource $homePlantIlluminatingSource */
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            return $this->sendError('Home Plant Illuminating Source not found');
        }

        $homePlantIlluminatingSource->delete();

          return $this->sendSuccess('Model deleted successfully');
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
