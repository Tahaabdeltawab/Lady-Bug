<?php

namespace App\Http\Controllers\API\UserManagement;

use Illuminate\Http\Request;
use Mekaeil\LaravelUserManagement\Repository\Contracts\DepartmentRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Contracts\UserRepositoryInterface;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\StoreDepartment;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\UpdateDepartment;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DepartmentResource;

class DepartmentsController extends AppBaseController
{

    protected $departmentRepository;
    protected $userRepository;

    public function __construct(
        DepartmentRepositoryInterface $department, 
        UserRepositoryInterface $user)
    {
        $this->departmentRepository = $department;
        $this->userRepository       = $user;
    }

    public function index()
    {
        try{
            $departments = $this->departmentRepository->all();

            return $this->sendResponse(DepartmentResource::collection($departments), 'Departments retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

   /*  public function create()
    {
        $departments = $this->departmentRepository->all();

        return view('user-management.department.create', compact('departments'));    
    }

    public function edit(int $ID)
    {   
        if($department = $this->departmentRepository->find($ID))
        {
            $departments = $this->departmentRepository->all();

            return view('user-management.department.edit', compact('department', 'departments'));    
        }

        return redirect()->route('admin.user_management.department.index')->with('message',[
           'type'   => 'danger',
           'text'   => 'Department does not exist!' 
        ]);
    }*/

    public function store(StoreDepartment $request)
    {
        try{
            $parent = null;
            if($request->parent_id && $findDepartment = $this->departmentRepository->find($request->parent_id))
            {
                $parent = $findDepartment->id;
            }

            $department = $this->departmentRepository->store([
                'title'     => $request->title,
                'parent_id' => $parent,
            ]);

            return $this->sendResponse($department, 'Department created successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    public function update(int $ID, UpdateDepartment $request)
    {
        try{
            if($department = $this->departmentRepository->find($ID))
            {
                $parent = null;
                if($request->parent_id && $findDepartment = $this->departmentRepository->find($request->parent_id))
                {
                    $parent = $findDepartment->id;
                }

                $this->departmentRepository->update($ID,[
                    'title'     => $request->title,
                    'parent_id' => $parent,
                ]);

                return $this->sendResponse($this->departmentRepository->find($ID), 'Department updated successfully');
            }

            return $this->sendError('This department does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    public function delete(int $ID)
    {
        try{
            if($department = $this->departmentRepository->find($ID))
            {
                $this->departmentRepository->delete($ID);

                return $this->sendResponse($department, 'Department deleted successfully');
            }

            return $this->sendError('This department does not exist');
                
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

}
