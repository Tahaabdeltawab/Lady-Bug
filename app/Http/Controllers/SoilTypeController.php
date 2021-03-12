<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSoilTypeRequest;
use App\Http\Requests\UpdateSoilTypeRequest;
use App\Repositories\SoilTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SoilTypeController extends AppBaseController
{
    /** @var  SoilTypeRepository */
    private $soilTypeRepository;

    public function __construct(SoilTypeRepository $soilTypeRepo)
    {
        $this->soilTypeRepository = $soilTypeRepo;
    }

    /**
     * Display a listing of the SoilType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $soilTypes = $this->soilTypeRepository->all();

        return view('soil_types.index')
            ->with('soilTypes', $soilTypes);
    }

    /**
     * Show the form for creating a new SoilType.
     *
     * @return Response
     */
    public function create()
    {
        return view('soil_types.create');
    }

    /**
     * Store a newly created SoilType in storage.
     *
     * @param CreateSoilTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateSoilTypeRequest $request)
    {
        $input = $request->all();

        $soilType = $this->soilTypeRepository->create($input);

        Flash::success('Soil Type saved successfully.');

        return redirect(route('soilTypes.index'));
    }

    /**
     * Display the specified SoilType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            Flash::error('Soil Type not found');

            return redirect(route('soilTypes.index'));
        }

        return view('soil_types.show')->with('soilType', $soilType);
    }

    /**
     * Show the form for editing the specified SoilType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            Flash::error('Soil Type not found');

            return redirect(route('soilTypes.index'));
        }

        return view('soil_types.edit')->with('soilType', $soilType);
    }

    /**
     * Update the specified SoilType in storage.
     *
     * @param int $id
     * @param UpdateSoilTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSoilTypeRequest $request)
    {
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            Flash::error('Soil Type not found');

            return redirect(route('soilTypes.index'));
        }

        $soilType = $this->soilTypeRepository->update($request->all(), $id);

        Flash::success('Soil Type updated successfully.');

        return redirect(route('soilTypes.index'));
    }

    /**
     * Remove the specified SoilType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $soilType = $this->soilTypeRepository->find($id);

        if (empty($soilType)) {
            Flash::error('Soil Type not found');

            return redirect(route('soilTypes.index'));
        }

        $this->soilTypeRepository->delete($id);

        Flash::success('Soil Type deleted successfully.');

        return redirect(route('soilTypes.index'));
    }
}
