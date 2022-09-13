<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInsecticideAPIRequest;
use App\Http\Requests\API\UpdateInsecticideAPIRequest;
use App\Models\Insecticide;
use App\Repositories\InsecticideRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\InsecticideResource;
use Response;

/**
 * Class InsecticideController
 * @package App\Http\Controllers\API
 */

class InsecticideAPIController extends AppBaseController
{
    /** @var  InsecticideRepository */
    private $insecticideRepository;

    public function __construct(InsecticideRepository $insecticideRepo)
    {
        $this->insecticideRepository = $insecticideRepo;
    }

    /**
     * Display a listing of the Insecticide.
     * GET|HEAD /insecticides
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $insecticides = $this->insecticideRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(InsecticideResource::collection($insecticides), 'Insecticides retrieved successfully');
    }

    /**
     * Store a newly created Insecticide in storage.
     * POST /insecticides
     *
     * @param CreateInsecticideAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInsecticideAPIRequest $request)
    {
        $input = $request->all();

        $insecticide = $this->insecticideRepository->create($input);

        return $this->sendResponse(new InsecticideResource($insecticide), 'Insecticide saved successfully');
    }

    /**
     * Display the specified Insecticide.
     * GET|HEAD /insecticides/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Insecticide $insecticide */
        $insecticide = $this->insecticideRepository->find($id);

        if (empty($insecticide)) {
            return $this->sendError('Insecticide not found');
        }

        return $this->sendResponse(new InsecticideResource($insecticide), 'Insecticide retrieved successfully');
    }

    /**
     * Update the specified Insecticide in storage.
     * PUT/PATCH /insecticides/{id}
     *
     * @param int $id
     * @param UpdateInsecticideAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInsecticideAPIRequest $request)
    {
        $input = $request->all();

        /** @var Insecticide $insecticide */
        $insecticide = $this->insecticideRepository->find($id);

        if (empty($insecticide)) {
            return $this->sendError('Insecticide not found');
        }

        $insecticide = $this->insecticideRepository->update($input, $id);

        return $this->sendResponse(new InsecticideResource($insecticide), 'Insecticide updated successfully');
    }

    /**
     * Remove the specified Insecticide from storage.
     * DELETE /insecticides/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Insecticide $insecticide */
        $insecticide = $this->insecticideRepository->find($id);

        if (empty($insecticide)) {
            return $this->sendError('Insecticide not found');
        }

        $insecticide->delete();

        return $this->sendSuccess('Insecticide deleted successfully');
    }
}