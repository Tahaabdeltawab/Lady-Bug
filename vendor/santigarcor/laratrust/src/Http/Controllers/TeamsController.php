<?php

namespace Laratrust\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class TeamsController
{
    protected $teamModel;

    public function __construct()
    {
        $this->teamModel = Config::get('laratrust.models.team');
    }

    public function index()
    {
        return View::make('laratrust::panel.teams.index', [
            'teams' => $this->teamModel::simplePaginate(10),
        ]);
    }

    public function create()
    {
        return View::make('laratrust::panel.edit', [
            'model' => null,
            'type' => 'team',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:teams,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $team = $this->teamModel::create($data);

        Session::flash('laratrust-success', 'team created successfully');
        return redirect(route('laratrust.teams.index'));
    }

    public function show(Request $request, $id)
    {
        $team = $this->teamModel::query()
            ->findOrFail($id);

        return View::make('laratrust::panel.teams.show', ['team' => $team]);
    }

    public function edit($id)
    {
        $team = $this->teamModel::findOrFail($id);

        return View::make('laratrust::panel.edit', [
            'model' => $team,
            'type' => 'team',
        ]);
    }

    public function update(Request $request, $id)
    {
        $team = $this->teamModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $team->update($data);

        Session::flash('laratrust-success', 'team updated successfully');
        return redirect(route('laratrust.teams.index'));
    }


    public function destroy($id)
    {
        $team = $this->teamModel::findOrFail($id);
        Session::flash('laratrust-success', 'team deleted successfully');
        $this->teamModel::destroy($id);

        return redirect(route('laratrust.teams.index'));
    }
}
