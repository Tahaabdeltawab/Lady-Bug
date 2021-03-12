<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkableRoleRequest;
use App\Http\Requests\UpdateWorkableRoleRequest;
use App\Repositories\WorkableRoleRepository;
use App\Repositories\WorkablePermissionRepository;
use App\Repositories\WorkableTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\WorkablePermission;

class WorkableRoleController extends AppBaseController
{
    /** @var  WorkableRoleRepository */
    private $workableRoleRepository;
    private $workablePermissionRepository;
    private $workableTypeRepository;

    public function __construct(WorkableRoleRepository $workableRoleRepo, WorkablePermissionRepository $workablePermissionRepo, WorkableTypeRepository $workableTypeRepo)
    {
        $this->workableRoleRepository       = $workableRoleRepo;
        $this->workablePermissionRepository = $workablePermissionRepo;
        $this->workableTypeRepository = $workableTypeRepo;
    }

    /**
     * Display a listing of the WorkableRole.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $workableRoles = $this->workableRoleRepository->paginate(10);

        return view('workable_roles.index')
            ->with('workableRoles', $workableRoles);
    }

    /**
     * Show the form for creating a new WorkableRole.
     *
     * @return Response
     */
    public function create()
    {
        $workable_type = request()->get('workable_type');
        $workablePermissions = $this->workablePermissionRepository->all();
        $workableTypes       = $this->workableTypeRepository->all();
        return view('workable_roles.create', compact('workablePermissions','workableTypes'));
    }


    public function permissions_by_workable_type()
    {
        $workable_type_id = request()->workable_type_id;
        $workable_type_name = request()->workable_type_name;
        // $workableTypes       = $this->workableTypeRepository->all();
        // $workablePermissions = $this->workablePermissionRepository->all();
        // $workablePermissions = resolve($this->workablePermissionRepository->model())->where('workable_type_id',$workable_type_id)->get();
        $workablePermissions = resolve($this->workablePermissionRepository->model())->whereHas('workable_type', function($q) use($workable_type_name) {
            return $q->where('name', $workable_type_name);
        })->get();
        return response()->json($workablePermissions);
    }

    /**
     * Store a newly created WorkableRole in storage.
     *
     * @param CreateWorkableRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkableRoleRequest $request)
    {
        $input = $request->all();

        $workableRole = $this->workableRoleRepository->create($input);

        if(! empty($request->workablePermissions))
        {
            $workableRole->workable_permissions()->sync($request->workablePermissions);
        }

        Flash::success('Workable Role saved successfully.');

        return redirect(route('workableRoles.index'));
    }

    /**
     * Display the specified WorkableRole.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $workableRole = $this->workableRoleRepository->find($id);

        if (empty($workableRole)) {
            Flash::error('Workable Role not found');

            return redirect(route('workableRoles.index'));
        }

        return view('workable_roles.show')->with('workableRole', $workableRole);
    }

    /**
     * Show the form for editing the specified WorkableRole.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if($workableRole = $this->workableRoleRepository->find($id))
        {
            $workableTypes       = $this->workableTypeRepository->all();

            $workablePermissions        = $this->workablePermissionRepository->all();

          /*   $workablePermissions = resolve($this->workablePermissionRepository->model())->whereHas('workable_type', function($q) use($workableRole) {

                return $q->where('name', $workableRole->workable_type->name);

            })->get(); */

            $wroleHaswPermissions = array_column(json_decode($workableRole->workable_permissions, true), 'id');

            return view('workable_roles.edit', compact('workableRole', 'workablePermissions', 'wroleHaswPermissions', 'workableTypes'));
        }

        Flash::error('Workable Role not found');
        return redirect(route('workableRoles.index'));
    }

    /**
     * Update the specified WorkableRole in storage.
     *
     * @param int $id
     * @param UpdateWorkableRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkableRoleRequest $request)
    {
        $workableRole = $this->workableRoleRepository->find($id);

        if (empty($workableRole)) {
            Flash::error('Workable Role not found');

            return redirect(route('workableRoles.index'));
        }

        $workableRole = $this->workableRoleRepository->update($request->all(), $id);

        $workablePermissions = $request->workablePermissions ?? [];
        $workableRole->workable_permissions()->sync($workablePermissions);

        Flash::success('Workable Role updated successfully.');

        return redirect(route('workableRoles.index'));
    }

    /**
     * Remove the specified WorkableRole from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $workableRole = $this->workableRoleRepository->find($id);

        if (empty($workableRole)) {
            Flash::error('Workable Role not found');

            return redirect(route('workableRoles.index'));
        }

        $this->workableRoleRepository->delete($id);

        Flash::success('Workable Role deleted successfully.');

        return redirect(route('workableRoles.index'));
    }
}
