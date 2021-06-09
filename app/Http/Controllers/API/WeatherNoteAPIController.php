<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWeatherNoteAPIRequest;
use App\Http\Requests\API\UpdateWeatherNoteAPIRequest;
use App\Models\WeatherNote;
use App\Repositories\WeatherNoteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WeatherNoteResource;
use Response;

/**
 * Class WeatherNoteController
 * @package App\Http\Controllers\API
 */

class WeatherNoteAPIController extends AppBaseController
{
    /** @var  WeatherNoteRepository */
    private $weatherNoteRepository;

    public function __construct(WeatherNoteRepository $weatherNoteRepo)
    {
        $this->weatherNoteRepository = $weatherNoteRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/weatherNotes",
     *      summary="Get a listing of the WeatherNotes.",
     *      tags={"WeatherNote"},
     *      description="Get all WeatherNotes",
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
     *                  @SWG\Items(ref="#/definitions/WeatherNote")
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
        $weatherNotes = $this->weatherNoteRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => WeatherNoteResource::collection($weatherNotes)], 'Weather Notes retrieved successfully');
    }

    /**
     * @param CreateWeatherNoteAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/weatherNotes",
     *      summary="Store a newly created WeatherNote in storage",
     *      tags={"WeatherNote"},
     *      description="Store WeatherNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WeatherNote that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WeatherNote")
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
     *                  ref="#/definitions/WeatherNote"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateWeatherNoteAPIRequest $request)
    {
        $input = $request->validated();

        $weatherNote = $this->weatherNoteRepository->save_localized($input);

        return $this->sendResponse(new WeatherNoteResource($weatherNote), 'Weather Note saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/weatherNotes/{id}",
     *      summary="Display the specified WeatherNote",
     *      tags={"WeatherNote"},
     *      description="Get WeatherNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WeatherNote",
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
     *                  ref="#/definitions/WeatherNote"
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
        /** @var WeatherNote $weatherNote */
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            return $this->sendError('Weather Note not found');
        }

        return $this->sendResponse(new WeatherNoteResource($weatherNote), 'Weather Note retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateWeatherNoteAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/weatherNotes/{id}",
     *      summary="Update the specified WeatherNote in storage",
     *      tags={"WeatherNote"},
     *      description="Update WeatherNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WeatherNote",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WeatherNote that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WeatherNote")
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
     *                  ref="#/definitions/WeatherNote"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateWeatherNoteAPIRequest $request)
    {
        $input = $request->validated();

        /** @var WeatherNote $weatherNote */
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            return $this->sendError('Weather Note not found');
        }

        $weatherNote = $this->weatherNoteRepository->save_localized($input, $id);

        return $this->sendResponse(new WeatherNoteResource($weatherNote), 'WeatherNote updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/weatherNotes/{id}",
     *      summary="Remove the specified WeatherNote from storage",
     *      tags={"WeatherNote"},
     *      description="Delete WeatherNote",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WeatherNote",
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
        /** @var WeatherNote $weatherNote */
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            return $this->sendError('Weather Note not found');
        }

        $weatherNote->delete();

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
