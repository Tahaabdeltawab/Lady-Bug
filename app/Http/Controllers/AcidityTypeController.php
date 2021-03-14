<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAcidityTypeRequest;
use App\Http\Requests\UpdateAcidityTypeRequest;
use App\Repositories\AcidityTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AcidityTypeController extends AppBaseController
{
    /** @var  AcidityTypeRepository */
    private $acidityTypeRepository;

    public function __construct(AcidityTypeRepository $acidityTypeRepo)
    {
        $this->acidityTypeRepository = $acidityTypeRepo;
    }

    /**
     * Display a listing of the AcidityType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $acidityTypes = $this->acidityTypeRepository->all();

        return view('acidity_types.index')
            ->with('acidityTypes', $acidityTypes);
    }

    /**
     * Show the form for creating a new AcidityType.
     *
     * @return Response
     */
    public function create()
    {
        return view('acidity_types.create');
    }

    /**
     * Store a newly created AcidityType in storage.
     *
     * @param CreateAcidityTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateAcidityTypeRequest $request)
    {
        $input = $request->all();

        $acidityType = $this->acidityTypeRepository->create($input);

        Flash::success('Acidity Type saved successfully.');

        return redirect(route('acidityTypes.index'));
    }

    /**
     * Display the specified AcidityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            Flash::error('Acidity Type not found');

            return redirect(route('acidityTypes.index'));
        }

        return view('acidity_types.show')->with('acidityType', $acidityType);
    }

    /**
     * Show the form for editing the specified AcidityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            Flash::error('Acidity Type not found');

            return redirect(route('acidityTypes.index'));
        }

        return view('acidity_types.edit')->with('acidityType', $acidityType);
    }

    /**
     * Update the specified AcidityType in storage.
     *
     * @param int $id
     * @param UpdateAcidityTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAcidityTypeRequest $request)
    {
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            Flash::error('Acidity Type not found');

            return redirect(route('acidityTypes.index'));
        }

        $acidityType = $this->acidityTypeRepository->update($request->all(), $id);

        Flash::success('Acidity Type updated successfully.');

        return redirect(route('acidityTypes.index'));
    }

    /**
     * Remove the specified AcidityType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $acidityType = $this->acidityTypeRepository->find($id);

        if (empty($acidityType)) {
            Flash::error('Acidity Type not found');

            return redirect(route('acidityTypes.index'));
        }

        $this->acidityTypeRepository->delete($id);

        Flash::success('Acidity Type deleted successfully.');

        return redirect(route('acidityTypes.index'));
    }
}
