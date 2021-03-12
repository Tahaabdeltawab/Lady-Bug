<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceTableRequest;
use App\Http\Requests\UpdateServiceTableRequest;
use App\Repositories\ServiceTableRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceTableController extends AppBaseController
{
    /** @var  ServiceTableRepository */
    private $serviceTableRepository;

    public function __construct(ServiceTableRepository $serviceTableRepo)
    {
        $this->serviceTableRepository = $serviceTableRepo;
    }

    /**
     * Display a listing of the ServiceTable.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceTables = $this->serviceTableRepository->all();

        return view('service_tables.index')
            ->with('serviceTables', $serviceTables);
    }

    /**
     * Show the form for creating a new ServiceTable.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_tables.create');
    }

    /**
     * Store a newly created ServiceTable in storage.
     *
     * @param CreateServiceTableRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceTableRequest $request)
    {
        $input = $request->all();

        $serviceTable = $this->serviceTableRepository->create($input);

        Flash::success('Service Table saved successfully.');

        return redirect(route('serviceTables.index'));
    }

    /**
     * Display the specified ServiceTable.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            Flash::error('Service Table not found');

            return redirect(route('serviceTables.index'));
        }

        return view('service_tables.show')->with('serviceTable', $serviceTable);
    }

    /**
     * Show the form for editing the specified ServiceTable.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            Flash::error('Service Table not found');

            return redirect(route('serviceTables.index'));
        }

        return view('service_tables.edit')->with('serviceTable', $serviceTable);
    }

    /**
     * Update the specified ServiceTable in storage.
     *
     * @param int $id
     * @param UpdateServiceTableRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceTableRequest $request)
    {
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            Flash::error('Service Table not found');

            return redirect(route('serviceTables.index'));
        }

        $serviceTable = $this->serviceTableRepository->update($request->all(), $id);

        Flash::success('Service Table updated successfully.');

        return redirect(route('serviceTables.index'));
    }

    /**
     * Remove the specified ServiceTable from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceTable = $this->serviceTableRepository->find($id);

        if (empty($serviceTable)) {
            Flash::error('Service Table not found');

            return redirect(route('serviceTables.index'));
        }

        $this->serviceTableRepository->delete($id);

        Flash::success('Service Table deleted successfully.');

        return redirect(route('serviceTables.index'));
    }
}
