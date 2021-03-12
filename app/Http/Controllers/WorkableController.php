<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkableRequest;
use App\Http\Requests\UpdateWorkableRequest;
use App\Repositories\WorkableRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WorkableController extends AppBaseController
{
    /** @var  WorkableRepository */
    private $workableRepository;

    public function __construct(WorkableRepository $workableRepo)
    {
        $this->workableRepository = $workableRepo;
    }

    /**
     * Display a listing of the Workable.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $workables = $this->workableRepository->paginate(10);

        return view('workables.index')
            ->with('workables', $workables);
    }

    /**
     * Show the form for creating a new Workable.
     *
     * @return Response
     */
    public function create()
    {
        return view('workables.create');
    }

    /**
     * Store a newly created Workable in storage.
     *
     * @param CreateWorkableRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkableRequest $request)
    {
        $input = $request->all();

        $workable = $this->workableRepository->create($input);

        Flash::success('Workable saved successfully.');

        return redirect(route('workables.index'));
    }

    /**
     * Display the specified Workable.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            Flash::error('Workable not found');

            return redirect(route('workables.index'));
        }

        return view('workables.show')->with('workable', $workable);
    }

    /**
     * Show the form for editing the specified Workable.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            Flash::error('Workable not found');

            return redirect(route('workables.index'));
        }

        return view('workables.edit')->with('workable', $workable);
    }

    /**
     * Update the specified Workable in storage.
     *
     * @param int $id
     * @param UpdateWorkableRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkableRequest $request)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            Flash::error('Workable not found');

            return redirect(route('workables.index'));
        }

        $workable = $this->workableRepository->update($request->all(), $id);

        Flash::success('Workable updated successfully.');

        return redirect(route('workables.index'));
    }

    /**
     * Remove the specified Workable from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {
            Flash::error('Workable not found');

            return redirect(route('workables.index'));
        }

        $this->workableRepository->delete($id);

        Flash::success('Workable deleted successfully.');

        return redirect(route('workables.index'));
    }
}
