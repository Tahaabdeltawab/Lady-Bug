<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnimalBreedingPurposeRequest;
use App\Http\Requests\UpdateAnimalBreedingPurposeRequest;
use App\Repositories\AnimalBreedingPurposeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AnimalBreedingPurposeController extends AppBaseController
{
    /** @var  AnimalBreedingPurposeRepository */
    private $animalBreedingPurposeRepository;

    public function __construct(AnimalBreedingPurposeRepository $animalBreedingPurposeRepo)
    {
        $this->animalBreedingPurposeRepository = $animalBreedingPurposeRepo;
    }

    /**
     * Display a listing of the AnimalBreedingPurpose.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $animalBreedingPurposes = $this->animalBreedingPurposeRepository->all();

        return view('animal_breeding_purposes.index')
            ->with('animalBreedingPurposes', $animalBreedingPurposes);
    }

    /**
     * Show the form for creating a new AnimalBreedingPurpose.
     *
     * @return Response
     */
    public function create()
    {
        return view('animal_breeding_purposes.create');
    }

    /**
     * Store a newly created AnimalBreedingPurpose in storage.
     *
     * @param CreateAnimalBreedingPurposeRequest $request
     *
     * @return Response
     */
    public function store(CreateAnimalBreedingPurposeRequest $request)
    {
        $input = $request->all();

        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->create($input);

        Flash::success('Animal Breeding Purpose saved successfully.');

        return redirect(route('animalBreedingPurposes.index'));
    }

    /**
     * Display the specified AnimalBreedingPurpose.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            Flash::error('Animal Breeding Purpose not found');

            return redirect(route('animalBreedingPurposes.index'));
        }

        return view('animal_breeding_purposes.show')->with('animalBreedingPurpose', $animalBreedingPurpose);
    }

    /**
     * Show the form for editing the specified AnimalBreedingPurpose.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            Flash::error('Animal Breeding Purpose not found');

            return redirect(route('animalBreedingPurposes.index'));
        }

        return view('animal_breeding_purposes.edit')->with('animalBreedingPurpose', $animalBreedingPurpose);
    }

    /**
     * Update the specified AnimalBreedingPurpose in storage.
     *
     * @param int $id
     * @param UpdateAnimalBreedingPurposeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAnimalBreedingPurposeRequest $request)
    {
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            Flash::error('Animal Breeding Purpose not found');

            return redirect(route('animalBreedingPurposes.index'));
        }

        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->update($request->all(), $id);

        Flash::success('Animal Breeding Purpose updated successfully.');

        return redirect(route('animalBreedingPurposes.index'));
    }

    /**
     * Remove the specified AnimalBreedingPurpose from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $animalBreedingPurpose = $this->animalBreedingPurposeRepository->find($id);

        if (empty($animalBreedingPurpose)) {
            Flash::error('Animal Breeding Purpose not found');

            return redirect(route('animalBreedingPurposes.index'));
        }

        $this->animalBreedingPurposeRepository->delete($id);

        Flash::success('Animal Breeding Purpose deleted successfully.');

        return redirect(route('animalBreedingPurposes.index'));
    }
}
