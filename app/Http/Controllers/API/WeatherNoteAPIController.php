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

        $this->middleware('permission:weather_notes.store')->only(['store']);
        $this->middleware('permission:weather_notes.update')->only(['update']);
        $this->middleware('permission:weather_notes.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $weatherNotes = $this->weatherNoteRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => WeatherNoteResource::collection($weatherNotes)], 'Weather Notes retrieved successfully');
    }

    public function store(CreateWeatherNoteAPIRequest $request)
    {
        $input = $request->validated();

        $weatherNote = $this->weatherNoteRepository->save_localized($input);

        return $this->sendResponse(new WeatherNoteResource($weatherNote), 'Weather Note saved successfully');
    }

    public function show($id)
    {
        /** @var WeatherNote $weatherNote */
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            return $this->sendError('Weather Note not found');
        }

        return $this->sendResponse(new WeatherNoteResource($weatherNote), 'Weather Note retrieved successfully');
    }

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
