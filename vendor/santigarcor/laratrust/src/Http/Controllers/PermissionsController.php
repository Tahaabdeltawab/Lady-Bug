<?php

namespace Laratrust\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class PermissionsController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index()
    {
        return View::make('laratrust::panel.permissions.index', [
            'permissions' => $this->permissionModel::simplePaginate(10),
        ]);
    }

    public function create()
    {
        return View::make('laratrust::panel.edit', [
            'model' => null,
            'type' => 'permission',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:permissions,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission = $this->permissionModel::create($data);

        Session::flash('laratrust-success', 'Permission created successfully');
        return redirect(route('laratrust.permissions.index'));
    }

    public function show(Request $request, $id)
    {
        $permission = $this->permissionModel::query()
            ->findOrFail($id);

        return View::make('laratrust::panel.permissions.show', ['permission' => $permission]);
    }

    public function edit($id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        return View::make('laratrust::panel.edit', [
            'model' => $permission,
            'type' => 'permission',
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');
        return redirect(route('laratrust.permissions.index'));
    }


    public function destroy($id)
    {
        $rolesAssignedToPermission = DB::table(Config::get('laratrust.tables.permission_role'))
            ->where(Config::get('laratrust.foreign_keys.permission'), $id)
            ->count();
        $usersAssignedToPermission = DB::table(Config::get('laratrust.tables.permission_user'))
            ->where(Config::get('laratrust.foreign_keys.permission'), $id)
            ->count();
        $permission = $this->permissionModel::findOrFail($id);

        if ($rolesAssignedToPermission > 0) {
            Session::flash('laratrust-warning', 'permission is attached to one or more roles. It can not be deleted');
        } elseif ($usersAssignedToPermission > 0) {
            Session::flash('laratrust-warning', 'permission is attached to one or more users. It can not be deleted');
        } else {
            Session::flash('laratrust-success', 'permission deleted successfully');
            $this->permissionModel::destroy($id);
        }

        return redirect(route('laratrust.permissions.index'));
    }
}
