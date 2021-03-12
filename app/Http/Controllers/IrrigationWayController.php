<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIrrigationWayRequest;
use App\Http\Requests\UpdateIrrigationWayRequest;
use App\Repositories\IrrigationWayRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class IrrigationWayController extends AppBaseController
{
    /** @var  IrrigationWayRepository */
    private $irrigationWayRepository;

    public function __construct(IrrigationWayRepository $irrigationWayRepo)
    {
        $this->irrigationWayRepository = $irrigationWayRepo;
    }

    /**
     * Display a listing of the IrrigationWay.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $irrigationWays = $this->irrigationWayRepository->all();

        return view('irrigation_ways.index')
            ->with('irrigationWays', $irrigationWays);
    }

    /**
     * Show the form for creating a new IrrigationWay.
     *
     * @return Response
     */
    public function create()
    {
        return view('irrigation_ways.create');
    }

    /**
     * Store a newly created IrrigationWay in storage.
     *
     * @param CreateIrrigationWayRequest $request
     *
     * @return Response
     */
    public function store(CreateIrrigationWayRequest $request)
    {
        $input = $request->all();

        $irrigationWay = $this->irrigationWayRepository->create($input);

        Flash::success('Irrigation Way saved successfully.');

        return redirect(route('irrigationWays.index'));
    }

    /**
     * Display the specified IrrigationWay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            Flash::error('Irrigation Way not found');

            return redirect(route('irrigationWays.index'));
        }

        return view('irrigation_ways.show')->with('irrigationWay', $irrigationWay);
    }

    /**
     * Show the form for editing the specified IrrigationWay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            Flash::error('Irrigation Way not found');

            return redirect(route('irrigationWays.index'));
        }

        return view('irrigation_ways.edit')->with('irrigationWay', $irrigationWay);
    }

    /**
     * Update the specified IrrigationWay in storage.
     *
     * @param int $id
     * @param UpdateIrrigationWayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIrrigationWayRequest $request)
    {
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            Flash::error('Irrigation Way not found');

            return redirect(route('irrigationWays.index'));
        }

        $irrigationWay = $this->irrigationWayRepository->update($request->all(), $id);

        Flash::success('Irrigation Way updated successfully.');

        return redirect(route('irrigationWays.index'));
    }

    /**
     * Remove the specified IrrigationWay from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $irrigationWay = $this->irrigationWayRepository->find($id);

        if (empty($irrigationWay)) {
            Flash::error('Irrigation Way not found');

            return redirect(route('irrigationWays.index'));
        }

        $this->irrigationWayRepository->delete($id);

        Flash::success('Irrigation Way deleted successfully.');

        return redirect(route('irrigationWays.index'));
    }
}
