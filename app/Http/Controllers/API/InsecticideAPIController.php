<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateInsecticideAPIRequest;
use App\Http\Requests\API\UpdateInsecticideAPIRequest;
use App\Models\Insecticide;
use App\Repositories\InsecticideRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AcXsResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\InsecticideResource;
use App\Models\Ac;
use App\Models\Country;
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

    public function getRelations()
    {
        return $this->sendResponse([
            'acs' => AcXsResource::collection(Ac::get(['id','name'])),
            'countries' => CountryResource::collection(Country::all()),
            'dosage_forms' => [
                ['value' => 'liquid', 'name' => app()->getLocale()=='ar' ?  'سائل' : 'liquid'],
                ['value' => 'powder', 'name' => app()->getLocale()=='ar' ?  'بودرة' : 'powder'],
            ]
        ], 'insecticide relations retrieved successfully');
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
        $input = $request->validated();

        $insecticide = $this->insecticideRepository->create($input);
        $insecticide->acs()->attach($input['acs']);

        if($assets = $request->file('assets'))
        {
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'insecticide');
                $insecticide->assets()->create($oneasset);
            }
        }

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
        $input = $request->validated();

        /** @var Insecticide $insecticide */
        $insecticide = $this->insecticideRepository->find($id);

        if (empty($insecticide)) {
            return $this->sendError('Insecticide not found');
        }

        $insecticide = $this->insecticideRepository->update($input, $id);
        $insecticide->acs()->sync($input['acs']);

        if($assets = $request->file('assets'))
        {
            foreach ($insecticide->assets as $ass) {
                $ass->delete();
            }
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'insecticide');
                $insecticide->assets()->create($oneasset);
            }
        }

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
        try
        {
        /** @var Insecticide $insecticide */
        $insecticide = $this->insecticideRepository->find($id);

        if (empty($insecticide)) {
            return $this->sendError('Insecticide not found');
        }

        $insecticide->delete();

        return $this->sendSuccess('Insecticide deleted successfully');
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
