<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSaltDetailRequest;
use App\Http\Requests\UpdateSaltDetailRequest;
use App\Repositories\SaltDetailRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SaltDetailController extends AppBaseController
{
    /** @var  SaltDetailRepository */
    private $saltDetailRepository;

    public function __construct(SaltDetailRepository $saltDetailRepo)
    {
        $this->saltDetailRepository = $saltDetailRepo;
    }

    /**
     * Display a listing of the SaltDetail.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $saltDetails = $this->saltDetailRepository->all();

        return view('salt_details.index')
            ->with('saltDetails', $saltDetails);
    }

    /**
     * Show the form for creating a new SaltDetail.
     *
     * @return Response
     */
    public function create()
    {
        return view('salt_details.create');
    }

    /**
     * Store a newly created SaltDetail in storage.
     *
     * @param CreateSaltDetailRequest $request
     *
     * @return Response
     */
    public function store(CreateSaltDetailRequest $request)
    {
        $input = $request->all();

        $saltDetail = $this->saltDetailRepository->create($input);

        Flash::success('Salt Detail saved successfully.');

        return redirect(route('saltDetails.index'));
    }

    /**
     * Display the specified SaltDetail.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            Flash::error('Salt Detail not found');

            return redirect(route('saltDetails.index'));
        }

        return view('salt_details.show')->with('saltDetail', $saltDetail);
    }

    /**
     * Show the form for editing the specified SaltDetail.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            Flash::error('Salt Detail not found');

            return redirect(route('saltDetails.index'));
        }

        return view('salt_details.edit')->with('saltDetail', $saltDetail);
    }

    /**
     * Update the specified SaltDetail in storage.
     *
     * @param int $id
     * @param UpdateSaltDetailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSaltDetailRequest $request)
    {
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            Flash::error('Salt Detail not found');

            return redirect(route('saltDetails.index'));
        }

        $saltDetail = $this->saltDetailRepository->update($request->all(), $id);

        Flash::success('Salt Detail updated successfully.');

        return redirect(route('saltDetails.index'));
    }

    /**
     * Remove the specified SaltDetail from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $saltDetail = $this->saltDetailRepository->find($id);

        if (empty($saltDetail)) {
            Flash::error('Salt Detail not found');

            return redirect(route('saltDetails.index'));
        }

        $this->saltDetailRepository->delete($id);

        Flash::success('Salt Detail deleted successfully.');

        return redirect(route('saltDetails.index'));
    }
}
