<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkablePermissionRequest;
use App\Http\Requests\UpdateWorkablePermissionRequest;
use App\Repositories\WorkablePermissionRepository;
use App\Repositories\WorkableTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WorkablePermissionController extends AppBaseController
{
    /** @var  WorkablePermissionRepository */
    private $workablePermissionRepository;
    private $workableTypeRepository;

    public function __construct(WorkablePermissionRepository $workablePermissionRepo, WorkableTypeRepository $workableTypeRepo)
    {
        $this->workablePermissionRepository = $workablePermissionRepo;
        $this->workableTypeRepository = $workableTypeRepo;
    }

    /**
     * Display a listing of the WorkablePermission.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $workablePermissions = $this->workablePermissionRepository->paginate(10);

        return view('workable_permissions.index')
            ->with('workablePermissions', $workablePermissions);
    }

    /**
     * Show the form for creating a new WorkablePermission.
     *
     * @return Response
     */
    public function create()
    {
        $workableTypes       = $this->workableTypeRepository->all();
        return view('workable_permissions.create', compact('workableTypes'));
    }

    /**
     * Store a newly created WorkablePermission in storage.
     *
     * @param CreateWorkablePermissionRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkablePermissionRequest $request)
    {
        $input = $request->all();

        $workablePermission = $this->workablePermissionRepository->create($input);

        Flash::success('Workable Permission saved successfully.');

        return redirect(route('workablePermissions.index'));
    }

    /**
     * Display the specified WorkablePermission.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            Flash::error('Workable Permission not found');

            return redirect(route('workablePermissions.index'));
        }

        return view('workable_permissions.show')->with('workablePermission', $workablePermission);
    }

    /**
     * Show the form for editing the specified WorkablePermission.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            Flash::error('Workable Permission not found');

            return redirect(route('workablePermissions.index'));
        }
        $workableTypes       = $this->workableTypeRepository->all();

        return view('workable_permissions.edit', compact('workablePermission', 'workableTypes'));
    }

    /**
     * Update the specified WorkablePermission in storage.
     *
     * @param int $id
     * @param UpdateWorkablePermissionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkablePermissionRequest $request)
    {
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            Flash::error('Workable Permission not found');

            return redirect(route('workablePermissions.index'));
        }

        $workablePermission = $this->workablePermissionRepository->update($request->all(), $id);

        Flash::success('Workable Permission updated successfully.');

        return redirect(route('workablePermissions.index'));
    }

    /**
     * Remove the specified WorkablePermission from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            Flash::error('Workable Permission not found');

            return redirect(route('workablePermissions.index'));
        }

        $this->workablePermissionRepository->delete($id);

        Flash::success('Workable Permission deleted successfully.');

        return redirect(route('workablePermissions.index'));
    }
}
