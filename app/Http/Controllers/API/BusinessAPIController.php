<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBusinessAPIRequest;
use App\Http\Requests\API\UpdateBusinessAPIRequest;
use App\Models\Business;
use App\Repositories\BusinessRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BusinessResource;
use Response;

/**
 * Class BusinessController
 * @package App\Http\Controllers\API
 */

class BusinessAPIController extends AppBaseController
{
    /** @var  BusinessRepository */
    private $businessRepository;

    public function __construct(BusinessRepository $businessRepo)
    {
        $this->businessRepository = $businessRepo;
    }

    /**
     * Display a listing of the Business.
     * GET|HEAD /businesses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $businesses = $this->businessRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BusinessResource::collection($businesses), 'Businesses retrieved successfully');
    }

    /**
     * Store a newly created Business in storage.
     * POST /businesses
     *
     * @param CreateBusinessAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessAPIRequest $request)
    {
        $input = $request->all();

        $business = $this->businessRepository->create($input);

        if($main_asset = $request->file('main_asset'))
        {
            $currentDate = Carbon::now()->toDateString();
            $assetname = 'business-main-'.$currentDate.'-'.uniqid().'.'.$main_asset->getClientOriginalExtension();
            $assetsize = $main_asset->getSize(); //size in bytes 1k = 1000bytes
            $assetmime = $main_asset->getClientMimeType();

            $path = $main_asset->storeAs('assets/businesses', $assetname, 's3');
            // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

            $url  = Storage::disk('s3')->url($path);

            $business->assets()->create([
                'asset_name'        => $assetname,
                'asset_url'         => $url,
                'asset_path'        => $path,
                'asset_size'        => $assetsize,
                'asset_mime'        => $assetmime,
            ]);
        }

        if($cover_asset = $request->file('cover_asset'))
        {
            $currentDate = Carbon::now()->toDateString();
            $assetname = 'business-cover-'.$currentDate.'-'.uniqid().'.'.$cover_asset->getClientOriginalExtension();
            $assetsize = $cover_asset->getSize(); //size in bytes 1k = 1000bytes
            $assetmime = $cover_asset->getClientMimeType();

            $path = $cover_asset->storeAs('assets/businesses', $assetname, 's3');
            // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

            $url  = Storage::disk('s3')->url($path);

            $product->assets()->create([
                'asset_name'        => $assetname,
                'asset_url'         => $url,
                'asset_path'        => $path,
                'asset_size'        => $assetsize,
                'asset_mime'        => $assetmime,
            ]);
        }

        return $this->sendResponse(new BusinessResource($business), 'Business saved successfully');
    }

    /**
     * Display the specified Business.
     * GET|HEAD /businesses/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        return $this->sendResponse(new BusinessResource($business), 'Business retrieved successfully');
    }

    /**
     * Update the specified Business in storage.
     * PUT/PATCH /businesses/{id}
     *
     * @param int $id
     * @param UpdateBusinessAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessAPIRequest $request)
    {
        $input = $request->all();

        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        $business = $this->businessRepository->update($input, $id);

        if($main_asset = $request->file('main_asset'))
        {
            $business->main_asset()->delete();
            $currentDate = Carbon::now()->toDateString();
            $assetname = 'business-main-'.$currentDate.'-'.uniqid().'.'.$main_asset->getClientOriginalExtension();
            $assetsize = $main_asset->getSize(); //size in bytes 1k = 1000bytes
            $assetmime = $main_asset->getClientMimeType();

            $path = $main_asset->storeAs('assets/businesses', $assetname, 's3');
            // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

            $url  = Storage::disk('s3')->url($path);

            $business->assets()->create([
                'asset_name'        => $assetname,
                'asset_url'         => $url,
                'asset_path'        => $path,
                'asset_size'        => $assetsize,
                'asset_mime'        => $assetmime,
            ]);
        }

        if($cover_asset = $request->file('cover_asset'))
        {
            $business->cover_asset()->delete();
            $currentDate = Carbon::now()->toDateString();
            $assetname = 'business-cover-'.$currentDate.'-'.uniqid().'.'.$cover_asset->getClientOriginalExtension();
            $assetsize = $cover_asset->getSize(); //size in bytes 1k = 1000bytes
            $assetmime = $cover_asset->getClientMimeType();

            $path = $cover_asset->storeAs('assets/businesses', $assetname, 's3');
            // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

            $url  = Storage::disk('s3')->url($path);

            $product->assets()->create([
                'asset_name'        => $assetname,
                'asset_url'         => $url,
                'asset_path'        => $path,
                'asset_size'        => $assetsize,
                'asset_mime'        => $assetmime,
            ]);
        }

        return $this->sendResponse(new BusinessResource($business), 'Business updated successfully');
    }

    /**
     * Remove the specified Business from storage.
     * DELETE /businesses/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Business $business */
        $business = $this->businessRepository->find($id);

        if (empty($business)) {
            return $this->sendError('Business not found');
        }

        $business->delete();

        return $this->sendSuccess('Business deleted successfully');
    }
}
