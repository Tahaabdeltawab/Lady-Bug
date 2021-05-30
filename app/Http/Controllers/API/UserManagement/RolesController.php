<?php

namespace App\Http\Controllers\API\UserManagement;

use Illuminate\Http\Request;
use Mekaeil\LaravelUserManagement\Repository\Contracts\PermissionRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Contracts\RoleRepositoryInterface;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\StoreRole;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\UpdateRole;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\RoleResource;

class RolesController extends AppBaseController
{
    protected $permissionRepository;
    protected $roleRepository;

    public function __construct(
        PermissionRepositoryInterface $permission,
        RoleRepositoryInterface $role)
    {
        $this->permissionRepository = $permission;
        $this->roleRepository       = $role;
    }

    public function index()
    {   try{
            $roles = $this->roleRepository->all();
            return $this->sendResponse(RoleResource::collection($roles), 'Roles retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function store(StoreRole $request)
    {
        try{
            $role = $this->roleRepository->store([
                'name'          => $request->name,
                'title'         => $request->title,
                'guard_name'    => $request->guard_name,
                'description'   => $request->description,
            ]);

            if(! empty($request->permissions))
            {
                $this->permissionRepository->setPermissionToRole($role->id, $request->permissions);
            }
        return $this->sendResponse($role, 'Role created successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

    public function update(int $ID, UpdateRole $request)
    {
        try{
            if($role = $this->roleRepository->find($ID))
            {
                $this->roleRepository->update($ID,[
                    'name'          => $request->name,
                    'title'         => $request->title,
                    'guard_name'    => $request->guard_name,
                    'description'   => $request->description,
                ]);

                $permissions = $request->permissions ?? [];
                $this->permissionRepository->SyncPermToRole($role->id, $permissions);

                return $this->sendResponse($this->roleRepository->find($ID), 'Role updated successfully');
            }

            return $this->sendError('This role does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function delete(int $ID)
    {
        try{
            if($this->roleRepository->find($ID))
            {
                $this->roleRepository->delete($ID);

                return $this->sendResponse($role, 'Role deleted successfully');
            }

            return $this->sendError('This role does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

}
