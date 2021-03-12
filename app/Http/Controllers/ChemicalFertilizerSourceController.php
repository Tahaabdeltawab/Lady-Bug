<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChemicalFertilizerSourceRequest;
use App\Http\Requests\UpdateChemicalFertilizerSourceRequest;
use App\Repositories\ChemicalFertilizerSourceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChemicalFertilizerSourceController extends AppBaseController
{
    /** @var  ChemicalFertilizerSourceRepository */
    private $chemicalFertilizerSourceRepository;

    public function __construct(ChemicalFertilizerSourceRepository $chemicalFertilizerSourceRepo)
    {
        $this->chemicalFertilizerSourceRepository = $chemicalFertilizerSourceRepo;
    }

    /**
     * Display a listing of the ChemicalFertilizerSource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $chemicalFertilizerSources = $this->chemicalFertilizerSourceRepository->all();

        return view('chemical_fertilizer_sources.index')
            ->with('chemicalFertilizerSources', $chemicalFertilizerSources);
    }

    /**
     * Show the form for creating a new ChemicalFertilizerSource.
     *
     * @return Response
     */
    public function create()
    {
        return view('chemical_fertilizer_sources.create');
    }

    /**
     * Store a newly created ChemicalFertilizerSource in storage.
     *
     * @param CreateChemicalFertilizerSourceRequest $request
     *
     * @return Response
     */
    public function store(CreateChemicalFertilizerSourceRequest $request)
    {
        $input = $request->all();

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->create($input);

        Flash::success('Chemical Fertilizer Source saved successfully.');

        return redirect(route('chemicalFertilizerSources.index'));
    }

    /**
     * Display the specified ChemicalFertilizerSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            Flash::error('Chemical Fertilizer Source not found');

            return redirect(route('chemicalFertilizerSources.index'));
        }

        return view('chemical_fertilizer_sources.show')->with('chemicalFertilizerSource', $chemicalFertilizerSource);
    }

    /**
     * Show the form for editing the specified ChemicalFertilizerSource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            Flash::error('Chemical Fertilizer Source not found');

            return redirect(route('chemicalFertilizerSources.index'));
        }

        return view('chemical_fertilizer_sources.edit')->with('chemicalFertilizerSource', $chemicalFertilizerSource);
    }

    /**
     * Update the specified ChemicalFertilizerSource in storage.
     *
     * @param int $id
     * @param UpdateChemicalFertilizerSourceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChemicalFertilizerSourceRequest $request)
    {
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            Flash::error('Chemical Fertilizer Source not found');

            return redirect(route('chemicalFertilizerSources.index'));
        }

        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->update($request->all(), $id);

        Flash::success('Chemical Fertilizer Source updated successfully.');

        return redirect(route('chemicalFertilizerSources.index'));
    }

    /**
     * Remove the specified ChemicalFertilizerSource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chemicalFertilizerSource = $this->chemicalFertilizerSourceRepository->find($id);

        if (empty($chemicalFertilizerSource)) {
            Flash::error('Chemical Fertilizer Source not found');

            return redirect(route('chemicalFertilizerSources.index'));
        }

        $this->chemicalFertilizerSourceRepository->delete($id);

        Flash::success('Chemical Fertilizer Source deleted successfully.');

        return redirect(route('chemicalFertilizerSources.index'));
    }
}
