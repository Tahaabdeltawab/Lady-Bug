<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFarmedTypeGinfoAPIRequest;
use App\Http\Requests\API\UpdateFarmedTypeGinfoAPIRequest;
use App\Models\FarmedTypeGinfo;
use App\Repositories\FarmedTypeGinfoRepository;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\FarmedTypeStageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\FarmedTypeGinfoResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\FarmedTypeStageResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class FarmedTypeGinfoController
 * @package App\Http\Controllers\API
 */

class FarmedTypeGinfoAPIController extends AppBaseController
{
    /** @var  FarmedTypeGinfoRepository */
    private $farmedTypeGinfoRepository;
    private $farmedTypeRepository;
    private $farmedTypeStageRepository;

    public function __construct(FarmedTypeGinfoRepository $farmedTypeGinfoRepo, FarmedTypeRepository $farmedTypeRepo, FarmedTypeStageRepository $farmedTypeStageRepo)
    {
        $this->farmedTypeGinfoRepository = $farmedTypeGinfoRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
        $this->farmedTypeStageRepository = $farmedTypeStageRepo;

        $this->middleware('permission:farmed_type_ginfos.store')->only(['store']);
        $this->middleware('permission:farmed_type_ginfos.update')->only(['update']);
        $this->middleware('permission:farmed_type_ginfos.destroy')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $farmedTypeGinfos = $this->farmedTypeGinfoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => FarmedTypeGinfoResource::collection($farmedTypeGinfos)], 'Farmed Type Ginfos retrieved successfully');
    }


     //farmed_type_ginfos relations
     public function farmed_type_ginfos_relations()
     {
         $farmed_type_stages = $this->farmedTypeStageRepository->all();
         $farmed_types = $this->farmedTypeRepository->all();

         return $this->sendResponse(
             [
                 'farmed_type_stages' => FarmedTypeStageResource::collection($farmed_type_stages),
                 'farmed_types' => FarmedTypeResource::collection($farmed_types)
             ], 'Farmed Type General Information relations retrieved successfully');
     }


     //get farmed type ginfos by farmed_type_id
     public function farmed_type_ginfos_by_farmed_type_id($farmed_type_id, $stage_id = null)
     {

         $farmedTypeGinfos = FarmedTypeGinfo::where('farmed_type_id', $farmed_type_id)->when($stage_id, function($q) use($stage_id) {
             $q->where('farmed_type_stage_id', $stage_id);
         })->get();

         $data = [];
         $farmed_type_stages = $this->farmedTypeStageRepository->all();

         foreach($farmed_type_stages as $stage)
         {
            $r = $farmedTypeGinfos->where('farmed_type_stage_id', $stage->id);
            $data[] = ['stage' => $stage->name, 'news' => FarmedTypeGinfoResource::collection($r)];
         }

        return $this->sendResponse(['all' => $data], 'Farmed Type Ginfos retrieved successfully');
     }

    public function store(Request $request)
    {
        try
            {
                $validator = Validator::make($request->all(), [
                    'title_ar_localized' => 'required|max:200',
                    'title_en_localized' => 'required|max:200',
                    'content_ar_localized' => 'required',
                    'content_en_localized' => 'required',
                    'farmed_type_id' => 'required|exists:farmed_types,id',
                    'farmed_type_stage_id' => 'required|exists:farmed_type_stages,id',
                    'assets' => ['nullable','array'],
                    'assets.*' => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
                ]);

                // return $this->sendError(json_encode($request->file('assets')[0]->getMimeType()), 777);
                if($validator->fails()){
                    $errors = $validator->errors();

                    return $this->sendError(json_encode($errors), 989);
                }

                $data['title_ar_localized'] = $request->title_ar_localized;
                $data['title_en_localized'] = $request->title_en_localized;
                $data['content_ar_localized'] = $request->content_ar_localized;
                $data['content_en_localized'] = $request->content_en_localized;
                $data['farmed_type_id'] = $request->farmed_type_id;
                $data['farmed_type_stage_id'] = $request->farmed_type_stage_id;

                $farmedTypeGinfo = $this->farmedTypeGinfoRepository->save_localized($data);

                if($assets = $request->file('assets'))
                {
                    foreach($assets as $asset)
                    {
                        $currentDate = Carbon::now()->toDateString();
                        $assetname = 'farmedtype-ginfo-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                        $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                        $assetmime = $asset->getClientMimeType();

                        $path = $asset->storeAs('assets/posts', $assetname, 's3');
                        // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                        $url  = Storage::disk('s3')->url($path);

                        $asset = $farmedTypeGinfo->assets()->create([
                            'asset_name'        => $assetname,
                            'asset_url'         => $url,
                            'asset_size'        => $assetsize,
                            'asset_mime'        => $assetmime,
                        ]);
                    }
                }

                return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'Farmed Type Ginfo saved successfully');
            }
            catch(\Throwable $th)
            {
                return $this->sendError($th->getMessage(), 500);
            }
    }

    public function show($id)
    {
        /** @var FarmedTypeGinfo $farmedTypeGinfo */
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            return $this->sendError('Farmed Type Ginfo not found');
        }

        return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'Farmed Type Ginfo retrieved successfully');
    }

    public function update($id, Request $request)
    {
        try
        {
            /** @var FarmedTypeGinfo $farmedTypeGinfo */
            $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

            if (empty($farmedTypeGinfo)) {
                return $this->sendError('Farmed Type Ginfo not found');
            }

            $validator = Validator::make($request->all(), [
                'title_ar_localized' => 'required|max:200',
                'title_en_localized' => 'required|max:200',
                'content_ar_localized' => 'required',
                'content_en_localized' => 'required',
                'farmed_type_id' => 'required|exists:farmed_types,id',
                'farmed_type_stage_id' => 'required|exists:farmed_type_stages,id',
                'assets' => ['nullable','array'],
                'assets.*' => ['nullable', 'max:2000', 'mimes:jpeg,jpg,png,svg']
            ]);

            // return $this->sendError(json_encode($request->file('assets')[0]->getMimeType()), 777);
            if($validator->fails()){
                $errors = $validator->errors();

                return $this->sendError(json_encode($errors), 989);
            }

            $data['title_ar_localized'] = $request->title_ar_localized;
            $data['title_en_localized'] = $request->title_en_localized;
            $data['content_ar_localized'] = $request->content_ar_localized;
            $data['content_en_localized'] = $request->content_en_localized;
            $data['farmed_type_id'] = $request->farmed_type_id;
            $data['farmed_type_stage_id'] = $request->farmed_type_stage_id;

            $farmedTypeGinfo = $this->farmedTypeGinfoRepository->save_localized($data, $id);

            if($assets = $request->file('assets'))
            {
                $farmedTypeGinfo->assets()->delete();
                foreach($assets as $asset)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $assetname = 'farmedtype-ginfo-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                    $assetsize = $asset->getSize(); //size in bytes 1k = 1000bytes
                    $assetmime = $asset->getClientMimeType();

                    $path = $asset->storeAs('assets/posts', $assetname, 's3');
                    // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                    $url  = Storage::disk('s3')->url($path);

                    $asset = $farmedTypeGinfo->assets()->create([
                        'asset_name'        => $assetname,
                        'asset_url'         => $url,
                        'asset_size'        => $assetsize,
                        'asset_mime'        => $assetmime,
                    ]);
                }
            }

            return $this->sendResponse(new FarmedTypeGinfoResource($farmedTypeGinfo), 'Farmed Type Ginfo saved successfully');
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try
        {
        /** @var FarmedTypeGinfo $farmedTypeGinfo */
        $farmedTypeGinfo = $this->farmedTypeGinfoRepository->find($id);

        if (empty($farmedTypeGinfo)) {
            return $this->sendError('Farmed Type Ginfo not found');
        }

        $farmedTypeGinfo->delete();
        foreach($farmedTypeGinfo->assets as $asset){
            $asset_url = $asset->asset_url;
            $path = parse_url($asset_url, PHP_URL_PATH);
            Storage::disk('s3')->delete($path);
        }
        $farmedTypeGinfo->assets()->delete();

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
