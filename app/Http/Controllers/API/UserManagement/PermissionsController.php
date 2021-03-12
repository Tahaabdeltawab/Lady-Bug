<?php

namespace App\Http\Controllers\API\UserManagement;

use Illuminate\Http\Request;
use Mekaeil\LaravelUserManagement\Repository\Contracts\PermissionRepositoryInterface;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\StorePermission;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\UpdatePermission;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PermissionResource;


class PermissionsController extends AppBaseController
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permission)
    {
        $this->permissionRepository = $permission;
    }

    public function index(Request $request)
    {
        try{
            $permissions = $this->permissionRepository->paginate(config('laravel_user_management.row_list_per_page'));
            return $this->sendResponse(PermissionResource::collection($permissions), 'Permissions retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    /* public function create()
    {
        return view('user-management.permission.create');
    }

    public function edit(int $ID)
    {
        if($permission = $this->permissionRepository->find($ID))
        {
            return view('user-management.permission.edit', compact('permission'));
        }
    
        return redirect()->route('admin.user_management.permission.index')->with('message',[
            'type'   => 'danger',
            'text'   => "This permission << $request->name >> does not exist!",
        ]);
      

    } */

    public function store(StorePermission $request)
    {
        try{
            $permission = $this->permissionRepository->store([
                'name'          => $request->name,
                'title'         => $request->title,
                'module'        => $request->module,
                'guard_name'    => $request->guard_name,
                'description'   => $request->description,            
            ]);
                
            return $this->sendResponse($permission, 'Permission created successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    public function update(int $ID, UpdatePermission $request)
    {
        try{
            if($permission = $this->permissionRepository->find($ID))
            {
                $this->permissionRepository->update($ID,[
                    'name'          => $request->name,
                    'title'         => $request->title,
                    'module'        => $request->module,
                    'guard_name'    => $request->guard_name,
                    'description'   => $request->description,        
                ]);

            return $this->sendResponse($this->permissionRepository->find($ID), 'Permission updated successfully');
            }

            return $this->sendError('This permission does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }

   
    }

    public function delete(int $ID)
    {
        try{
            if($permission = $this->permissionRepository->find($ID))
            {
                $name = $permission->name;
                $this->permissionRepository->delete($ID);

                return $this->sendResponse($permission, 'Permission deleted successfully');
            }

            return $this->sendError('This permission does not exist');
                
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }
}
