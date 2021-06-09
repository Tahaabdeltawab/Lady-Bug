<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWorkableRoleAPIRequest;
use App\Http\Requests\API\UpdateWorkableRoleAPIRequest;
use App\Repositories\WorkableRoleRepository;
use App\Repositories\WorkablePermissionRepository;
use App\Repositories\WorkableTypeRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WorkableRoleResource;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\WorkablePermission;

/**
 * Class WorkableRoleController
 * @package App\Http\Controllers\API
 */

class WorkableRoleAPIController extends AppBaseController
{
    /** @var  WorkableRoleRepository */
    private $workableRoleRepository;
    private $workablePermissionRepository;
    private $workableTypeRepository;

    public function __construct(WorkableRoleRepository $workableRoleRepo, WorkablePermissionRepository $workablePermissionRepo, WorkableTypeRepository $workableTypeRepo)
    {
        $this->workableRoleRepository       = $workableRoleRepo;
        $this->workablePermissionRepository = $workablePermissionRepo;
        $this->workableTypeRepository = $workableTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/workableRoles",
     *      summary="Get a listing of the WorkableRoles.",
     *      tags={"WorkableRole"},
     *      description="Get all WorkableRoles",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/WorkableRole")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        try{
            $workableRoles = $this->workableRoleRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );

            return $this->sendResponse(['all' => WorkableRoleResource::collection($workableRoles)], 'Workable Roles retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function permissions_by_workable_type()
    {
        try{
            $workable_type_id = request()->workable_type_id;
            $workable_type_name = request()->workable_type_name;
            // $workableTypes       = $this->workableTypeRepository->all();
            // $workablePermissions = $this->workablePermissionRepository->all();
            // $workablePermissions = resolve($this->workablePermissionRepository->model())->where('workable_type_id',$workable_type_id)->get();
            $workablePermissions = resolve($this->workablePermissionRepository->model())->whereHas('workable_type', function($q) use($workable_type_name) {
                return $q->where('name', $workable_type_name);
            })->get();
            return $this->sendResponse($workablePermissions, 'Workable Permissions retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param CreateWorkableRoleAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/workableRoles",
     *      summary="Store a newly created WorkableRole in storage",
     *      tags={"WorkableRole"},
     *      description="Store WorkableRole",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkableRole that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkableRole")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/WorkableRole"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateWorkableRoleAPIRequest $request)
    {
        try{
            $input = $request->validated();

            $workableRole = $this->workableRoleRepository->save_localized($input);

            if(! empty($request->workablePermissions))
            {
                $workableRole->workable_permissions()->sync($request->workablePermissions);
            }

            return $this->sendResponse(new WorkableRoleResource($workableRole), 'Workable Role saved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/workableRoles/{id}",
     *      summary="Display the specified WorkableRole",
     *      tags={"WorkableRole"},
     *      description="Get WorkableRole",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableRole",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/WorkableRole"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        try{
            /** @var WorkableRole $workableRole */
            $workableRole = $this->workableRoleRepository->find($id);

            if (empty($workableRole)) {
                return $this->sendError('Workable Role not found');
            }

            return $this->sendResponse(new WorkableRoleResource($workableRole), 'Workable Role retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @param UpdateWorkableRoleAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/workableRoles/{id}",
     *      summary="Update the specified WorkableRole in storage",
     *      tags={"WorkableRole"},
     *      description="Update WorkableRole",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableRole",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkableRole that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkableRole")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/WorkableRole"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateWorkableRoleAPIRequest $request)
    {
        try{
            $input = $request->validated();

            /** @var WorkableRole $workableRole */
            $workableRole = $this->workableRoleRepository->find($id);

            if (empty($workableRole)) {
                return $this->sendError('Workable Role not found');
            }

            $workableRole = $this->workableRoleRepository->save_localized($input, $id);
            $workablePermissions = $request->workablePermissions ?? [];
            $workableRole->workable_permissions()->sync($workablePermissions);

            return $this->sendResponse(new WorkableRoleResource($workableRole), 'WorkableRole updated successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/workableRoles/{id}",
     *      summary="Remove the specified WorkableRole from storage",
     *      tags={"WorkableRole"},
     *      description="Delete WorkableRole",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkableRole",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        try
        {
        /** @var WorkableRole $workableRole */
        $workableRole = $this->workableRoleRepository->find($id);

        if (empty($workableRole)) {
            return $this->sendError('Workable Role not found');
        }

        $workableRole->delete();

          return $this->sendSuccess('Model deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }


    public function create()
    {
        $workable_type = request()->get('workable_type');
        $workablePermissions = $this->workablePermissionRepository->all();
        $workableTypes       = $this->workableTypeRepository->all();
        return view('workable_roles.create', compact('workablePermissions','workableTypes'));
    }


    public function edit($id)
    {
        if($workableRole = $this->workableRoleRepository->find($id))
        {
            $workableTypes       = $this->workableTypeRepository->all();

            $workablePermissions        = $this->workablePermissionRepository->all();

          /*   $workablePermissions = resolve($this->workablePermissionRepository->model())->whereHas('workable_type', function($q) use($workableRole) {

                return $q->where('name', $workableRole->workable_type->name);

            })->get(); */

            $wroleHaswPermissions = array_column(json_decode($workableRole->workable_permissions, true), 'id');

            return view('workable_roles.edit', compact('workableRole', 'workablePermissions', 'wroleHaswPermissions', 'workableTypes'));
        }

        Flash::error('Workable Role not found');
        return redirect(route('workableRoles.index'));
    }
}
