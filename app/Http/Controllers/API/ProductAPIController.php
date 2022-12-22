<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\CityRepository;
use App\Repositories\FarmedTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\RateProductRequest;
use App\Http\Resources\AcResource;
use App\Http\Resources\AcXsResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\ProductAdminResource;
use App\Http\Resources\ProductLgResource;
use App\Http\Resources\ProductTypeResource;
use App\Http\Resources\ProductXsResource;
use App\Models\Ac;
use App\Models\Business;
use App\Models\City;
use App\Models\Country;
use App\Models\FarmedType;
use App\Models\Fertilizer;
use App\Models\Insecticide;
use App\Models\NutElemValue;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    // admin
    public function admin_index(Request $request)
    {
        $query = Product::query();
        $pag = \Helper::pag($query->count(), $request->perPage, $request->page);
        $products = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

        $data = [
            'data' => ProductAdminResource::collection($products),
            'meta' => $pag
        ];
        return $this->sendResponse($data, 'Products retrieved successfully');
    }
    public function index(Request $request)
    {
        $query = Product::when(request()->product_type, fn ($q) => $q->where('product_type_id', request()->product_type));
        $pag = \Helper::pag($query->count(), $request->perPage, $request->page);
        $products = $query->skip($pag['skip'])->limit($pag['perPage'])->get();

        $data = [
            'unread_notifications_count' => auth()->user()->unreadNotifications->count(),
            'data' => collect(ProductXsResource::collection($products))->where('canBeSeen', true)->values(),
            'meta' => $pag
        ];
        if(!request()->product_type){
            $data['product_types'] = ProductType::has('products')->get();
        }
        return $this->sendResponse($data, 'Products retrieved successfully');
    }


    public function search($query)
    {
        $query = \Str::lower(trim($query));
        $products = Product::whereRaw('LOWER(`name`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')
        ->orWhereRaw('LOWER(`description`) regexp ? ', '"(ar|en)":"\w*' . $query . '.*"')
        ->get();
        return $this->sendResponse(['all' => collect(ProductXsResource::collection($products))->where('canBeSeen', true)->values()], 'Products retrieved successfully');
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


    // RATE

    public function rate_product(RateProductRequest $request)
    {
        try
        {
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
                'countries' => CountryResource::collection(Country::all()),
                'cities' => CityResource::collection(City::all()),
                'farmed_types' => FarmedTypeResource::collection(FarmedType::all()),
                'product_types' => ProductTypeResource::collection(ProductType::all()),
                'acs' => AcXsResource::collection(Ac::get(['id','name'])),
                'dosage_forms' => [// fertilizer and insecticide
                    ['value' => 'liquid', 'name' => app()->getLocale()=='ar' ?  'سائل' : 'liquid'],
                    ['value' => 'powder', 'name' => app()->getLocale()=='ar' ?  'بودرة' : 'powder'],
                ],
                'addition_ways' => [ // fertilizer
                    ['value' => 'soil', 'name' => app()->getLocale()=='ar' ?  'في التربة' : 'In Soil'],
                    ['value' => 'leaves', 'name' => app()->getLocale()=='ar' ?  'على الأرواق' : 'On Leaves'],
                ],
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

                $input = $request->validated();
                $input['seller_id'] = auth()->id();
                $input['sold'] = 0;
                // if insecticide
                if($request->product_type_id == 1 && $insecticideData = $request->insecticide){
                    $insecticideData['name'] = $request->name;
                    $insecticideData['acs'] = $request->insecticide_acs; // to pass through validation
                    // validation
                    $rules = Insecticide::$rules; unset($rules['name.ar']); unset($rules['name.en']);
                    $validator = Validator::make($insecticideData, $rules);
                    if ($validator->fails()) return $this->sendError($validator->errors()->first());

                    $insecticide = Insecticide::create($insecticideData);
                    $insecticide->acs()->attach($request->insecticide_acs);
                    $input['insecticide_id'] = $insecticide->id;
                }
                // if fertilizer
                else if($request->product_type_id == 3 && $fertilizerData = $request->fertilizer){
                    $fertilizerData['name'] = $request->name;
                    // validation
                    $rules = Fertilizer::$rules; unset($rules['name.ar']); unset($rules['name.en']);
                    $validator = Validator::make($fertilizerData, $rules);
                    if ($validator->fails()) return $this->sendError($validator->errors()->first());

                    $nev = NutElemValue::create($request->fertilizer_nut_elem_value);
                    $fertilizerData['nut_elem_value_id'] = $nev->id;
                    $fertilizer = Fertilizer::create($fertilizerData);
                    $input['fertilizer_id'] = $fertilizer->id;
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

                if($assets = $request->file('assets'))
                {
                    foreach($assets as $asset)
                    {
                        $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product');
                        $product->assets()->create($oneasset);
                        if(isset($insecticide))
                            $insecticide->assets()->create($oneasset);
                        else if(isset($fertilizer))
                            $fertilizer->assets()->create($oneasset);

                    }
                }


                // notify the owner followers
                foreach(auth()->user()->followers as $follower){
                    $follower->notify(new \App\Notifications\Product($product));
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

        return $this->sendResponse(new ProductLgResource($product), 'Product retrieved successfully');
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

            $input = $request->validated();

             // if insecticide
             if($request->product_type_id == 1 && $insecticideData = $request->insecticide){
                $insecticideData['acs'] = $request->insecticide_acs; // to pass through validation
                // validation
                $rules = Insecticide::$rules; unset($rules['name.ar']); unset($rules['name.en']);
                $validator = Validator::make($insecticideData, $rules);
                if ($validator->fails()) return $this->sendError($validator->errors()->first());

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
            }
            // if fertilizer
            else if($request->product_type_id == 3 && $fertilizerData = $request->fertilizer){
                // validation
                $rules = Fertilizer::$rules; unset($rules['name.ar']); unset($rules['name.en']);
                $validator = Validator::make($fertilizerData, $rules);
                if ($validator->fails()) return $this->sendError($validator->errors()->first());

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
            }

            $product = $this->productRepository->update($input, $id);

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

            if($assets = $request->file('assets'))
            {
                foreach ($product->assets as $ass) {
                    $ass->delete();
                    // this will delete the assets from database and from
                    // storage (storage assets which are the same storage assets for
                    // either the product fertilizer or insecticide).
                    // so no need for iterating over insecticide assets to delete them from storage
                    // just db deletion is needed.
                }

                if(isset($insecticide))
                    $insecticide->assets()->delete();
                else if(isset($fertilizer))
                    $fertilizer->assets()->delete();


                foreach($assets as $asset)
                {
                    $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'product');
                    $product->assets()->create($oneasset);
                    if(isset($insecticide))
                        $insecticide->assets()->create($oneasset);
                    else if(isset($fertilizer))
                        $fertilizer->assets()->create($oneasset);
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
