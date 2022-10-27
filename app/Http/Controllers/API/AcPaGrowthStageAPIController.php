<?php

namespace App\Http\Controllers\API;

use App\Models\FarmedType;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ResistantDiseaseRequest;
use App\Http\Requests\API\AcPaGrowthStageRequest;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\AcPaGrowthStageResource;
use App\Models\AcPaGrowthStage;

class AcPaGrowthStageAPIController extends AppBaseController
{

    public function __construct(){}

    public function one_affecting_ac($ac_pa_growth_stage_id)
    {
        $affecting_ac = AcPaGrowthStage::find($ac_pa_growth_stage_id);
        if (empty($affecting_ac)) return $this->sendError('Affecting AC not found');
        return $this->sendResponse(AcPaGrowthStageResource::make($affecting_ac), 'Affecting AC retrieved successfully');
    }

    public function get_affecting_acs($pathogen_id, $pathogen_growth_stage_id = null)
    {
        $affecting_acs = AcPaGrowthStage::whereHas('pathogenGrowthStage',function($q) use($pathogen_id, $pathogen_growth_stage_id){
            $q->where('pathogen_growth_stages.pathogen_id', $pathogen_id)->when($pathogen_growth_stage_id,
            function($qq) use($pathogen_growth_stage_id) {
                return $qq->where('pathogen_growth_stages.id', $pathogen_growth_stage_id);
            });
        })->get();
        return $this->sendResponse(AcPaGrowthStageResource::collection($affecting_acs), 'Affecting ACs retrieved successfully');
    }

    public function create_affecting_ac(AcPaGrowthStageRequest $request)
    {
        $input = $request->validated();
        unset($input['assets']);

        if(AcPaGrowthStage::where(['ac_id' => $input['ac_id'], 'pathogen_growth_stage_id' => $input['pathogen_growth_stage_id']])->exists())
            return $this->sendError('This Affecting Ac is already present with this stage');

        $affecting_ac = AcPaGrowthStage::create($input);
        if($assets = $request->file('assets'))
        {
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'affecting-ac');
                $assets[] = $affecting_ac->assets()->create($oneasset);
            }
        }
        return $this->sendResponse(AcPaGrowthStageResource::make($affecting_ac), 'success');
    }

    public function update_affecting_ac($ac_pa_growth_stage_id, AcPaGrowthStageRequest $request)
    {
        $input = $request->validated();
        unset($input['assets']);

        if(!$affecting_ac = AcPaGrowthStage::find($ac_pa_growth_stage_id))
            return $this->sendError('Affecting AC Not found');

        $mine = ($affecting_ac->ac_id == $input['ac_id']) && ($affecting_ac->pathogen_growth_stage_id == $input['pathogen_growth_stage_id']);
        if(!$mine && AcPaGrowthStage::where(['ac_id' => $input['ac_id'], 'pathogen_growth_stage_id' => $input['pathogen_growth_stage_id']])->exists())
            return $this->sendError('This Affecting Ac is already present with this stage');

        $affecting_ac->update($input);
        if($assets = $request->file('assets'))
        {
            foreach ($affecting_ac->assets as $ass) {
                $ass->delete();
            }
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'affecting-ac');
                $assets[] = $affecting_ac->assets()->create($oneasset);
            }
        }
        return $this->sendResponse(AcPaGrowthStageResource::make($affecting_ac), 'success');
    }

    public function delete_affecting_ac($id)
    {
        try
        {
            $affecting_ac = AcPaGrowthStage::find($id);
            if (empty($affecting_ac)) return $this->sendError('Affecting AC not found');
            foreach ($affecting_ac->assets as $ass) {
                $ass->delete();
            }
            $affecting_ac->delete();
            return $this->sendSuccess('Affecting AC deleted successfully');
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
