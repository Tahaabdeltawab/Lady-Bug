<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWeatherNoteRequest;
use App\Http\Requests\UpdateWeatherNoteRequest;
use App\Repositories\WeatherNoteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WeatherNoteController extends AppBaseController
{
    /** @var  WeatherNoteRepository */
    private $weatherNoteRepository;

    public function __construct(WeatherNoteRepository $weatherNoteRepo)
    {
        $this->weatherNoteRepository = $weatherNoteRepo;
    }

    /**
     * Display a listing of the WeatherNote.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $weatherNotes = $this->weatherNoteRepository->all();

        return view('weather_notes.index')
            ->with('weatherNotes', $weatherNotes);
    }

    /**
     * Show the form for creating a new WeatherNote.
     *
     * @return Response
     */
    public function create()
    {
        return view('weather_notes.create');
    }

    /**
     * Store a newly created WeatherNote in storage.
     *
     * @param CreateWeatherNoteRequest $request
     *
     * @return Response
     */
    public function store(CreateWeatherNoteRequest $request)
    {
        $input = $request->all();

        $weatherNote = $this->weatherNoteRepository->create($input);

        Flash::success('Weather Note saved successfully.');

        return redirect(route('weatherNotes.index'));
    }

    /**
     * Display the specified WeatherNote.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            Flash::error('Weather Note not found');

            return redirect(route('weatherNotes.index'));
        }

        return view('weather_notes.show')->with('weatherNote', $weatherNote);
    }

    /**
     * Show the form for editing the specified WeatherNote.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            Flash::error('Weather Note not found');

            return redirect(route('weatherNotes.index'));
        }

        return view('weather_notes.edit')->with('weatherNote', $weatherNote);
    }

    /**
     * Update the specified WeatherNote in storage.
     *
     * @param int $id
     * @param UpdateWeatherNoteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWeatherNoteRequest $request)
    {
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            Flash::error('Weather Note not found');

            return redirect(route('weatherNotes.index'));
        }

        $weatherNote = $this->weatherNoteRepository->update($request->all(), $id);

        Flash::success('Weather Note updated successfully.');

        return redirect(route('weatherNotes.index'));
    }

    /**
     * Remove the specified WeatherNote from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $weatherNote = $this->weatherNoteRepository->find($id);

        if (empty($weatherNote)) {
            Flash::error('Weather Note not found');

            return redirect(route('weatherNotes.index'));
        }

        $this->weatherNoteRepository->delete($id);

        Flash::success('Weather Note deleted successfully.');

        return redirect(route('weatherNotes.index'));
    }
}
