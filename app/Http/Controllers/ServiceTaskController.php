<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceTaskRequest;
use App\Http\Requests\UpdateServiceTaskRequest;
use App\Repositories\ServiceTaskRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceTaskController extends AppBaseController
{
    /** @var  ServiceTaskRepository */
    private $serviceTaskRepository;

    public function __construct(ServiceTaskRepository $serviceTaskRepo)
    {
        $this->serviceTaskRepository = $serviceTaskRepo;
    }

    /**
     * Display a listing of the ServiceTask.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceTasks = $this->serviceTaskRepository->all();

        return view('service_tasks.index')
            ->with('serviceTasks', $serviceTasks);
    }

    /**
     * Show the form for creating a new ServiceTask.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_tasks.create');
    }

    /**
     * Store a newly created ServiceTask in storage.
     *
     * @param CreateServiceTaskRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceTaskRequest $request)
    {
        $input = $request->all();

        $serviceTask = $this->serviceTaskRepository->create($input);

        Flash::success('Service Task saved successfully.');

        return redirect(route('serviceTasks.index'));
    }

    /**
     * Display the specified ServiceTask.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            Flash::error('Service Task not found');

            return redirect(route('serviceTasks.index'));
        }

        return view('service_tasks.show')->with('serviceTask', $serviceTask);
    }

    /**
     * Show the form for editing the specified ServiceTask.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            Flash::error('Service Task not found');

            return redirect(route('serviceTasks.index'));
        }

        return view('service_tasks.edit')->with('serviceTask', $serviceTask);
    }

    /**
     * Update the specified ServiceTask in storage.
     *
     * @param int $id
     * @param UpdateServiceTaskRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceTaskRequest $request)
    {
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            Flash::error('Service Task not found');

            return redirect(route('serviceTasks.index'));
        }

        $serviceTask = $this->serviceTaskRepository->update($request->all(), $id);

        Flash::success('Service Task updated successfully.');

        return redirect(route('serviceTasks.index'));
    }

    /**
     * Remove the specified ServiceTask from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceTask = $this->serviceTaskRepository->find($id);

        if (empty($serviceTask)) {
            Flash::error('Service Task not found');

            return redirect(route('serviceTasks.index'));
        }

        $this->serviceTaskRepository->delete($id);

        Flash::success('Service Task deleted successfully.');

        return redirect(route('serviceTasks.index'));
    }
}
