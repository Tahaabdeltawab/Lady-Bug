<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\UpdateRoleAPIRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->middleware('permission:roles.store')->only(['store']);
        $this->middleware('permission:roles.update')->only(['update', 'update_role_permissions']);
        $this->middleware('permission:roles.destroy')->only(['destroy']);
    }

    public function index()
    {   try{
            $roles = Role::appAllowedRoles()->get();
            return $this->sendResponse(['all' =>  RoleResource::collection($roles)], 'Roles retrieved successfully');
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
                'role' => ['integer', 'required', 'exists:roles,id'],
                "permissions" => ['nullable', 'array'],
                'permissions.*' => ["nullable", "exists:permissions,id"],
            ]);

            if ($validator->fails()) {
                return $this->sendError(json_encode($validator->errors()));
            }

            $role = Role::find($request->role);

            $role->syncPermissions($request->permissions);
            // $role->attachPermissions([$request->permissions]);

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
            if($role = Role::find($id))
            {
                $role->update([
                    'name'          => $request->name,
                    'display_name'  => $request->display_name,
                    'description'   => $request->description,
                ]);

            if (isset($request->permissions))
            {
                $role->syncPermissions($request->permissions);
            }

                return $this->sendResponse(new RoleResource(Role::find($id)), 'Role updated successfully');
            }

            return $this->sendError('This role does not exist');
        }catch(\Throwable $th){
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try{
            if($role = Role::find($id))
            {
                return $this->sendResponse(new RoleResource($role), 'Role retrived successfully');
            }

            return $this->sendError('This role does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

    public function destroy(int $id)
    {
        try{
            if($role = Role::find($id))
            {
                $role->delete();

                return $this->sendResponse(new RoleResource($role), 'Role deleted successfully');
            }

            return $this->sendError('This role does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

}
