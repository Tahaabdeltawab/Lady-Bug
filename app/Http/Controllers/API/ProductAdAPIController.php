<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAdAPIRequest;
use App\Http\Requests\API\UpdateProductAdAPIRequest;
use App\Models\ProductAd;
use App\Repositories\ProductAdRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductAdResource;
use Response;

/**
 * Class ProductAdController
 * @package App\Http\Controllers\API
 */

class ProductAdAPIController extends AppBaseController
{
    /** @var  ProductAdRepository */
    private $productAdRepository;

    public function __construct(ProductAdRepository $productAdRepo)
    {
        $this->productAdRepository = $productAdRepo;
    }

    /**
     * Display a listing of the ProductAd.
     * GET|HEAD /productAds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $productAds = $this->productAdRepository->all(
            $request->except(['page', 'perPage']),
            $request->get('page') ?? 1,
            $request->get('perPage')
        );

        return $this->sendResponse(['all' => ProductAdResource::collection($productAds['all']), 'meta' => $productAds['meta']], 'Product Ads retrieved successfully');
    }

    /**
     * Store a newly created ProductAd in storage.
     * POST /productAds
     *
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if($ads = $request->ads){
            foreach ($ads as $t) {
                $this->productAdRepository->create($t);
            }
            return $this->sendSuccess('ads created successfully');
        }

        $input = $request->validated();

        $productAd = $this->productAdRepository->create($input);

        return $this->sendResponse(new ProductAdResource($productAd), 'Product Ad saved successfully');
    }

    /**
     * Display the specified ProductAd.
     * GET|HEAD /productAds/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductAd $productAd */
        $productAd = $this->productAdRepository->find($id);

        if (empty($productAd)) {
            return $this->sendError('Product Ad not found');
        }

        return $this->sendResponse(new ProductAdResource($productAd), 'Product Ad retrieved successfully');
    }

    /**
     * Update the specified ProductAd in storage.
     * PUT/PATCH /productAds/{id}
     *
     * @param int $id
     * @param UpdateProductAdAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductAdAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ProductAd $productAd */
        $productAd = $this->productAdRepository->find($id);

        if (empty($productAd)) {
            return $this->sendError('Product Ad not found');
        }

        $productAd = $this->productAdRepository->update($input, $id);

        return $this->sendResponse(new ProductAdResource($productAd), 'ProductAd updated successfully');
    }

    /**
     * Remove the specified ProductAd from storage.
     * DELETE /productAds/{id}
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
        /** @var ProductAd $productAd */
        $productAd = $this->productAdRepository->find($id);

        if (empty($productAd)) {
            return $this->sendError('Product Ad not found');
        }

        $productAd->delete();

        return $this->sendSuccess('Product Ad deleted successfully');
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
