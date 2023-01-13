<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCountryAPIRequest;
use App\Http\Requests\API\UpdateCountryAPIRequest;
use App\Models\Country;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CountryResource;
use Response;

/**
 * Class CountryController
 * @package App\Http\Controllers\API
 */

class CountryAPIController extends AppBaseController
{
    /** @var  CountryRepository */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepo)
    {
        $this->countryRepository = $countryRepo;
    }

    /**
     * Display a listing of the Country.
     * GET|HEAD /countries
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $countries = $this->countryRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => CountryResource::collection($countries['all']), 'meta' => $countries['meta']], 'Countries retrieved successfully');
    }

    /**
     * Store a newly created Country in storage.
     * POST /countries
     *
     * @param CreateCountryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCountryAPIRequest $request)
    {
        $input = $request->validated();

        $country = $this->countryRepository->create($input);

        return $this->sendResponse(new CountryResource($country), 'Country saved successfully');
    }

    /**
     * Display the specified Country.
     * GET|HEAD /countries/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Country $country */
        $country = $this->countryRepository->find($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        return $this->sendResponse(new CountryResource($country), 'Country retrieved successfully');
    }

    /**
     * Update the specified Country in storage.
     * PUT/PATCH /countries/{id}
     *
     * @param int $id
     * @param UpdateCountryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCountryAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Country $country */
        $country = $this->countryRepository->find($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        $country = $this->countryRepository->update($input, $id);

        return $this->sendResponse(new CountryResource($country), 'Country updated successfully');
    }

    /**
     * Remove the specified Country from storage.
     * DELETE /countries/{id}
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
        /** @var Country $country */
        $country = $this->countryRepository->find($id);

        if (empty($country)) {
            return $this->sendError('Country not found');
        }

        $country->delete();

        return $this->sendSuccess('Country deleted successfully');
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
