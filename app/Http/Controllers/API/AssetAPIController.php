<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAssetAPIRequest;
use App\Http\Requests\API\UpdateAssetAPIRequest;
use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AssetResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Response;

/**
 * Class AssetController
 * @package App\Http\Controllers\API
 */

class AssetAPIController extends AppBaseController
{
    /** @var  AssetRepository */
    private $assetRepository;

    public function __construct(AssetRepository $assetRepo)
    {
        $this->assetRepository = $assetRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/assets",
     *      summary="Get a listing of the Assets.",
     *      tags={"Asset"},
     *      description="Get all Assets",
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
     *                  @SWG\Items(ref="#/definitions/Asset")
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
        try{
            $assets = $this->assetRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );

            return $this->sendResponse(['all' => AssetResource::collection($assets)], 'Assets retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param CreateAssetAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/assets",
     *      summary="Store a newly created Asset in storage",
     *      tags={"Asset"},
     *      description="Store Asset",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Asset that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Asset")
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
     *                  ref="#/definitions/Asset"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAssetAPIRequest $request)
    {
        try{
            $asset = $request->file('asset');

            if(isset($asset)){
                $currentDate = Carbon::now()->toDateString();
                $assetname = 'asset-'.$currentDate.'-'.uniqid().'.'.$asset->getClientOriginalExtension();
                $assetsize = $asset->getSize();
                $assetmime = $asset->getClientMimeType();

                $path = $asset->storeAs('assets/images', $assetname, 's3');
                // $path = Storage::disk('s3')->putFileAs('assets/images', $asset, $assetname);

                $url  = Storage::disk('s3')->url($path);

                $asset = $this->assetRepository->create([
                    'asset_name'    => $assetname,
                    'asset_url'     => $url,
                    'asset_size'    => $assetsize,
                    'asset_mime'    => $assetmime
                ]);

                return $this->sendResponse(new AssetResource($asset), 'Asset saved successfully');

            }else{
                return $this->sendError('No assets found');
            }
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/assets/{id}",
     *      summary="Display the specified Asset",
     *      tags={"Asset"},
     *      description="Get Asset",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Asset",
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
     *                  ref="#/definitions/Asset"
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
        try{
            /** @var Asset $asset */
            $asset = $this->assetRepository->find($id);

            if (empty($asset)) {
                return $this->sendError('Asset not found');
            }

            return $this->sendResponse(new AssetResource($asset), 'Asset retrieved successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @param UpdateAssetAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/assets/{id}",
     *      summary="Update the specified Asset in storage",
     *      tags={"Asset"},
     *      description="Update Asset",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Asset",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Asset that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Asset")
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
     *                  ref="#/definitions/Asset"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, CreateAssetAPIRequest $request)
    {
        try{
            $input = $request->validated();

            /** @var Asset $asset */
            $asset = $this->assetRepository->find($id);

            if (empty($asset)) {
                return $this->sendError('Asset not found');
            }

            $asset = $this->assetRepository->save_localized($input, $id);

            return $this->sendResponse(new AssetResource($asset), 'Asset updated successfully');
        }catch(\Throwable $th){
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/assets/{id}",
     *      summary="Remove the specified Asset from storage",
     *      tags={"Asset"},
     *      description="Delete Asset",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Asset",
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
        /** @var Asset $asset */
        $asset = $this->assetRepository->find($id);

        if (empty($asset)) {
            return $this->sendError('Asset not found');
        }

        $asset->delete();

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
