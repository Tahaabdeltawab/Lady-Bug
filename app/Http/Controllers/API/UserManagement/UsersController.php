<?php

namespace App\Http\Controllers\API\UserManagement;

use Illuminate\Http\Request;
use Mekaeil\LaravelUserManagement\Repository\Contracts\PermissionRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Contracts\RoleRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Contracts\UserRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\DepartmentRepository;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\StoreUser;
use Mekaeil\LaravelUserManagement\Http\Requests\Admin\UpdateUser;
use App\Http\Controllers\AppBaseController;
use App\Entities\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UsersController extends AppBaseController
{
    protected $userRepository;
    protected $permissionRepository;
    protected $roleRepository;
    protected $departmentRepository;

    public function __construct(
        // UserRepositoryInterface $user,
        UserRepository $user,
        PermissionRepositoryInterface $permission,
        RoleRepositoryInterface $role,
        DepartmentRepository $department)
    {
        $this->permissionRepository = $permission;
        $this->roleRepository       = $role;
        $this->userRepository       = $user;
        $this->departmentRepository = $department;
    }

    public function index()
    {
        try{
            $users          = $this->userRepository->all();
            // $users          = User::with(['roles' => function($q){return $q->where('id',4);}])->where('id',7)->first();
            // $users          = $this->userRepository->allWithTrashed(); //this paginates the results and add pagination links and get soft deleted records
            
            // return $this->sendResponse($users, 'Users retrieved successfully');
            return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    /* public function create()
    {
        $roles       = $this->roleRepository->all();
        $departments = $this->departmentRepository->all();

        return view('user-management.user.create', compact('roles', 'departments'));
    }

    public function edit($ID)
    {
        if($user = $this->userRepository->find($ID))
        {
            $roles              = $this->roleRepository->all();
            $departments        = $this->departmentRepository->all();
            $userHasRoles       = $user->roles ? array_column(json_decode($user->roles, true), 'id') : [];
            $userHasDepartments = $user->departments ? array_column(json_decode($user->departments, true), 'id') : [];
    
            return view('user-management.user.edit', compact('roles', 'departments', 'user', 'userHasRoles', 'userHasDepartments'));    
        }

        return redirect()->back()->with('message',[
            'type'  => 'danger',
            'text'  => 'This user does not exist!',
        ]);

    } */

    public function store(StoreUser $request)
    {
        try{
            $user = $this->userRepository->store([
                'name'          => $request->name,
                'email'         => $request->email,
                'mobile'        => $request->mobile,
                'status'        => $request->status ?? 'accepted',
                'password'      => Hash::make($request->password)
            ]);
        
            $roles       = $request->roles       ?? [];
            $departments = $request->departments ?? [];
            
            $this->roleRepository->setRoleToMember($user, $roles);
            $this->departmentRepository->attachDepartment($user, $departments);
    
            // return $this->sendResponse(UserResource::collection($user), 'User created successfully');
            return $this->sendResponse($user, 'User created successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    public function update(int $ID, UpdateUser $request)
    {
        try{
            if($user = $this->userRepository->find($ID))
            {
                $this->userRepository->update($ID, [
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'status'        => $request->status,
                    'mobile'        => $request->mobile,
                ]);
            
                $roles       = $request->roles       ?? [];

                $departments = $request->departments ?? [];
                if(count($departments) == 1 && $departments[0] == null)
                {
                    $departments = []; 
                }
                //// IF WE WANT TO CHANGE PASSWORD
                ////////////////////////////////////////////////////////////
                if($request->password)
                {
                    $this->userRepository->update($ID, [
                        'password'       => Hash::make($request->password)
                    ]);
                }
                ////////////////////////////////////////////////////////////

                $this->roleRepository->syncRoleToUser($user, $roles);
                $this->departmentRepository->syncDepartments($user, $departments);
        
                // return $this->sendResponse(collect($user->with(['roles','departments'])->find($ID)), 'User Updated successfully');
                return $this->sendResponse($user, 'User updated successfully');
            }

            return $this->sendError('This user does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }

        
    }

    public function delete($ID)
    {
        try{
            if($user = $this->userRepository->find($ID))
            {
                //// soft delete
                $this->userRepository->update($ID, [
                    'status'    => 'deleted'
                ]);
                $user->delete();

                return $this->sendResponse($user, 'User deleted successfully');
            }

            return $this->sendError('This user does not exist');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }

    public function restoreBackUser(int $ID)
    {
        try{
            if($this->userRepository->restoreUser($ID))
            {
                $user = $this->userRepository->update($ID, [
                    'status'    => 'accepted',
                ]);

                return $this->sendResponse($this->userRepository->find($ID), 'User restored successfully');
            }

            return $this->sendError('This user does not exist');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }
}
