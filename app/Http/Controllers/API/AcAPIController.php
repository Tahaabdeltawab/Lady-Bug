<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAcAPIRequest;
use App\Http\Requests\API\UpdateAcAPIRequest;
use App\Models\Ac;
use App\Repositories\AcRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AcResource;
use Response;

/**
 * Class AcController
 * @package App\Http\Controllers\API
 */

class AcAPIController extends AppBaseController
{
    /** @var  AcRepository */
    private $acRepository;

    public function __construct(AcRepository $acRepo)
    {
        $this->acRepository = $acRepo;

        $this->middleware('permission:acs.index')->only(['admin_index']);
        $this->middleware('permission:acs.show')->only(['admin_show']);
        $this->middleware('permission:acs.store')->only(['store']);
        $this->middleware('permission:acs.update')->only(['update']);
        $this->middleware('permission:acs.destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the Ac.
     * GET|HEAD /acs
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
        $acs = $this->acRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => AcResource::collection($acs['all']), 'meta' => $acs['meta']], 'Acs retrieved successfully');
    }

    public function who_classes($value = null)
    {
        $who_classes =  [
            ['value' => 'organic', 'name' => app()->getLocale() == 'ar' ?  'عضوي' : 'Organic'],
            ['value' => 'inorganic', 'name' => app()->getLocale() == 'ar' ?  'غير عضوي' : 'Inorganic'],
            ['value' => 'rejected', 'name' => app()->getLocale() == 'ar' ?  'مرفوض' : 'Rejected']
        ];
        if($value !== null)
            return collect($who_classes)->firstWhere('value', $value);
        else
            return $who_classes;
    }

    public function getRelations()
    {
        return $this->sendResponse(['who_classes' => $this->who_classes()], 'relations retrieved');
    }

    /**
     * Store a newly created Ac in storage.
     * POST /acs
     *
     * @param CreateAcAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAcAPIRequest $request)
    {
        $input = $request->validated();

        $ac = $this->acRepository->create($input);

        return $this->sendResponse(new AcResource($ac), 'Ac saved successfully');
    }

    /**
     * Display the specified Ac.
     * GET|HEAD /acs/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Ac $ac */
        $ac = $this->acRepository->find($id);

        if (empty($ac)) {
            return $this->sendError('Ac not found');
        }

        return $this->sendResponse(new AcResource($ac), 'Ac retrieved successfully');
    }

    /**
     * Update the specified Ac in storage.
     * PUT/PATCH /acs/{id}
     *
     * @param int $id
     * @param UpdateAcAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAcAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Ac $ac */
        $ac = $this->acRepository->find($id);

        if (empty($ac)) {
            return $this->sendError('Ac not found');
        }

        $ac = $this->acRepository->update($input, $id);

        return $this->sendResponse(new AcResource($ac), 'Ac updated successfully');
    }

    /**
     * Remove the specified Ac from storage.
     * DELETE /acs/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
        /** @var Ac $ac */
        $ac = $this->acRepository->find($id);

        if (empty($ac)) {
            return $this->sendError('Ac not found');
        }

        $ac->delete();

        return $this->sendSuccess('Ac deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }
}
