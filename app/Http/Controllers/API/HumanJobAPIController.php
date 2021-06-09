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
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobs",
     *      summary="Get a listing of the Jobs.",
     *      tags={"Job"},
     *      description="Get all Jobs",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Job")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $jobs = $this->humanJobRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' =>HumanJobResource::collection($jobs)], 'Jobs retrieved successfully');
    }

    /**
     * @param CreateJobAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/jobs",
     *      summary="Store a newly created Job in storage",
     *      tags={"Job"},
     *      description="Store Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Job that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Job")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateJobAPIRequest $request)
    {
        $input = $request->validated();

        $job = $this->humanJobRepository->save_localized($input);

        return $this->sendResponse(new HumanJobResource($job), 'Job saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobs/{id}",
     *      summary="Display the specified Job",
     *      tags={"Job"},
     *      description="Get Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Job $job */
        $job = $this->humanJobRepository->find($id);

        if (empty($job)) {
            return $this->sendError('Job not found');
        }

        return $this->sendResponse(new HumanJobResource($job), 'Job retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateJobAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/jobs/{id}",
     *      summary="Update the specified Job in storage",
     *      tags={"Job"},
     *      description="Update Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Job that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Job")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/jobs/{id}",
     *      summary="Remove the specified Job from storage",
     *      tags={"Job"},
     *      description="Delete Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
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
