<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeAPIRequest;
use App\Models\FarmedType;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class FarmedTypeController
 * @package App\Http\Controllers\API
 */

class FarmedTypeAPIController extends AppBaseController
{
    /** @var  FarmedTypeRepository */
    private $farmedTypeRepository;
    private $assetRepository;

    public function __construct(FarmedTypeRepository $farmedTypeRepo, AssetRepository $assetRepo)
    {
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->assetRepository = $assetRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypes",
     *      summary="Get a listing of the FarmedTypes.",
     *      tags={"FarmedType"},
     *      description="Get all FarmedTypes",
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
     *                  @SWG\Items(ref="#/definitions/FarmedType")
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
        $farmedTypes = $this->farmedTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes)], 'Farmed Types retrieved successfully');
    }

    public function search($query)
    {
        $farmedTypes = FarmedType::whereHas('translations', function($q) use($query)
        {
            $q->where('name','like', '%'.$query.'%' );
        })->get();

        return $this->sendResponse(['all' => FarmedTypeResource::collection($farmedTypes)], 'Farmed Types retrieved successfully');
    }

    /**
     * @param CreateFarmedTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/farmedTypes",
     *      summary="Store a newly created FarmedType in storage",
     *      tags={"FarmedType"},
     *      description="Store FarmedType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedType that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedType")
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
     *                  ref="#/definitions/FarmedType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(/* CreateFarmedTypeAPI */Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar_localized'                     => 'required|max:200',
            'name_en_localized'                     => 'required|max:200',
            'farm_activity_type_id'                 => 'required',
            'photo'                                 => 'nullable|max:2000|mimes:jpeg,jpg,png',
            'farming_temperature'                   => 'required',
            'flowering_time'                        => 'required|integer', // number of days till flowering
            'maturity_time'                         => 'required|integer',  // number of days till maturity
            'flowering_temperature'                 => 'required|array|size:2',
            'flowering_temperature.*'               => 'required|numeric',
            'maturity_temperature'                  => 'required|array|size:2',
            'maturity_temperature.*'                => 'required|numeric',
            'humidity'                              => 'required|array|size:2', // in the time of maturity
            'humidity.*'                            => 'required|numeric', // in the time of maturity
            'suitable_soil_salts_concentration'     => 'required|array|size:2',
            'suitable_soil_salts_concentration.*'   => 'required|numeric',
            'suitable_water_salts_concentration'    => 'required|array|size:2',
            'suitable_water_salts_concentration.*'  => 'required|numeric',
            'suitable_ph'                           => 'required|array|size:2',
            'suitable_ph.*'                         => 'required|numeric',
            'suitable_soil_types'                   => 'required|array|size:2',
            'suitable_soil_types.*'                 => 'required|integer|exists:soil_types,id',
        ]);

        if($validator->fails())
        {
            return $this->sendError(json_encode($validator->errors()), 5050);
        }

        $to_save['name_ar_localized'] = $request->name_ar_localized;
        $to_save['name_en_localized'] = $request->name_en_localized;
        $to_save['farm_activity_type_id'] = $request->farm_activity_type_id;
        $to_save['flowering_time'] = $request->flowering_time;
        $to_save['maturity_time'] = $request->maturity_time;
        $to_save['farming_temperature'] = json_encode($request->farming_temperature);
        $to_save['flowering_temperature'] = json_encode($request->flowering_temperature);
        $to_save['maturity_temperature'] = json_encode($request->maturity_temperature);
        $to_save['humidity'] = json_encode($request->humidity);
        $to_save['suitable_soil_salts_concentration'] = json_encode($request->suitable_soil_salts_concentration);
        $to_save['suitable_water_salts_concentration'] = json_encode($request->suitable_water_salts_concentration);
        $to_save['suitable_ph'] = json_encode($request->suitable_ph);
        $to_save['suitable_soil_types'] = json_encode($request->suitable_soil_types);

        $farmedType = $this->farmedTypeRepository->save_localized($to_save);

        if($photo = $request->file('photo'))
        {
            $currentDate = Carbon::now()->toDateString();
            $photoname = 'farmedType-'.$currentDate.'-'.uniqid().'.'.$photo->getClientOriginalExtension();
            $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
            $photomime = $photo->getClientMimeType();

            $path = $photo->storeAs('assets/images/farmedTypes', $photoname, 's3');
            // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

            $url  = Storage::disk('s3')->url($path);

            $saved_photo = $farmedType->asset()->create([
                'asset_name'        => $photoname,
                'asset_url'         => $url,
                'asset_size'        => $photosize,
                'asset_mime'        => $photomime,
            ]);

        }


        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/farmedTypes/{id}",
     *      summary="Display the specified FarmedType",
     *      tags={"FarmedType"},
     *      description="Get FarmedType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedType",
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
     *                  ref="#/definitions/FarmedType"
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
        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFarmedTypeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/farmedTypes/{id}",
     *      summary="Update the specified FarmedType in storage",
     *      tags={"FarmedType"},
     *      description="Update FarmedType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedType",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FarmedType that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FarmedType")
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
     *                  ref="#/definitions/FarmedType"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, /* CreateFarmedTypeAPI */Request $request)
    {

        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        $validator = Validator::make($request->all(), [
            'name_ar_localized'                     => 'required|max:200',
            'name_en_localized'                     => 'required|max:200',
            'farm_activity_type_id'                 => 'required',
            'photo'                                 => 'nullable|max:2000|mimes:jpeg,jpg,png', // nullable only for update
            'farming_temperature'                   => 'required',
            'flowering_time'                        => 'required|integer', // number of days till flowering
            'maturity_time'                         => 'required|integer',  // number of days till maturity
            'flowering_temperature'                 => 'required|array|size:2',
            'flowering_temperature.*'               => 'required|numeric',
            'maturity_temperature'                  => 'required|array|size:2',
            'maturity_temperature.*'                => 'required|numeric',
            'humidity'                              => 'required|array|size:2', // in the time of maturity
            'humidity.*'                            => 'required|numeric', // in the time of maturity
            'suitable_soil_salts_concentration'     => 'required|array|size:2',
            'suitable_soil_salts_concentration.*'   => 'required|numeric',
            'suitable_water_salts_concentration'    => 'required|array|size:2',
            'suitable_water_salts_concentration.*'  => 'required|numeric',
            'suitable_ph'                           => 'required|array|size:2',
            'suitable_ph.*'                         => 'required|numeric',
            'suitable_soil_types'                   => 'required|array|size:2',
            'suitable_soil_types.*'                 => 'required|integer|exists:soil_types,id',
        ]);

        if($validator->fails())
        {
            return $this->sendError(json_encode($validator->errors()), 5050);
        }

        $to_save['name_ar_localized'] = $request->name_ar_localized;
        $to_save['name_en_localized'] = $request->name_en_localized;
        $to_save['farm_activity_type_id'] = $request->farm_activity_type_id;
        $to_save['flowering_time'] = $request->flowering_time;
        $to_save['maturity_time'] = $request->maturity_time;
        $to_save['farming_temperature'] = json_encode($request->farming_temperature);
        $to_save['flowering_temperature'] = json_encode($request->flowering_temperature);
        $to_save['maturity_temperature'] = json_encode($request->maturity_temperature);
        $to_save['humidity'] = json_encode($request->humidity);
        $to_save['suitable_soil_salts_concentration'] = json_encode($request->suitable_soil_salts_concentration);
        $to_save['suitable_water_salts_concentration'] = json_encode($request->suitable_water_salts_concentration);
        $to_save['suitable_ph'] = json_encode($request->suitable_ph);
        $to_save['suitable_soil_types'] = json_encode($request->suitable_soil_types);

        $farmedType = $this->farmedTypeRepository->save_localized($to_save, $id);

        if($photo = $request->file('photo'))
        {
            $currentDate = Carbon::now()->toDateString();
            $photoname = 'farmedType-'.$currentDate.'-'.uniqid().'.'.$photo->getClientOriginalExtension();
            $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
            $photomime = $photo->getClientMimeType();

            $path = $photo->storeAs('assets/images/farmedTypes', $photoname, 's3');
            // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

            $url  = Storage::disk('s3')->url($path);

            $farmedType->asset()->delete();
            $saved_photo = $farmedType->asset()->create([
                'asset_name'        => $photoname,
                'asset_url'         => $url,
                'asset_size'        => $photosize,
                'asset_mime'        => $photomime,
            ]);

        }

        return $this->sendResponse(new FarmedTypeResource($farmedType), 'Farmed Type updated successfully');

        $farmedType = $this->farmedTypeRepository->save_localized($input, $id);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/farmedTypes/{id}",
     *      summary="Remove the specified FarmedType from storage",
     *      tags={"FarmedType"},
     *      description="Delete FarmedType",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FarmedType",
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
        /** @var FarmedType $farmedType */
        $farmedType = $this->farmedTypeRepository->find($id);

        if (empty($farmedType)) {
            return $this->sendError('Farmed Type not found');
        }

        $farmedType->delete();

        return $this->sendSuccess('Farmed Type deleted successfully');
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
