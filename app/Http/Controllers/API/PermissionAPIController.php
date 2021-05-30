<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\Admin\CreatePermissionAPIRequest;
use App\Http\Requests\API\Admin\UpdatePermissionAPIRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;


class PermissionAPIController extends AppBaseController
{
    protected $permissionRepository;

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        try{
            $permissions = Permission::paginate(10);
            return $this->sendResponse(PermissionResource::collection($permissions), 'Permissions retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function store(CreatePermissionAPIRequest $request)
    {
        try{
            $permission = Permission::create([
                'name'          => $request->name,
                'display_name'  => $request->display_name,
                'description'   => $request->description,
            ]);

            return $this->sendResponse(new PermissionResource($permission), 'Permission created successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function update(int $ID, UpdatePermissionAPIRequest $request)
    {
        try{
            if($permission = Permission::find($ID))
            {
                $permission->update([
                    'name'          => $request->name,
                    'display_name'  => $request->display_name,
                    'description'   => $request->description,
                ]);

            return $this->sendResponse(new PermissionResource(Permission::find($ID)), 'Permission updated successfully');
            }

            return $this->sendError('This permission does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }


    }

    public function show(int $ID)
    {
        try{
            if($permission = Permission::find($ID))
            {
                return $this->sendResponse(new PermissionResource($permission), 'Permission retrived successfully');
            }

            return $this->sendError('This permission does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }

    }

    public function destroy(int $ID)
    {
        try{
            if($permission = Permission::find($ID))
            {
                $name = $permission->name;
                $permission->delete();

                return $this->sendResponse(new PermissionResource($permission), 'Permission deleted successfully');
            }

            return $this->sendError('This permission does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }
}
