<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFarmRequest;
use App\Http\Requests\UpdateFarmRequest;
use App\Repositories\FarmRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkableRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FarmController extends AppBaseController
{
    /** @var  FarmRepository */
    private $farmRepository;
    private $userRepository;
    private $workableRepository;

    public function __construct(FarmRepository $farmRepo, UserRepository $userRepo, WorkableRepository $workableRepo)
    {
        $this->farmRepository = $farmRepo;
        $this->userRepository = $userRepo;
        $this->workableRepository = $workableRepo;
    }

    /**
     * Display a listing of the Farm.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $farms = $this->farmRepository->paginate(10);

        return view('farms.index')
            ->with('farms', $farms);
    }

    /**
     * Show the form for creating a new Farm.
     *
     * @return Response
     */
    public function create()
    {
        // $workers = resolve($this->userRepository->model())->where('id','!=',auth()->id())->get();
        $workers = $this->userRepository->all();
        $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
            $q->where('name', 'App\Models\Farm');
        })->get();
        return view('farms.create', compact('workers', 'workable_roles'));
    }

    /**
     * Store a newly created Farm in storage.
     *
     * @param CreateFarmRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmRequest $request)
    {
        try{

            //create the farm
            $input = $request->all();
            $farm = $this->farmRepository->create($input);
    
            //set the farm workers adding the creator to them
            $workers = $request->workers ?? [];
            $workers[] = auth()->id();
            $farm->workers()->sync($workers);
    
            //set the farm workers roles being the creator the admin
            $admin_role_id = \App\Models\WorkableRole::select('id')->where('name','admin')->whereHas('workable_type', function($q){
                $q->where('name', 'App\Models\Farm');
            })->first()->id;
    
            foreach($farm->workers as $farm_worker){
                $workable_roles[$farm_worker->id] = $request->{"workable_roles_".$farm_worker->id} ?? [];
                // if($farm_worker->id == auth()->id()){
                //     $workable_roles[$farm_worker->id] = [$admin_role_id];
                // }
                $workables[$farm_worker->id] = \App\Models\Workable::where([['worker_id',$farm_worker->id], ['workable_id',$farm->id], ['workable_type','App\Models\Farm']])
                                             ->first()->workable_roles()->sync($workable_roles[$farm_worker->id]);
            }
    
            Flash::success('Farm saved successfully.');
    
            return redirect(route('farms.index'));
        }catch(\Throwable $th){
            dd($th);
        }
    }

    /**
     * Display the specified Farm.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            Flash::error('Farm not found');

            return redirect(route('farms.index'));
        }

        return view('farms.show')->with('farm', $farm);
    }

    /**
     * Show the form for editing the specified Farm.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            Flash::error('Farm not found');

            return redirect(route('farms.index'));
        }

        // $workers = resolve($this->userRepository->model())->where('id','!=',auth()->id())->get();
        $workers = $this->userRepository->all();
        $workableHasWorkers = $farm->workers->pluck('id')->all();
        $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
            $q->where('name', 'App\Models\Farm');
        })->get();

        return view('farms.edit', compact('farm', 'workers', 'workableHasWorkers', 'workable_roles'));
    }

    /**
     * Update the specified Farm in storage.
     *
     * @param int $id
     * @param UpdateFarmRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmRequest $request)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            Flash::error('Farm not found');

            return redirect(route('farms.index'));
        }
        //update the farm
        $farm = $this->farmRepository->update($request->all(), $id);

        //set the farm workers adding the creator to them
        $workers = $request->workers ?? [];
        $workers[] = auth()->id();
        $farm->workers()->sync($workers);


        //set the farm workers roles being the creator the admin
        $admin_role_id = \App\Models\WorkableRole::select('id')->where('name','admin')->whereHas('workable_type', function($q){
            $q->where('name', 'App\Models\Farm');
        })->first()->id;

        foreach($farm->workers as $farm_worker){
            $workable_roles[$farm_worker->id] = $request->{"workable_roles_".$farm_worker->id} ?? [];
            // if($farm_worker->id == auth()->id()){
            //     $workable_roles[$farm_worker->id] = [$admin_role_id];
            // }
            $workables[$farm_worker->id] = \App\Models\Workable::where([['worker_id',$farm_worker->id], ['workable_id',$farm->id], ['workable_type','App\Models\Farm']])
                                         ->first()->workable_roles()->sync($workable_roles[$farm_worker->id]);
        }


        Flash::success('Farm updated successfully.');

        return redirect(route('farms.index'));
    }

    /**
     * Remove the specified Farm from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {
            Flash::error('Farm not found');

            return redirect(route('farms.index'));
        }

        $this->farmRepository->delete($id);

        Flash::success('Farm deleted successfully.');

        return redirect(route('farms.index'));
    }


   /*  public function edit_roles($id)
    {
        $farm = $this->farmRepository->find($id);

        if (empty($farm)) {

            Flash::error('Farm with this worker not found');

            return redirect(route('farms.index'));
        }

        $workers = $farm->workers;

        return view('farms.roles.edit', compact('farm', 'workers'));
    }


    public function update_roles($id, Request $request)
    {
        $workable = $this->workableRepository->find($id);

        if (empty($workable)) {

            Flash::error('Farm with this worker not found');

            return redirect(route('farms.index'));
        }

        $workable_roles = $request->workable_roles ?? [];

        $workable->workable_roles()->sync($workable_roles);

        Flash::success('Farm roles updated successfully.');

        return redirect(route('farms.index'));
    }
 */

}
