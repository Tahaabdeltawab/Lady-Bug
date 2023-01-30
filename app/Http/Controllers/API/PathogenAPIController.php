<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePathogenAPIRequest;
use App\Http\Requests\API\UpdatePathogenAPIRequest;
use App\Models\Pathogen;
use App\Repositories\PathogenRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PathogenResource;
use App\Models\AcPaGrowthStage;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class PathogenController
 * @package App\Http\Controllers\API
 */

class PathogenAPIController extends AppBaseController
{
    /** @var  PathogenRepository */
    private $pathogenRepository;

    public function __construct(PathogenRepository $pathogenRepo)
    {
        $this->pathogenRepository = $pathogenRepo;

        $this->middleware('permission:pathogens.index')->only(['admin_index']);
        $this->middleware('permission:pathogens.show')->only(['admin_show']);
        $this->middleware('permission:pathogens.store')->only(['store']);
        $this->middleware('permission:pathogens.update')->only(['update']);
        $this->middleware('permission:pathogens.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the Pathogen.
     * GET|HEAD /pathogens
     *
     * @param Request $request
     * @return Response
     */
    public function admin_index(Request $request)
    {
        return $this->index($request);
    }

    public function admin_show($id)
    {
        return $this->show($id);
    }

    public function index(Request $request)
    {
        $pathogens = $this->pathogenRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => PathogenResource::collection($pathogens['all']), 'meta' => $pathogens['meta']], 'Pathogens retrieved successfully');
    }

    /**
     * Store a newly created Pathogen in storage.
     * POST /pathogens
     *
     * @param CreatePathogenAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePathogenAPIRequest $request)
    {
        $input = $request->validated();

        $pathogen = $this->pathogenRepository->create($input);

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen saved successfully');
    }

    /**
     * Display the specified Pathogen.
     * GET|HEAD /pathogens/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Pathogen $pathogen */
        $pathogen = $this->pathogenRepository->find($id);

        if (empty($pathogen)) {
            return $this->sendError('Pathogen not found');
        }

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen retrieved successfully');
    }

    /**
     * Update the specified Pathogen in storage.
     * PUT/PATCH /pathogens/{id}
     *
     * @param int $id
     * @param UpdatePathogenAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePathogenAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Pathogen $pathogen */
        $pathogen = $this->pathogenRepository->find($id);

        if (empty($pathogen)) {
            return $this->sendError('Pathogen not found');
        }

        $pathogen = $this->pathogenRepository->update($input, $id);

        return $this->sendResponse(new PathogenResource($pathogen), 'Pathogen updated successfully');
    }

    /**
     * Remove the specified Pathogen from storage.
     * DELETE /pathogens/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try{
            /** @var Pathogen $pathogen */
            $pathogen = $this->pathogenRepository->find($id);

            if (empty($pathogen)) {
                return $this->sendError('Pathogen not found');
            }
            DB::beginTransaction();
            foreach($pathogen->pathogenGrowthStages as $stage){
                foreach(AcPaGrowthStage::where('pathogen_growth_stage_id', $stage->id)->get() as $acpa){
                    foreach($acpa->assets as $ass){
                        $ass->delete();
                    }
                    $acpa->delete();
                }
                foreach($stage->assets as $ass){
                    $ass->delete();
                }
                $stage->delete();
            }
            $pathogen->delete();
            DB::commit();
            return $this->sendSuccess('Pathogen deleted successfully');
        }catch(\Throwable $th)
        {
            DB::rollBack();
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
