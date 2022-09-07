<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateJobAPIRequest;
use App\Http\Requests\API\UpdateJobAPIRequest;
use App\Models\HumanJob;
use App\Repositories\HumanJobRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HumanJobResource;
use Response;


class HumanJobAPIController extends AppBaseController
{
    /** @var  HumanJobRepository */
    private $humanJobRepository;

    public function __construct(HumanJobRepository $humanJobRepo)
    {
        $this->humanJobRepository = $humanJobRepo;

        $this->middleware('permission:human_jobs.store')->only(['store']);
        $this->middleware('permission:human_jobs.update')->only(['update']);
        $this->middleware('permission:human_jobs.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $jobs = $this->humanJobRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' =>HumanJobResource::collection($jobs)], 'Jobs retrieved successfully');
    }

    public function store(CreateJobAPIRequest $request)
    {
        $input = $request->validated();

        $job = $this->humanJobRepository->save_localized($input);
        return $this->sendResponse(new HumanJobResource($job), 'Job saved successfully');
    }

    public function show($id)
    {
        /** @var Job $job */
        $job = $this->humanJobRepository->find($id);

        if (empty($job)) {
            return $this->sendError('Job not found');
        }

        return $this->sendResponse(new HumanJobResource($job), 'Job retrieved successfully');
    }

    public function update($id, CreateJobAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Job $job */
        $job = $this->humanJobRepository->find($id);

        if (empty($job)) {
            return $this->sendError('Job not found');
        }

        $job = $this->humanJobRepository->save_localized($input, $id);

        return $this->sendResponse(new HumanJobResource($job), 'Job updated successfully');
    }

    public function destroy($id)
    {
        try
        {
        /** @var Job $job */
        $job = $this->humanJobRepository->find($id);

        if (empty($job)) {
            return $this->sendError('Job not found');
        }

        $job->delete();

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
