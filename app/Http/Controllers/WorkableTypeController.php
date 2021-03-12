<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkableTypeRequest;
use App\Http\Requests\UpdateWorkableTypeRequest;
use App\Repositories\WorkableTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WorkableTypeController extends AppBaseController
{
    /** @var  WorkableTypeRepository */
    private $workableTypeRepository;

    public function __construct(WorkableTypeRepository $workableTypeRepo)
    {
        $this->workableTypeRepository = $workableTypeRepo;
    }

    /**
     * Display a listing of the WorkableType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $workableTypes = $this->workableTypeRepository->paginate(10);

        return view('workable_types.index')
            ->with('workableTypes', $workableTypes);
    }

    /**
     * Show the form for creating a new WorkableType.
     *
     * @return Response
     */
    public function create()
    {
        return view('workable_types.create');
    }

    /**
     * Store a newly created WorkableType in storage.
     *
     * @param CreateWorkableTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkableTypeRequest $request)
    {
        $input = $request->all();

        $workableType = $this->workableTypeRepository->create($input);

        Flash::success('Workable Type saved successfully.');

        return redirect(route('workableTypes.index'));
    }

    /**
     * Display the specified WorkableType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            Flash::error('Workable Type not found');

            return redirect(route('workableTypes.index'));
        }

        return view('workable_types.show')->with('workableType', $workableType);
    }

    /**
     * Show the form for editing the specified WorkableType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            Flash::error('Workable Type not found');

            return redirect(route('workableTypes.index'));
        }

        return view('workable_types.edit')->with('workableType', $workableType);
    }

    /**
     * Update the specified WorkableType in storage.
     *
     * @param int $id
     * @param UpdateWorkableTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkableTypeRequest $request)
    {
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            Flash::error('Workable Type not found');

            return redirect(route('workableTypes.index'));
        }

        $workableType = $this->workableTypeRepository->update($request->all(), $id);

        Flash::success('Workable Type updated successfully.');

        return redirect(route('workableTypes.index'));
    }

    /**
     * Remove the specified WorkableType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workableType = $this->workableTypeRepository->find($id);

        if (empty($workableType)) {
            Flash::error('Workable Type not found');

            return redirect(route('workableTypes.index'));
        }

        $this->workableTypeRepository->delete($id);

        Flash::success('Workable Type deleted successfully.');

        return redirect(route('workableTypes.index'));
    }
}
