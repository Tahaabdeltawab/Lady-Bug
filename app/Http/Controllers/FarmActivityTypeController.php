<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmActivityTypeRequest;
use App\Http\Requests\UpdateFarmActivityTypeRequest;
use App\Repositories\FarmActivityTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmActivityTypeController extends AppBaseController
{
    /** @var  FarmActivityTypeRepository */
    private $farmActivityTypeRepository;

    public function __construct(FarmActivityTypeRepository $farmActivityTypeRepo)
    {
        $this->farmActivityTypeRepository = $farmActivityTypeRepo;
    }

    /**
     * Display a listing of the FarmActivityType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farmActivityTypes = $this->farmActivityTypeRepository->all();

        return view('farm_activity_types.index')
            ->with('farmActivityTypes', $farmActivityTypes);
    }

    /**
     * Show the form for creating a new FarmActivityType.
     *
     * @return Response
     */
    public function create()
    {
        return view('farm_activity_types.create');
    }

    /**
     * Store a newly created FarmActivityType in storage.
     *
     * @param CreateFarmActivityTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmActivityTypeRequest $request)
    {
        $input = $request->all();

        $farmActivityType = $this->farmActivityTypeRepository->create($input);

        Flash::success('Farm Activity Type saved successfully.');

        return redirect(route('farmActivityTypes.index'));
    }

    /**
     * Display the specified FarmActivityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            Flash::error('Farm Activity Type not found');

            return redirect(route('farmActivityTypes.index'));
        }

        return view('farm_activity_types.show')->with('farmActivityType', $farmActivityType);
    }

    /**
     * Show the form for editing the specified FarmActivityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            Flash::error('Farm Activity Type not found');

            return redirect(route('farmActivityTypes.index'));
        }

        return view('farm_activity_types.edit')->with('farmActivityType', $farmActivityType);
    }

    /**
     * Update the specified FarmActivityType in storage.
     *
     * @param int $id
     * @param UpdateFarmActivityTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmActivityTypeRequest $request)
    {
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            Flash::error('Farm Activity Type not found');

            return redirect(route('farmActivityTypes.index'));
        }

        $farmActivityType = $this->farmActivityTypeRepository->update($request->all(), $id);

        Flash::success('Farm Activity Type updated successfully.');

        return redirect(route('farmActivityTypes.index'));
    }

    /**
     * Remove the specified FarmActivityType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farmActivityType = $this->farmActivityTypeRepository->find($id);

        if (empty($farmActivityType)) {
            Flash::error('Farm Activity Type not found');

            return redirect(route('farmActivityTypes.index'));
        }

        $this->farmActivityTypeRepository->delete($id);

        Flash::success('Farm Activity Type deleted successfully.');

        return redirect(route('farmActivityTypes.index'));
    }
}
