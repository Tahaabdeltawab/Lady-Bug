<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHomePlantIlluminatingSourceRequest;
use App\Http\Requests\UpdateHomePlantIlluminatingSourceRequest;
use App\Repositories\HomePlantIlluminatingSourceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class HomePlantIlluminatingSourceController extends AppBaseController
{
    /** @var  HomePlantIlluminatingSourceRepository */
    private $homePlantIlluminatingSourceRepository;

    public function __construct(HomePlantIlluminatingSourceRepository $homePlantIlluminatingSourceRepo)
    {
        $this->homePlantIlluminatingSourceRepository = $homePlantIlluminatingSourceRepo;
    }

    /**
     * Display a listing of the HomePlantIlluminatingSource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $homePlantIlluminatingSources = $this->homePlantIlluminatingSourceRepository->all();

        return view('home_plant_illuminating_sources.index')
            ->with('homePlantIlluminatingSources', $homePlantIlluminatingSources);
    }

    /**
     * Show the form for creating a new HomePlantIlluminatingSource.
     *
     * @return Response
     */
    public function create()
    {
        return view('home_plant_illuminating_sources.create');
    }

    /**
     * Store a newly created HomePlantIlluminatingSource in storage.
     *
     * @param CreateHomePlantIlluminatingSourceRequest $request
     *
     * @return Response
     */
    public function store(CreateHomePlantIlluminatingSourceRequest $request)
    {
        $input = $request->all();

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->create($input);

        Flash::success('Home Plant Illuminating Source saved successfully.');

        return redirect(route('homePlantIlluminatingSources.index'));
    }

    /**
     * Display the specified HomePlantIlluminatingSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            Flash::error('Home Plant Illuminating Source not found');

            return redirect(route('homePlantIlluminatingSources.index'));
        }

        return view('home_plant_illuminating_sources.show')->with('homePlantIlluminatingSource', $homePlantIlluminatingSource);
    }

    /**
     * Show the form for editing the specified HomePlantIlluminatingSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            Flash::error('Home Plant Illuminating Source not found');

            return redirect(route('homePlantIlluminatingSources.index'));
        }

        return view('home_plant_illuminating_sources.edit')->with('homePlantIlluminatingSource', $homePlantIlluminatingSource);
    }

    /**
     * Update the specified HomePlantIlluminatingSource in storage.
     *
     * @param int $id
     * @param UpdateHomePlantIlluminatingSourceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHomePlantIlluminatingSourceRequest $request)
    {
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            Flash::error('Home Plant Illuminating Source not found');

            return redirect(route('homePlantIlluminatingSources.index'));
        }

        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->update($request->all(), $id);

        Flash::success('Home Plant Illuminating Source updated successfully.');

        return redirect(route('homePlantIlluminatingSources.index'));
    }

    /**
     * Remove the specified HomePlantIlluminatingSource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $homePlantIlluminatingSource = $this->homePlantIlluminatingSourceRepository->find($id);

        if (empty($homePlantIlluminatingSource)) {
            Flash::error('Home Plant Illuminating Source not found');

            return redirect(route('homePlantIlluminatingSources.index'));
        }

        $this->homePlantIlluminatingSourceRepository->delete($id);

        Flash::success('Home Plant Illuminating Source deleted successfully.');

        return redirect(route('homePlantIlluminatingSources.index'));
    }
}
