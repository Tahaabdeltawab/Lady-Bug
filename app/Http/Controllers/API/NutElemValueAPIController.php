<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateNutElemValueAPIRequest;
use App\Http\Requests\API\UpdateNutElemValueAPIRequest;
use App\Models\NutElemValue;
use App\Repositories\NutElemValueRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\NutElemValueResource;
use Response;

/**
 * Class NutElemValueController
 * @package App\Http\Controllers\API
 */

class NutElemValueAPIController extends AppBaseController
{
    /** @var  NutElemValueRepository */
    private $nutElemValueRepository;

    public function __construct(NutElemValueRepository $nutElemValueRepo)
    {
        $this->nutElemValueRepository = $nutElemValueRepo;
    }

    /**
     * Display a listing of the NutElemValue.
     * GET|HEAD /nutElemValues
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $nutElemValues = $this->nutElemValueRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(NutElemValueResource::collection($nutElemValues), 'Nut Elem Values retrieved successfully');
    }

    /**
     * Store a newly created NutElemValue in storage.
     * POST /nutElemValues
     *
     * @param CreateNutElemValueAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateNutElemValueAPIRequest $request)
    {
        $input = $request->all();

        $nutElemValue = $this->nutElemValueRepository->create($input);

        return $this->sendResponse(new NutElemValueResource($nutElemValue), 'Nut Elem Value saved successfully');
    }

    /**
     * Display the specified NutElemValue.
     * GET|HEAD /nutElemValues/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var NutElemValue $nutElemValue */
        $nutElemValue = $this->nutElemValueRepository->find($id);

        if (empty($nutElemValue)) {
            return $this->sendError('Nut Elem Value not found');
        }

        return $this->sendResponse(new NutElemValueResource($nutElemValue), 'Nut Elem Value retrieved successfully');
    }

    /**
     * Update the specified NutElemValue in storage.
     * PUT/PATCH /nutElemValues/{id}
     *
     * @param int $id
     * @param UpdateNutElemValueAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNutElemValueAPIRequest $request)
    {
        $input = $request->all();

        /** @var NutElemValue $nutElemValue */
        $nutElemValue = $this->nutElemValueRepository->find($id);

        if (empty($nutElemValue)) {
            return $this->sendError('Nut Elem Value not found');
        }

        $nutElemValue = $this->nutElemValueRepository->update($input, $id);

        return $this->sendResponse(new NutElemValueResource($nutElemValue), 'NutElemValue updated successfully');
    }

    /**
     * Remove the specified NutElemValue from storage.
     * DELETE /nutElemValues/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var NutElemValue $nutElemValue */
        $nutElemValue = $this->nutElemValueRepository->find($id);

        if (empty($nutElemValue)) {
            return $this->sendError('Nut Elem Value not found');
        }

        $nutElemValue->delete();

        return $this->sendSuccess('Nut Elem Value deleted successfully');
    }
}
