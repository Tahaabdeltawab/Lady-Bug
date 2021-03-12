<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeasuringUnitRequest;
use App\Http\Requests\UpdateMeasuringUnitRequest;
use App\Repositories\MeasuringUnitRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MeasuringUnitController extends AppBaseController
{
    /** @var  MeasuringUnitRepository */
    private $measuringUnitRepository;

    public function __construct(MeasuringUnitRepository $measuringUnitRepo)
    {
        $this->measuringUnitRepository = $measuringUnitRepo;
    }

    /**
     * Display a listing of the MeasuringUnit.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $measuringUnits = $this->measuringUnitRepository->all();

        return view('measuring_units.index')
            ->with('measuringUnits', $measuringUnits);
    }

    /**
     * Show the form for creating a new MeasuringUnit.
     *
     * @return Response
     */
    public function create()
    {
        return view('measuring_units.create');
    }

    /**
     * Store a newly created MeasuringUnit in storage.
     *
     * @param CreateMeasuringUnitRequest $request
     *
     * @return Response
     */
    public function store(CreateMeasuringUnitRequest $request)
    {
        $input = $request->all();

        $measuringUnit = $this->measuringUnitRepository->create($input);

        Flash::success('Measuring Unit saved successfully.');

        return redirect(route('measuringUnits.index'));
    }

    /**
     * Display the specified MeasuringUnit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            Flash::error('Measuring Unit not found');

            return redirect(route('measuringUnits.index'));
        }

        return view('measuring_units.show')->with('measuringUnit', $measuringUnit);
    }

    /**
     * Show the form for editing the specified MeasuringUnit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            Flash::error('Measuring Unit not found');

            return redirect(route('measuringUnits.index'));
        }

        return view('measuring_units.edit')->with('measuringUnit', $measuringUnit);
    }

    /**
     * Update the specified MeasuringUnit in storage.
     *
     * @param int $id
     * @param UpdateMeasuringUnitRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeasuringUnitRequest $request)
    {
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            Flash::error('Measuring Unit not found');

            return redirect(route('measuringUnits.index'));
        }

        $measuringUnit = $this->measuringUnitRepository->update($request->all(), $id);

        Flash::success('Measuring Unit updated successfully.');

        return redirect(route('measuringUnits.index'));
    }

    /**
     * Remove the specified MeasuringUnit from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $measuringUnit = $this->measuringUnitRepository->find($id);

        if (empty($measuringUnit)) {
            Flash::error('Measuring Unit not found');

            return redirect(route('measuringUnits.index'));
        }

        $this->measuringUnitRepository->delete($id);

        Flash::success('Measuring Unit deleted successfully.');

        return redirect(route('measuringUnits.index'));
    }
}
