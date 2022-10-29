<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductTypeAPIRequest;
use App\Http\Requests\API\UpdateProductTypeAPIRequest;
use App\Models\ProductType;
use App\Repositories\ProductTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductTypeResource;
use Response;

/**
 * Class ProductTypeController
 * @package App\Http\Controllers\API
 */

class ProductTypeAPIController extends AppBaseController
{
    /** @var  ProductTypeRepository */
    private $productTypeRepository;

    public function __construct(ProductTypeRepository $productTypeRepo)
    {
        $this->productTypeRepository = $productTypeRepo;
    }

    /**
     * Display a listing of the ProductType.
     * GET|HEAD /productTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $productTypes = $this->productTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ProductTypeResource::collection($productTypes), 'Product Types retrieved successfully');
    }

    /**
     * Store a newly created ProductType in storage.
     * POST /productTypes
     *
     * @param CreateProductTypeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductTypeAPIRequest $request)
    {
        $input = $request->validated();

        $productType = $this->productTypeRepository->create($input);

        return $this->sendResponse(new ProductTypeResource($productType), 'Product Type saved successfully');
    }

    /**
     * Display the specified ProductType.
     * GET|HEAD /productTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductType $productType */
        $productType = $this->productTypeRepository->find($id);

        if (empty($productType)) {
            return $this->sendError('Product Type not found');
        }

        return $this->sendResponse(new ProductTypeResource($productType), 'Product Type retrieved successfully');
    }

    /**
     * Update the specified ProductType in storage.
     * PUT/PATCH /productTypes/{id}
     *
     * @param int $id
     * @param UpdateProductTypeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductTypeAPIRequest $request)
    {
        $input = $request->validated();

        /** @var ProductType $productType */
        $productType = $this->productTypeRepository->find($id);

        if (empty($productType)) {
            return $this->sendError('Product Type not found');
        }

        $productType = $this->productTypeRepository->update($input, $id);

        return $this->sendResponse(new ProductTypeResource($productType), 'ProductType updated successfully');
    }

    /**
     * Remove the specified ProductType from storage.
     * DELETE /productTypes/{id}
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
        /** @var ProductType $productType */
        $productType = $this->productTypeRepository->find($id);

        if (empty($productType)) {
            return $this->sendError('Product Type not found');
        }

        $productType->delete();

        return $this->sendSuccess('Product Type deleted successfully');
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
