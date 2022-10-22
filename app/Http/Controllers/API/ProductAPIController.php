<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\CityRepository;
use App\Repositories\FarmedTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\ProductTypeResource;
use App\Models\Business;
use App\Models\Fertilizer;
use App\Models\Insecticide;
use App\Models\NutElemValue;
use App\Models\ProductType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function pag($class, $perPage, $page)
    {
        $pag['itemsCount'] = $class::count();
        $pag['perPage'] = (int) ($perPage ?? 10);
        $pag['pagesCount'] = ceil($pag['itemsCount'] / $pag['perPage']);
        $pag['currentPage'] = (int) ($page ?? 1);
        $pag['skip'] = ($pag['currentPage'] - 1) * $pag['perPage'];
        return $pag;
    }

    public function index(Request $request)
    {
        $pag = $this->pag(Product::class, $request->perPage, $request->page);
        $products = Product::when(request()->product_type, fn ($q) => $q->where('product_type_id', request()->product_type))
        ->skip($pag['skip'])->limit($pag['perPage'])->get();

        $user = auth()->user();

        return $this->sendResponse([
            'unread_notifications_count' => $user->unreadNotifications->count(),
            'all' => ProductResource::collection($products),
            'meta' => $pag
        ], 'Products retrieved successfully');
    }


    public function search($query)
    {
        $query = strtolower(trim($query));
        $products = Product::whereRaw('LOWER(`name`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')
        ->orWhereRaw('LOWER(`description`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')
        ->get();
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
        return $this->sendResponse(
            [
                'cities' => CityResource::collection($this->cityRepository->all()),
                'farmed_types' => FarmedTypeResource::collection($this->farmedTypeRepository->all()),
                'product_types' => ProductTypeResource::collection(ProductType::all()),
            ], 'Products relations retrieved successfully');
    }





    public function store(CreateProductAPIRequest $request)
        {
            try
            {
                DB::beginTransaction();

                if($request->business_id){
                    $business = Business::find($request->business_id);
                    if(!auth()->user()->hasPermission("create-product", $business))
                        return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));
                }

                $input = $request->all();
                $input['seller_id'] = auth()->id();
                $input['sold'] = 0;
                // if insecticide
                if($request->product_type_id == 1 && $insecticideData = $request->insecticide){
                    $insecticideData['name'] = $request->name;
                    $insecticide = Insecticide::create($insecticideData);
                    $insecticide->acs()->attach($request->insecticide_acs);
                    $input['insecticide_id'] = $insecticide->id;

                    if($assets = $request->file('insecticide_assets'))
                    {
                        foreach($assets as $asset)
                        {
                            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'insecticide');
                            $insecticide->assets()->create($oneasset);
                        }
                    }
                }
                // if fertilizer
                else if($request->product_type_id == 3 && $fertilizerData = $request->fertilizer){
                    $fertilizerData['name'] = $request->name;
                    $nev = NutElemValue::create($request->fertilizer_nut_elem_value);
                    $fertilizerData['nut_elem_value_id'] = $nev->id;
                    $fertilizer = Fertilizer::create($fertilizerData);
                    $input['fertilizer_id'] = $fertilizer->id;

                    if($assets = $request->file('fertilizer_assets'))
                    {
                        foreach($assets as $asset)
                        {
                            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'fertilizer');
                            $fertilizer->assets()->create($oneasset);
                        }
                    }
                }

                $product = Product::create($input);

                $product->farmedTypes()->attach($request->farmed_types);
                foreach($request->shipping_cities as $sh){
                    $product->shippingCities()->attach($sh['city_id'], ['shipping_days' => $sh['shipping_days'], 'shipping_fees' => $sh['shipping_fees']]);
                }

                if($request->ads){
                    foreach($request->ads as $anad){
                        $ad = $product->ads()->create($anad);
                        if(isset($anad['asset'])){
                            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($anad['asset'], 'product-ad');
                            $ad->asset()->create($oneasset);
                        }else
                            return $this->sendError('The ad should have a photo');
                    }
                }


                if($internal_assets = $request->file('internal_assets'))
                {
                    foreach($internal_assets as $asset)
                    {
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product-internal');
                        $product->assets()->create($oneasset);
                    }
                }

                if($external_assets = $request->file('external_assets'))
                {
                    foreach($external_assets as $asset)
                    {
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product-external');
                        $product->assets()->create($oneasset);
                    }
                }
                DB::commit();
                return $this->sendResponse(new ProductResource($product), __('Product saved successfully'));
            }
            catch(\Throwable $th)
            {
                DB::rollBack();
                return $this->sendError($th->getMessage(), 500);
            }
        }

    public function show($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    public function update($id, CreateProductAPIRequest $request)
    {
        try
        {
            DB::beginTransaction();
            /** @var Product $product */
            $product = $this->productRepository->find($id);

            if (empty($product))
                return $this->sendError('Product not found');

            if($product->business_id){
                $business = Business::find($product->business_id);
                if(!auth()->user()->hasPermission("edit-product", $business))
                    return $this->sendError(__('Unauthorized, you don\'t have the required permissions!'));
            }

            $input = $request->all();

             // if insecticide
             if($request->product_type_id == 1 && $insecticideData = $request->insecticide){
                unset($insecticideData['precautions']);
                unset($insecticideData['notes']);
                $product->insecticide()->update($insecticideData);
                $insecticide = $product->insecticide;
                // were not put in mass update because constraint issue due to translatable
                $insecticide->name = $request->name;
                $insecticide->precautions = $request->insecticide['precautions'];
                $insecticide->notes = $request->insecticide['notes'];
                $insecticide->save();
                $insecticide->acs()->sync($request->insecticide_acs);

                if($assets = $request->file('insecticide_assets'))
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
            }
            // if fertilizer
            else if($request->product_type_id == 3 && $fertilizerData = $request->fertilizer){
                unset($fertilizerData['precautions']);
                unset($fertilizerData['notes']);
                $product->fertilizer()->update($fertilizerData);
                $fertilizer = $product->fertilizer;
                // were not put in mass update because constraint issue due to translatable
                $fertilizer->name = $request->name;
                $fertilizer->precautions = $request->fertilizer['precautions'];
                $fertilizer->notes = $request->fertilizer['notes'];
                $fertilizer->save();
                $fertilizer->nutElemValue()->update($request->fertilizer_nut_elem_value);

                if($assets = $request->file('fertilizer_assets'))
                {
                    foreach ($fertilizer->assets as $ass) {
                        $ass->delete();
                    }
                    foreach($assets as $asset)
                    {
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'fertilizer');
                        $fertilizer->assets()->create($oneasset);
                    }
                }
            }

            $product = $this->productRepository->save_localized($input, $id);

            $product->farmedTypes()->sync($request->farmed_types);
            $product->shippingCities()->detach();
            foreach($request->shipping_cities as $sh){
                $product->shippingCities()->attach($sh['city_id'], ['shipping_days' => $sh['shipping_days'], 'shipping_fees' => $sh['shipping_fees']]);
            }

            if($request->ads){
                foreach ($product->ads as $delad) {
                    $delad->asset->delete();
                    $delad->delete();
                }

                foreach($request->ads as $anad){
                    $ad = $product->ads()->create($anad);
                    if(isset($anad['asset'])){
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($anad['asset'], 'product-ad');
                        $ad->asset()->create($oneasset);
                    }else
                    return $this->sendError('The ad should have a photo');
                }
            }

            if($internal_assets = $request->file('internal_assets'))
            {
                foreach ($product->internal_assets as $ass) {
                    $ass->delete();
                }
                foreach($internal_assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product-internal');
                    $product->assets()->create($oneasset);
                }
            }

            if($external_assets = $request->file('external_assets'))
            {
                foreach ($product->external_assets as $ass) {
                    $ass->delete();
                }
                foreach($external_assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product-external');
                    $product->assets()->create($oneasset);
                }
            }
            DB::commit();
            return $this->sendResponse(new ProductResource(Product::find($product->id)), __('Product saved successfully'));
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try
        {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        foreach ($product->ads as $delad) {
            $delad->asset->delete();
            $delad->delete();
        }

        foreach($product->assets as $ass){
            $ass->delete();
        }
        $product->delete();

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
