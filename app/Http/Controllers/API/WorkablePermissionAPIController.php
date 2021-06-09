<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWorkablePermissionAPIRequest;
use App\Http\Requests\API\UpdateWorkablePermissionAPIRequest;
use App\Models\WorkablePermission;
use App\Repositories\WorkablePermissionRepository;
use Illuminate\Http\Request;
use App\Repositories\WorkableTypeRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\WorkablePermissionResource;
use Response;

/**
 * Class WorkablePermissionController
 * @package App\Http\Controllers\API
 */

class WorkablePermissionAPIController extends AppBaseController
{
   /** @var  WorkablePermissionRepository */
   private $workablePermissionRepository;
   private $workableTypeRepository;

   public function __construct(WorkablePermissionRepository $workablePermissionRepo, WorkableTypeRepository $workableTypeRepo)
   {
       $this->workablePermissionRepository = $workablePermissionRepo;
       $this->workableTypeRepository = $workableTypeRepo;
   }


    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/workablePermissions",
     *      summary="Get a listing of the WorkablePermissions.",
     *      tags={"WorkablePermission"},
     *      description="Get all WorkablePermissions",
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
     *                  @SWG\Items(ref="#/definitions/WorkablePermission")
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
            $workablePermissions = $this->workablePermissionRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );

            return $this->sendResponse(['all' => WorkablePermissionResource::collection($workablePermissions)], 'Workable Permissions retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param CreateWorkablePermissionAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/workablePermissions",
     *      summary="Store a newly created WorkablePermission in storage",
     *      tags={"WorkablePermission"},
     *      description="Store WorkablePermission",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkablePermission that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkablePermission")
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
     *                  ref="#/definitions/WorkablePermission"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateWorkablePermissionAPIRequest $request)
    {
        try{
            $input = $request->validated();

            $workablePermission = $this->workablePermissionRepository->save_localized($input);

            return $this->sendResponse(new WorkablePermissionResource($workablePermission), 'Workable Permission saved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/workablePermissions/{id}",
     *      summary="Display the specified WorkablePermission",
     *      tags={"WorkablePermission"},
     *      description="Get WorkablePermission",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkablePermission",
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
     *                  ref="#/definitions/WorkablePermission"
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
            /** @var WorkablePermission $workablePermission */
            $workablePermission = $this->workablePermissionRepository->find($id);

            if (empty($workablePermission)) {
                return $this->sendError('Workable Permission not found');
            }

            return $this->sendResponse(new WorkablePermissionResource($workablePermission), 'Workable Permission retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @param UpdateWorkablePermissionAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/workablePermissions/{id}",
     *      summary="Update the specified WorkablePermission in storage",
     *      tags={"WorkablePermission"},
     *      description="Update WorkablePermission",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkablePermission",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="WorkablePermission that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/WorkablePermission")
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
     *                  ref="#/definitions/WorkablePermission"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateWorkablePermissionAPIRequest $request)
    {
        try{
            $input = $request->validated();

            /** @var WorkablePermission $workablePermission */
            $workablePermission = $this->workablePermissionRepository->find($id);

            if (empty($workablePermission)) {
                return $this->sendError('Workable Permission not found');
            }

            $workablePermission = $this->workablePermissionRepository->save_localized($input, $id);

            return $this->sendResponse(new WorkablePermissionResource($workablePermission), 'WorkablePermission updated successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/workablePermissions/{id}",
     *      summary="Remove the specified WorkablePermission from storage",
     *      tags={"WorkablePermission"},
     *      description="Delete WorkablePermission",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of WorkablePermission",
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
        /** @var WorkablePermission $workablePermission */
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            return $this->sendError('Workable Permission not found');
        }

        $workablePermission->delete();

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
        $workableTypes       = $this->workableTypeRepository->all();
        return view('workable_permissions.create', compact('workableTypes'));
    }

    public function edit($id)
    {
        $workablePermission = $this->workablePermissionRepository->find($id);

        if (empty($workablePermission)) {
            Flash::error('Workable Permission not found');

            return redirect(route('workablePermissions.index'));
        }
        $workableTypes       = $this->workableTypeRepository->all();

        return view('workable_permissions.edit', compact('workablePermission', 'workableTypes'));
    }
}
