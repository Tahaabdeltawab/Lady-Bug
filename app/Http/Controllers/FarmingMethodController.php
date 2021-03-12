<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmingMethodRequest;
use App\Http\Requests\UpdateFarmingMethodRequest;
use App\Repositories\FarmingMethodRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmingMethodController extends AppBaseController
{
    /** @var  FarmingMethodRepository */
    private $farmingMethodRepository;

    public function __construct(FarmingMethodRepository $farmingMethodRepo)
    {
        $this->farmingMethodRepository = $farmingMethodRepo;
    }

    /**
     * Display a listing of the FarmingMethod.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmingMethods = $this->farmingMethodRepository->all();

        return view('farming_methods.index')
            ->with('farmingMethods', $farmingMethods);
    }

    /**
     * Show the form for creating a new FarmingMethod.
     *
     * @return Response
     */
    public function create()
    {
        return view('farming_methods.create');
    }

    /**
     * Store a newly created FarmingMethod in storage.
     *
     * @param CreateFarmingMethodRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmingMethodRequest $request)
    {
        $input = $request->all();

        $farmingMethod = $this->farmingMethodRepository->create($input);

        Flash::success('Farming Method saved successfully.');

        return redirect(route('farmingMethods.index'));
    }

    /**
     * Display the specified FarmingMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            Flash::error('Farming Method not found');

            return redirect(route('farmingMethods.index'));
        }

        return view('farming_methods.show')->with('farmingMethod', $farmingMethod);
    }

    /**
     * Show the form for editing the specified FarmingMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            Flash::error('Farming Method not found');

            return redirect(route('farmingMethods.index'));
        }

        return view('farming_methods.edit')->with('farmingMethod', $farmingMethod);
    }

    /**
     * Update the specified FarmingMethod in storage.
     *
     * @param int $id
     * @param UpdateFarmingMethodRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmingMethodRequest $request)
    {
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            Flash::error('Farming Method not found');

            return redirect(route('farmingMethods.index'));
        }

        $farmingMethod = $this->farmingMethodRepository->update($request->all(), $id);

        Flash::success('Farming Method updated successfully.');

        return redirect(route('farmingMethods.index'));
    }

    /**
     * Remove the specified FarmingMethod from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmingMethod = $this->farmingMethodRepository->find($id);

        if (empty($farmingMethod)) {
            Flash::error('Farming Method not found');

            return redirect(route('farmingMethods.index'));
        }

        $this->farmingMethodRepository->delete($id);

        Flash::success('Farming Method deleted successfully.');

        return redirect(route('farmingMethods.index'));
    }
}
