<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use App\Repositories\DistrictRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DistrictController extends AppBaseController
{
    /** @var  DistrictRepository */
    private $districtRepository;

    public function __construct(DistrictRepository $districtRepo)
    {
        $this->districtRepository = $districtRepo;
    }

    /**
     * Display a listing of the District.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $districts = $this->districtRepository->all();

        return view('districts.index')
            ->with('districts', $districts);
    }

    /**
     * Show the form for creating a new District.
     *
     * @return Response
     */
    public function create()
    {
        return view('districts.create');
    }

    /**
     * Store a newly created District in storage.
     *
     * @param CreateDistrictRequest $request
     *
     * @return Response
     */
    public function store(CreateDistrictRequest $request)
    {
        $input = $request->all();

        $district = $this->districtRepository->create($input);

        Flash::success('District saved successfully.');

        return redirect(route('districts.index'));
    }

    /**
     * Display the specified District.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        return view('districts.show')->with('district', $district);
    }

    /**
     * Show the form for editing the specified District.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        return view('districts.edit')->with('district', $district);
    }

    /**
     * Update the specified District in storage.
     *
     * @param int $id
     * @param UpdateDistrictRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDistrictRequest $request)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        $district = $this->districtRepository->update($request->all(), $id);

        Flash::success('District updated successfully.');

        return redirect(route('districts.index'));
    }

    /**
     * Remove the specified District from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $district = $this->districtRepository->find($id);

        if (empty($district)) {
            Flash::error('District not found');

            return redirect(route('districts.index'));
        }

        $this->districtRepository->delete($id);

        Flash::success('District deleted successfully.');

        return redirect(route('districts.index'));
    }
}
