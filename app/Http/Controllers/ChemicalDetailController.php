<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChemicalDetailRequest;
use App\Http\Requests\UpdateChemicalDetailRequest;
use App\Repositories\ChemicalDetailRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChemicalDetailController extends AppBaseController
{
    /** @var  ChemicalDetailRepository */
    private $chemicalDetailRepository;

    public function __construct(ChemicalDetailRepository $chemicalDetailRepo)
    {
        $this->chemicalDetailRepository = $chemicalDetailRepo;
    }

    /**
     * Display a listing of the ChemicalDetail.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $chemicalDetails = $this->chemicalDetailRepository->all();

        return view('chemical_details.index')
            ->with('chemicalDetails', $chemicalDetails);
    }

    /**
     * Show the form for creating a new ChemicalDetail.
     *
     * @return Response
     */
    public function create()
    {
        return view('chemical_details.create');
    }

    /**
     * Store a newly created ChemicalDetail in storage.
     *
     * @param CreateChemicalDetailRequest $request
     *
     * @return Response
     */
    public function store(CreateChemicalDetailRequest $request)
    {
        $input = $request->all();

        $chemicalDetail = $this->chemicalDetailRepository->create($input);

        Flash::success('Chemical Detail saved successfully.');

        return redirect(route('chemicalDetails.index'));
    }

    /**
     * Display the specified ChemicalDetail.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            Flash::error('Chemical Detail not found');

            return redirect(route('chemicalDetails.index'));
        }

        return view('chemical_details.show')->with('chemicalDetail', $chemicalDetail);
    }

    /**
     * Show the form for editing the specified ChemicalDetail.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            Flash::error('Chemical Detail not found');

            return redirect(route('chemicalDetails.index'));
        }

        return view('chemical_details.edit')->with('chemicalDetail', $chemicalDetail);
    }

    /**
     * Update the specified ChemicalDetail in storage.
     *
     * @param int $id
     * @param UpdateChemicalDetailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChemicalDetailRequest $request)
    {
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            Flash::error('Chemical Detail not found');

            return redirect(route('chemicalDetails.index'));
        }

        $chemicalDetail = $this->chemicalDetailRepository->update($request->all(), $id);

        Flash::success('Chemical Detail updated successfully.');

        return redirect(route('chemicalDetails.index'));
    }

    /**
     * Remove the specified ChemicalDetail from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chemicalDetail = $this->chemicalDetailRepository->find($id);

        if (empty($chemicalDetail)) {
            Flash::error('Chemical Detail not found');

            return redirect(route('chemicalDetails.index'));
        }

        $this->chemicalDetailRepository->delete($id);

        Flash::success('Chemical Detail deleted successfully.');

        return redirect(route('chemicalDetails.index'));
    }
}
