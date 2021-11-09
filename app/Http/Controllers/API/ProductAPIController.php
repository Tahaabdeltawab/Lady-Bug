<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\CityRepository;
use App\Repositories\FarmedTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\FarmedTypeResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */

class ProductAPIController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;
    private $farmedTypeRepository;
    private $cityRepository;

    public function __construct(ProductRepository $productRepo, CityRepository $cityRepo, FarmedTypeRepository $farmedTypeRepo)
    {
        $this->productRepository = $productRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->cityRepository = $cityRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/products",
     *      summary="Get a listing of the Products.",
     *      tags={"Product"},
     *      description="Get all Products",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Product")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        $user = auth()->user();

        return $this->sendResponse([  'unread_notifications_count' => $user->unreadNotifications->count(), 'all' => ProductResource::collection($products)], 'Products retrieved successfully');
    }


    public function search($query)
    {
        $products = Product::whereHas('translations', function($q) use($query)
        {
            $q->where('name','like', '%'.$query.'%' )->orWhere('description','like', '%'.$query.'%');
        })->get();

        return $this->sendResponse(['all' => ProductResource::collection($products)], 'Products retrieved successfully');
    }


    //  sell product
    public function toggle_sell_product($id)
    {
        try
        {
            $product = $this->productRepository->find($id);

            if (empty($product)) {
                return $this->sendError('Product not found');
            }

            if($product->seller_id != auth()->id())
            {
                return $this->sendError(__('Sorry, You are not the product seller'));
            }

            if($product->sold)
            {
                $do = false;
                $msg = 'Product unsold successfully';
            }
            else
            {
                $do = true;
                $msg = 'Product sold successfully';
            }

            $this->productRepository->update(['sold' => $do], $id);

            return $this->sendSuccess($msg);
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    
    // // // // // RATE // // // //

    public function rate(Request $request)
    {
        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'rating' => ['required', 'numeric', 'max:5', 'min:1'],
                    'product' => ['required', 'integer', 'exists:products,id']
                ]
            );

            if($validator->fails()){
                return $this->sendError(json_encode($validator->errors()), 422);
            }

            $product = $this->productRepository->find($request->product);

                $product->rateOnce($request->rating);
                return $this->sendSuccess("You have rated $product->name with $request->rating stars successfully");
        }
        catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    //products relations
    public function products_relations()
    {
        $cities = $this->cityRepository->all();
        $farmed_types = $this->farmedTypeRepository->all();

        return $this->sendResponse(
            [
                'cities' => CityResource::collection($cities),
                'farmed_types' => FarmedTypeResource::collection($farmed_types)
            ], 'Products relations retrieved successfully');
    }



    /**
     * @param CreateProductAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/products",
     *      summary="Store a newly created Product in storage",
     *      tags={"Product"},
     *      description="Store Product",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Product that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Product")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Product"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */


    public function store(/* CreatePostAPI */Request $request)
        {
            try
            {
                $validator = Validator::make($request->all(), [
                    'price'                         => 'required',
                    'description_ar_localized'      => 'required',
                    'description_en_localized'      => 'required',
                    'name_ar_localized'             => 'required|max:200',
                    'name_en_localized'             => 'required|max:200',
                    'farmed_type_id'                => 'required|exists:farmed_types,id',
                    'city_id'                       => 'required|exists:cities,id',
                    'district_id'                   => 'required|exists:districts,id',
                    'seller_mobile'                 => 'required|max:20',
                    'other_links'                   => 'nullable',
                    'internal_assets'               => ['nullable','array'],
                    'external_assets'               => ['nullable','array'],
                    'internal_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg'],
                    'external_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
                ]);

                // return $this->sendError(json_encode($request->file('assets')[0]->getMimeType()), 777);
                if($validator->fails()){
                    $errors = $validator->errors();

                    return $this->sendError(json_encode($errors), 888);
                }

                $data['price'] = $request->price;
                $data['seller_id'] = auth()->id();
                $data['description_ar_localized'] = $request->description_ar_localized;
                $data['description_en_localized'] = $request->description_en_localized;
                $data['name_ar_localized'] = $request->name_ar_localized;
                $data['name_en_localized'] = $request->name_en_localized;
                $data['farmed_type_id'] = $request->farmed_type_id;
                $data['city_id'] = $request->city_id;
                $data['district_id'] = $request->district_id;
                $data['seller_mobile'] = $request->seller_mobile;
                $data['other_links'] = $request->other_links;
                $data['sold'] = 0;

                $product = $this->productRepository->save_localized($data);

                if($internal_assets = $request->file('internal_assets'))
                {
                    foreach($internal_assets as $asset)
                    {
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'product-internal-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/posts', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $asset = $product->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                }

                if($external_assets = $request->file('external_assets'))
                {
                    foreach($external_assets as $asset)
                    {
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'product-external-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/posts', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $asset = $product->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                }

                return $this->sendResponse(new ProductResource($product), __('Product saved successfully'));
            }
            catch(\Throwable $th)
            {
                return $this->sendError($th->getMessage(), 500);
            }
        }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/products/{id}",
     *      summary="Display the specified Product",
     *      tags={"Product"},
     *      description="Get Product",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Product",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Product"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateProductAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/products/{id}",
     *      summary="Update the specified Product in storage",
     *      tags={"Product"},
     *      description="Update Product",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Product",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Product that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Product")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Product"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, Request $request)
    {
        try
        {
            /** @var Product $product */
            $product = $this->productRepository->find($id);

            if (empty($product)) {
                return $this->sendError('Product not found');
            }

            $validator = Validator::make($request->all(), [
                'price'                         => 'required',
                'description_ar_localized'      => 'required',
                'description_en_localized'      => 'required',
                'name_ar_localized'             => 'required|max:200',
                'name_en_localized'             => 'required|max:200',
                'farmed_type_id'                => 'required|exists:farmed_types,id',
                'city_id'                       => 'required|exists:cities,id',
                'district_id'                   => 'required|exists:districts,id',
                'seller_mobile'                 => 'required|max:20',
                'other_links'                   => 'nullable',
                'internal_assets'               => ['nullable','array'],
                'external_assets'               => ['nullable','array'],
                'internal_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg'],
                'external_assets.*'             => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
            ]);

            // return $this->sendError(json_encode($request->file('assets')[0]->getMimeType()), 777);
            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 888);
            }

            $data['price'] = $request->price;
            // $data['seller_id'] = auth()->id();
            $data['description_ar_localized'] = $request->description_ar_localized;
            $data['description_en_localized'] = $request->description_en_localized;
            $data['name_ar_localized'] = $request->name_ar_localized;
            $data['name_en_localized'] = $request->name_en_localized;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['city_id'] = $request->city_id;
            $data['district_id'] = $request->district_id;
            $data['seller_mobile'] = $request->seller_mobile;
            $data['other_links'] = $request->other_links;
            $data['sold'] = 0;

            $product = $this->productRepository->save_localized($data, $id);

            if($internal_assets = $request->file('internal_assets'))
            {
                $product->internal_assets()->delete();
                foreach($internal_assets as $asset)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetname = 'product-internal-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                    $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                    $assetmime = $asset->getClientMimeType();

                    $path = $asset->storeAs('assets/posts', $assetname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $asset = $product->assets()->create([
                        'asset_name'        => $assetname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetsize,
                        'asset_mime'        => $assetmime,
                    ]);
                }
            }

            if($external_assets = $request->file('external_assets'))
            {
                $product->external_assets()->delete();
                foreach($external_assets as $asset)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetname = 'product-external-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                    $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                    $assetmime = $asset->getClientMimeType();

                    $path = $asset->storeAs('assets/posts', $assetname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $asset = $product->assets()->create([
                        'asset_name'        => $assetname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetsize,
                        'asset_mime'        => $assetmime,
                    ]);
                }
            }

            return $this->sendResponse(new ProductResource($product), __('Product saved successfully'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/products/{id}",
     *      summary="Remove the specified Product from storage",
     *      tags={"Product"},
     *      description="Delete Product",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Product",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        try
        {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $product->delete();
        foreach($product->assets as $asset){
            $asset_url = $asset->asset_url;
            $path = parse_url($asset_url, PHP_URL_PATH);
            Storage::disk('s3')->delete($path);
        }
        $product->assets()->delete();

          return $this->sendSuccess('Model deleted successfully');
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
