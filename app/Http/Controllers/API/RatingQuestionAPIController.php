<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRatingQuestionAPIRequest;
use App\Http\Requests\API\UpdateRatingQuestionAPIRequest;
use App\Models\RatingQuestion;
use App\Repositories\RatingQuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RatingQuestionResource;
use Response;

/**
 * Class RatingQuestionController
 * @package App\Http\Controllers\API
 */

class RatingQuestionAPIController extends AppBaseController
{
    /** @var  RatingQuestionRepository */
    private $ratingQuestionRepository;

    public function __construct(RatingQuestionRepository $ratingQuestionRepo)
    {
        $this->ratingQuestionRepository = $ratingQuestionRepo;
    }

    /**
     * Display a listing of the RatingQuestion.
     * GET|HEAD /ratingQuestions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $ratingQuestions = RatingQuestion::get(['id', 'name']);
        return $this->sendResponse(RatingQuestionResource::collection($ratingQuestions), 'Rating Questions retrieved successfully');
    }

    /**
     * Store a newly created RatingQuestion in storage.
     * POST /ratingQuestions
     *
     * @param CreateRatingQuestionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRatingQuestionAPIRequest $request)
    {
        $input = $request->validated();

        $ratingQuestion = $this->ratingQuestionRepository->create($input);

        return $this->sendResponse(new RatingQuestionResource($ratingQuestion), 'Rating Question saved successfully');
    }

    /**
     * Display the specified RatingQuestion.
     * GET|HEAD /ratingQuestions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RatingQuestion $ratingQuestion */
        $ratingQuestion = $this->ratingQuestionRepository->find($id);

        if (empty($ratingQuestion)) {
            return $this->sendError('Rating Question not found');
        }

        return $this->sendResponse(new RatingQuestionResource($ratingQuestion), 'Rating Question retrieved successfully');
    }

    /**
     * Update the specified RatingQuestion in storage.
     * PUT/PATCH /ratingQuestions/{id}
     *
     * @param int $id
     * @param UpdateRatingQuestionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRatingQuestionAPIRequest $request)
    {
        $input = $request->validated();

        /** @var RatingQuestion $ratingQuestion */
        $ratingQuestion = $this->ratingQuestionRepository->find($id);

        if (empty($ratingQuestion)) {
            return $this->sendError('Rating Question not found');
        }

        $ratingQuestion = $this->ratingQuestionRepository->update($input, $id);

        return $this->sendResponse(new RatingQuestionResource($ratingQuestion), 'RatingQuestion updated successfully');
    }

    /**
     * Remove the specified RatingQuestion from storage.
     * DELETE /ratingQuestions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RatingQuestion $ratingQuestion */
        $ratingQuestion = $this->ratingQuestionRepository->find($id);

        if (empty($ratingQuestion)) {
            return $this->sendError('Rating Question not found');
        }

        $ratingQuestion->delete();

        return $this->sendSuccess('Rating Question deleted successfully');
    }
}
