<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskTypeRequest;
use App\Http\Requests\UpdateTaskTypeRequest;
use App\Repositories\TaskTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TaskTypeController extends AppBaseController
{
    /** @var  TaskTypeRepository */
    private $taskTypeRepository;

    public function __construct(TaskTypeRepository $taskTypeRepo)
    {
        $this->taskTypeRepository = $taskTypeRepo;
    }

    /**
     * Display a listing of the TaskType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $taskTypes = $this->taskTypeRepository->all();

        return view('task_types.index')
            ->with('taskTypes', $taskTypes);
    }

    /**
     * Show the form for creating a new TaskType.
     *
     * @return Response
     */
    public function create()
    {
        return view('task_types.create');
    }

    /**
     * Store a newly created TaskType in storage.
     *
     * @param CreateTaskTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateTaskTypeRequest $request)
    {
        $input = $request->all();

        $taskType = $this->taskTypeRepository->create($input);

        Flash::success('Task Type saved successfully.');

        return redirect(route('taskTypes.index'));
    }

    /**
     * Display the specified TaskType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            Flash::error('Task Type not found');

            return redirect(route('taskTypes.index'));
        }

        return view('task_types.show')->with('taskType', $taskType);
    }

    /**
     * Show the form for editing the specified TaskType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            Flash::error('Task Type not found');

            return redirect(route('taskTypes.index'));
        }

        return view('task_types.edit')->with('taskType', $taskType);
    }

    /**
     * Update the specified TaskType in storage.
     *
     * @param int $id
     * @param UpdateTaskTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTaskTypeRequest $request)
    {
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            Flash::error('Task Type not found');

            return redirect(route('taskTypes.index'));
        }

        $taskType = $this->taskTypeRepository->update($request->all(), $id);

        Flash::success('Task Type updated successfully.');

        return redirect(route('taskTypes.index'));
    }

    /**
     * Remove the specified TaskType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $taskType = $this->taskTypeRepository->find($id);

        if (empty($taskType)) {
            Flash::error('Task Type not found');

            return redirect(route('taskTypes.index'));
        }

        $this->taskTypeRepository->delete($id);

        Flash::success('Task Type deleted successfully.');

        return redirect(route('taskTypes.index'));
    }
}
