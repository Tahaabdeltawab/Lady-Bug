<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeExtrasAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeExtrasAPIRequest;
use App\Models\FarmedTypeExtras;
use App\Repositories\FarmedTypeExtrasRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeExtrasResource;
use Response;

/**
 * Class FarmedTypeExtrasController
 * @package App\Http\Controllers\API
 */

class FarmedTypeExtrasAPIController extends AppBaseController
{
    /** @var  FarmedTypeExtrasRepository */
    private $farmedTypeExtrasRepository;

    public function __construct(FarmedTypeExtrasRepository $farmedTypeExtrasRepo)
    {
        $this->farmedTypeExtrasRepository = $farmedTypeExtrasRepo;
    }

    /**
     * Display a listing of the FarmedTypeExtras.
     * GET|HEAD /farmedTypeExtras
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(FarmedTypeExtrasResource::collection($farmedTypeExtras), 'Farmed Type Extras retrieved successfully');
    }

    /**
     * Store a newly created FarmedTypeExtras in storage.
     * POST /farmedTypeExtras
     *
     * @param CreateFarmedTypeExtrasAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmedTypeExtrasAPIRequest $request)
    {
        $input = $request->all();

        $farmedTypeExtras = $this->farmedTypeExtrasRepository->create($input);

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'Farmed Type Extras saved successfully');
    }

    /**
     * Display the specified FarmedTypeExtras.
     * GET|HEAD /farmedTypeExtras/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'Farmed Type Extras retrieved successfully');
    }

    /**
     * Update the specified FarmedTypeExtras in storage.
     * PUT/PATCH /farmedTypeExtras/{id}
     *
     * @param int $id
     * @param UpdateFarmedTypeExtrasAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmedTypeExtrasAPIRequest $request)
    {
        $input = $request->all();

        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        $farmedTypeExtras = $this->farmedTypeExtrasRepository->update($input, $id);

        return $this->sendResponse(new FarmedTypeExtrasResource($farmedTypeExtras), 'FarmedTypeExtras updated successfully');
    }

    /**
     * Remove the specified FarmedTypeExtras from storage.
     * DELETE /farmedTypeExtras/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FarmedTypeExtras $farmedTypeExtras */
        $farmedTypeExtras = $this->farmedTypeExtrasRepository->find($id);

        if (empty($farmedTypeExtras)) {
            return $this->sendError('Farmed Type Extras not found');
        }

        $farmedTypeExtras->delete();

        return $this->sendSuccess('Farmed Type Extras deleted successfully');
    }
}
