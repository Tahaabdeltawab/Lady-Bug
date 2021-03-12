<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmingWayRequest;
use App\Http\Requests\UpdateFarmingWayRequest;
use App\Repositories\FarmingWayRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmingWayController extends AppBaseController
{
    /** @var  FarmingWayRepository */
    private $farmingWayRepository;

    public function __construct(FarmingWayRepository $farmingWayRepo)
    {
        $this->farmingWayRepository = $farmingWayRepo;
    }

    /**
     * Display a listing of the FarmingWay.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmingWays = $this->farmingWayRepository->all();

        return view('farming_ways.index')
            ->with('farmingWays', $farmingWays);
    }

    /**
     * Show the form for creating a new FarmingWay.
     *
     * @return Response
     */
    public function create()
    {
        return view('farming_ways.create');
    }

    /**
     * Store a newly created FarmingWay in storage.
     *
     * @param CreateFarmingWayRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmingWayRequest $request)
    {
        $input = $request->all();

        $farmingWay = $this->farmingWayRepository->create($input);

        Flash::success('Farming Way saved successfully.');

        return redirect(route('farmingWays.index'));
    }

    /**
     * Display the specified FarmingWay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            Flash::error('Farming Way not found');

            return redirect(route('farmingWays.index'));
        }

        return view('farming_ways.show')->with('farmingWay', $farmingWay);
    }

    /**
     * Show the form for editing the specified FarmingWay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            Flash::error('Farming Way not found');

            return redirect(route('farmingWays.index'));
        }

        return view('farming_ways.edit')->with('farmingWay', $farmingWay);
    }

    /**
     * Update the specified FarmingWay in storage.
     *
     * @param int $id
     * @param UpdateFarmingWayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmingWayRequest $request)
    {
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            Flash::error('Farming Way not found');

            return redirect(route('farmingWays.index'));
        }

        $farmingWay = $this->farmingWayRepository->update($request->all(), $id);

        Flash::success('Farming Way updated successfully.');

        return redirect(route('farmingWays.index'));
    }

    /**
     * Remove the specified FarmingWay from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmingWay = $this->farmingWayRepository->find($id);

        if (empty($farmingWay)) {
            Flash::error('Farming Way not found');

            return redirect(route('farmingWays.index'));
        }

        $this->farmingWayRepository->delete($id);

        Flash::success('Farming Way deleted successfully.');

        return redirect(route('farmingWays.index'));
    }
}
