<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnimalFodderSourceRequest;
use App\Http\Requests\UpdateAnimalFodderSourceRequest;
use App\Repositories\AnimalFodderSourceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AnimalFodderSourceController extends AppBaseController
{
    /** @var  AnimalFodderSourceRepository */
    private $animalFodderSourceRepository;

    public function __construct(AnimalFodderSourceRepository $animalFodderSourceRepo)
    {
        $this->animalFodderSourceRepository = $animalFodderSourceRepo;
    }

    /**
     * Display a listing of the AnimalFodderSource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $animalFodderSources = $this->animalFodderSourceRepository->all();

        return view('animal_fodder_sources.index')
            ->with('animalFodderSources', $animalFodderSources);
    }

    /**
     * Show the form for creating a new AnimalFodderSource.
     *
     * @return Response
     */
    public function create()
    {
        return view('animal_fodder_sources.create');
    }

    /**
     * Store a newly created AnimalFodderSource in storage.
     *
     * @param CreateAnimalFodderSourceRequest $request
     *
     * @return Response
     */
    public function store(CreateAnimalFodderSourceRequest $request)
    {
        $input = $request->all();

        $animalFodderSource = $this->animalFodderSourceRepository->create($input);

        Flash::success('Animal Fodder Source saved successfully.');

        return redirect(route('animalFodderSources.index'));
    }

    /**
     * Display the specified AnimalFodderSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            Flash::error('Animal Fodder Source not found');

            return redirect(route('animalFodderSources.index'));
        }

        return view('animal_fodder_sources.show')->with('animalFodderSource', $animalFodderSource);
    }

    /**
     * Show the form for editing the specified AnimalFodderSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            Flash::error('Animal Fodder Source not found');

            return redirect(route('animalFodderSources.index'));
        }

        return view('animal_fodder_sources.edit')->with('animalFodderSource', $animalFodderSource);
    }

    /**
     * Update the specified AnimalFodderSource in storage.
     *
     * @param int $id
     * @param UpdateAnimalFodderSourceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAnimalFodderSourceRequest $request)
    {
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            Flash::error('Animal Fodder Source not found');

            return redirect(route('animalFodderSources.index'));
        }

        $animalFodderSource = $this->animalFodderSourceRepository->update($request->all(), $id);

        Flash::success('Animal Fodder Source updated successfully.');

        return redirect(route('animalFodderSources.index'));
    }

    /**
     * Remove the specified AnimalFodderSource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $animalFodderSource = $this->animalFodderSourceRepository->find($id);

        if (empty($animalFodderSource)) {
            Flash::error('Animal Fodder Source not found');

            return redirect(route('animalFodderSources.index'));
        }

        $this->animalFodderSourceRepository->delete($id);

        Flash::success('Animal Fodder Source deleted successfully.');

        return redirect(route('animalFodderSources.index'));
    }
}
