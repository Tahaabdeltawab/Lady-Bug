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
    }

    /**
     * Display a listing of the Ac.
     * GET|HEAD /acs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $acs = $this->acRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AcResource::collection($acs), 'Acs retrieved successfully');
    }

    public function who_classes($value = null)
    {
        $who_classes =  [
            ['value' => 'organic', 'name' => app()->getLocale() == 'ar' ?  'عضوي' : 'Organic'],
            ['value' => 'inorganic', 'name' => app()->getLocale() == 'ar' ?  'غير عضوي' : 'Inorganic'],
            ['value' => 'rejected', 'name' => app()->getLocale() == 'ar' ?  'مرفوض' : 'Rejected']
        ];
        if($value)
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
        /** @var Ac $ac */
        $ac = $this->acRepository->find($id);

        if (empty($ac)) {
            return $this->sendError('Ac not found');
        }

        $ac->delete();

        return $this->sendSuccess('Ac deleted successfully');
    }
}
