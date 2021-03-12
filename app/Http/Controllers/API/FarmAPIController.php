<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmAPIRequest;
use App\Http\Requests\API\UpdateFarmAPIRequest;
use App\Repositories\FarmRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkableRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmResource;
use Illuminate\Http\Request;
use Flash;
use Response;


class FarmAPIController extends AppBaseController
{
    
    private $farmRepository;
    private $userRepository;
    private $workableRepository;

    public function __construct(FarmRepository $farmRepo, UserRepository $userRepo, WorkableRepository $workableRepo)
    {
        $this->farmRepository = $farmRepo;
        $this->userRepository = $userRepo;
        $this->workableRepository = $workableRepo;
    }


    public function index(Request $request)
    {
        try{
            $farms = $this->farmRepository->all();
            return $this->sendResponse(['all' => FarmResource::collection($farms)], 'Farms retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    public function store(CreateFarmAPIRequest $request)
    {
        try{

            //create the farm
            $input = $request->validated();

            return response()->json($input);

            //



            $farm = $this->farmRepository->save_localized($input);
    
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
    
            return $this->sendResponse(new FarmResource($farm), 'Farm saved successfully');

        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }

    }


    public function show($id)
    {
        try{
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            return $this->sendResponse(new FarmResource($farm), 'Farm retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }


    public function update($id, UpdateFarmAPIRequest $request)
    {
        try{
            //update the farm
            $input = $request->validated();

            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            $farm = $this->farmRepository->save_localized($input, $id);

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

            return $this->sendResponse(new FarmResource($farm), 'Farm updated successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }

    }

    public function destroy($id)
    {
        try{
            /** @var Farm $farm */
            $farm = $this->farmRepository->find($id);

            if (empty($farm)) {
                return $this->sendError('Farm not found');
            }

            $farm->delete();

            return $this->sendSuccess('Farm deleted successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500); 
        }
    }




    public function create()
    {
        // $workers = resolve($this->userRepository->model())->where('id','!=',auth()->id())->get();
        $workers = $this->userRepository->all();
        $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
            $q->where('name', 'App\Models\Farm');
        })->get();
        return view('farms.create', compact('workers', 'workable_roles'));
    }


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
