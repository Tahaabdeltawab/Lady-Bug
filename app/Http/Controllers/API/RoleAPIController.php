<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\UpdateRoleAPIRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleAPIController extends AppBaseController
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
        $this->middleware('permission:roles.store')->only(['store']);
        $this->middleware('permission:roles.update')->only(['update', 'update_role_permissions']);
        $this->middleware('permission:roles.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {   try{
            $roles = $this->roleRepository->all(
                $request->except(['page', 'perPage']),
                $request->get('page'),
                $request->get('perPage'),
                ['appAllowedRoles']
            );

            return $this->sendResponse(['all' => RoleResource::collection($roles['all']), 'meta' => $roles['meta']], 'Roles retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function store(UpdateRoleAPIRequest $request)
    {
        try
        {
            $role = Role::create([
                'name'          => $request->name,
                'display_name'  => $request->display_name,
                'description'   => $request->description,
            ]);

            if(isset($request->permissions) && ! empty($request->permissions))
            {
                $role->syncPermissions($request->permissions);
            }
            return $this->sendResponse(new RoleResource($role), 'Role created successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }


    public function update_role_permissions(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'role' => 'integer|required|exists:roles,id',
                'permissions' => 'nullable|array',
                'permissions.*' => 'nullable|exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $role = Role::appAllowedRoles()->find($request->role);
            if(!$role) return $this->sendError('This role is not found or not editable!');
            $role->syncPermissions($request->permissions);

            return $this->sendResponse(new roleResource($role), __('role permissions saved successfully'));
        }
        catch(\Throwable $th)
        {throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function update(int $id, UpdateRoleAPIRequest $request)
    {
        try{
            $role = Role::appAllowedRoles()->find($id);
            if(!$role) return $this->sendError('This role is not found or not editable!');

            $role->update([
                'name'          => $request->name,
                'display_name'  => $request->display_name,
                'description'   => $request->description,
            ]);

            if (isset($request->permissions))
                $role->syncPermissions($request->permissions);

            return $this->sendResponse(new RoleResource(Role::find($id)), 'Role updated successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try{
            $role = Role::appAllowedRoles()->find($id);
            if(!$role) return $this->sendError('This role is not found or not editable!');

            return $this->sendResponse(new RoleResource($role), 'Role retrived successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

    public function destroy(int $id)
    {
        try{
            $role = Role::appAllowedRoles()->find($id);
            if(!$role) return $this->sendError('This role is not found or not editable!');

            $role->delete();
            return $this->sendResponse(new RoleResource($role), 'Role deleted successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

}
