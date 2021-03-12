<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnimalFodderTypeRequest;
use App\Http\Requests\UpdateAnimalFodderTypeRequest;
use App\Repositories\AnimalFodderTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AnimalFodderTypeController extends AppBaseController
{
    /** @var  AnimalFodderTypeRepository */
    private $animalFodderTypeRepository;

    public function __construct(AnimalFodderTypeRepository $animalFodderTypeRepo)
    {
        $this->animalFodderTypeRepository = $animalFodderTypeRepo;
    }

    /**
     * Display a listing of the AnimalFodderType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $animalFodderTypes = $this->animalFodderTypeRepository->all();

        return view('animal_fodder_types.index')
            ->with('animalFodderTypes', $animalFodderTypes);
    }

    /**
     * Show the form for creating a new AnimalFodderType.
     *
     * @return Response
     */
    public function create()
    {
        return view('animal_fodder_types.create');
    }

    /**
     * Store a newly created AnimalFodderType in storage.
     *
     * @param CreateAnimalFodderTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateAnimalFodderTypeRequest $request)
    {
        $input = $request->all();

        $animalFodderType = $this->animalFodderTypeRepository->create($input);

        Flash::success('Animal Fodder Type saved successfully.');

        return redirect(route('animalFodderTypes.index'));
    }

    /**
     * Display the specified AnimalFodderType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            Flash::error('Animal Fodder Type not found');

            return redirect(route('animalFodderTypes.index'));
        }

        return view('animal_fodder_types.show')->with('animalFodderType', $animalFodderType);
    }

    /**
     * Show the form for editing the specified AnimalFodderType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            Flash::error('Animal Fodder Type not found');

            return redirect(route('animalFodderTypes.index'));
        }

        return view('animal_fodder_types.edit')->with('animalFodderType', $animalFodderType);
    }

    /**
     * Update the specified AnimalFodderType in storage.
     *
     * @param int $id
     * @param UpdateAnimalFodderTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAnimalFodderTypeRequest $request)
    {
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            Flash::error('Animal Fodder Type not found');

            return redirect(route('animalFodderTypes.index'));
        }

        $animalFodderType = $this->animalFodderTypeRepository->update($request->all(), $id);

        Flash::success('Animal Fodder Type updated successfully.');

        return redirect(route('animalFodderTypes.index'));
    }

    /**
     * Remove the specified AnimalFodderType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $animalFodderType = $this->animalFodderTypeRepository->find($id);

        if (empty($animalFodderType)) {
            Flash::error('Animal Fodder Type not found');

            return redirect(route('animalFodderTypes.index'));
        }

        $this->animalFodderTypeRepository->delete($id);

        Flash::success('Animal Fodder Type deleted successfully.');

        return redirect(route('animalFodderTypes.index'));
    }
}
