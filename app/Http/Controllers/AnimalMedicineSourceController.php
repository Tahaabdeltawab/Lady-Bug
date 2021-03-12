<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnimalMedicineSourceRequest;
use App\Http\Requests\UpdateAnimalMedicineSourceRequest;
use App\Repositories\AnimalMedicineSourceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AnimalMedicineSourceController extends AppBaseController
{
    /** @var  AnimalMedicineSourceRepository */
    private $animalMedicineSourceRepository;

    public function __construct(AnimalMedicineSourceRepository $animalMedicineSourceRepo)
    {
        $this->animalMedicineSourceRepository = $animalMedicineSourceRepo;
    }

    /**
     * Display a listing of the AnimalMedicineSource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $animalMedicineSources = $this->animalMedicineSourceRepository->all();

        return view('animal_medicine_sources.index')
            ->with('animalMedicineSources', $animalMedicineSources);
    }

    /**
     * Show the form for creating a new AnimalMedicineSource.
     *
     * @return Response
     */
    public function create()
    {
        return view('animal_medicine_sources.create');
    }

    /**
     * Store a newly created AnimalMedicineSource in storage.
     *
     * @param CreateAnimalMedicineSourceRequest $request
     *
     * @return Response
     */
    public function store(CreateAnimalMedicineSourceRequest $request)
    {
        $input = $request->all();

        $animalMedicineSource = $this->animalMedicineSourceRepository->create($input);

        Flash::success('Animal Medicine Source saved successfully.');

        return redirect(route('animalMedicineSources.index'));
    }

    /**
     * Display the specified AnimalMedicineSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            Flash::error('Animal Medicine Source not found');

            return redirect(route('animalMedicineSources.index'));
        }

        return view('animal_medicine_sources.show')->with('animalMedicineSource', $animalMedicineSource);
    }

    /**
     * Show the form for editing the specified AnimalMedicineSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            Flash::error('Animal Medicine Source not found');

            return redirect(route('animalMedicineSources.index'));
        }

        return view('animal_medicine_sources.edit')->with('animalMedicineSource', $animalMedicineSource);
    }

    /**
     * Update the specified AnimalMedicineSource in storage.
     *
     * @param int $id
     * @param UpdateAnimalMedicineSourceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAnimalMedicineSourceRequest $request)
    {
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            Flash::error('Animal Medicine Source not found');

            return redirect(route('animalMedicineSources.index'));
        }

        $animalMedicineSource = $this->animalMedicineSourceRepository->update($request->all(), $id);

        Flash::success('Animal Medicine Source updated successfully.');

        return redirect(route('animalMedicineSources.index'));
    }

    /**
     * Remove the specified AnimalMedicineSource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $animalMedicineSource = $this->animalMedicineSourceRepository->find($id);

        if (empty($animalMedicineSource)) {
            Flash::error('Animal Medicine Source not found');

            return redirect(route('animalMedicineSources.index'));
        }

        $this->animalMedicineSourceRepository->delete($id);

        Flash::success('Animal Medicine Source deleted successfully.');

        return redirect(route('animalMedicineSources.index'));
    }
}
