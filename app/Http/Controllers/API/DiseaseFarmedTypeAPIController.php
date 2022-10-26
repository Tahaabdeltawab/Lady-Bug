<?php

namespace App\Http\Controllers\API;

use App\Models\FarmedType;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ResistantDiseaseRequest;
use App\Http\Requests\API\SensitiveDiseaseRequest;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\FarmedTypeResource;
use App\Http\Resources\SensitiveDiseaseResource;
use App\Models\SensitiveDiseaseFarmedType;


class DiseaseFarmedTypeAPIController extends AppBaseController
{

    public function __construct(){}

    public function one_sensitive_disease($disease_farmed_type_id)
    {
        $sensitive = SensitiveDiseaseFarmedType::find($disease_farmed_type_id);
        return $this->sendResponse(SensitiveDiseaseResource::make($sensitive), 'sensitive disease retrieved successfully');
    }

    public function get_sensitive_diseases($farmed_type_id, $farmed_type_stage_id = null)
    {
        $sensitives = SensitiveDiseaseFarmedType::where('farmed_type_id', $farmed_type_id)->when($farmed_type_stage_id != 'all',
            function($q) use($farmed_type_stage_id) {
                return $q->where('farmed_type_stage_id', $farmed_type_stage_id);
            })->get();
        return $this->sendResponse(SensitiveDiseaseResource::collection($sensitives), 'sensitive diseases retrieved successfully');
    }

    public function save_sensitive_disease(SensitiveDiseaseRequest $request)
    {
        $input = $request->validated();
        unset($input['assets']);
        if(!isset($input['farmed_type_stage_id'])) $input['farmed_type_stage_id'] = null;

        $sensitive = SensitiveDiseaseFarmedType::firstOrCreate($input);
        if($assets = $request->file('assets'))
        {
            foreach ($sensitive->assets as $ass) { // will only apply if updating
                $ass->delete();
            }
            foreach($assets as $asset)
            {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, 'sensitive');
                $assets[] = $sensitive->assets()->create($oneasset);
            }
        }
        return $this->sendResponse(SensitiveDiseaseResource::make($sensitive), 'success');
    }

    public function delete_sensitive_disease($id)
    {
        try
        {
            $sensitive = SensitiveDiseaseFarmedType::find($id);
            if (empty($sensitive)) return $this->sendError('Farmed Type Disease not found');
            foreach ($sensitive->assets as $ass) {
                $ass->delete();
            }
            $sensitive->delete();
            return $this->sendSuccess('Farmed Type Disease deleted successfully');
        }
        catch(\Throwable $th)
        {
            if ($th instanceof \Illuminate\Database\QueryException)
            return $this->sendError('Model cannot be deleted as it is associated with other models');
            else
            return $this->sendError('Error deleting the model');
        }
    }

    // resistant diseases
    public function get_resistant_diseases($id)
    {
        $farmedType = FarmedType::find($id);
        if(!$farmedType) return $this->sendError('Farmed type not found');
        return $this->sendResponse(DiseaseResource::collection($farmedType->resistant_diseases), 'resistant diseases retrieved successfully');
    }

    public function resistant_diseases(ResistantDiseaseRequest $request)
    {
        $farmedType = FarmedType::find($request->farmed_type_id);
        $farmedType->resistant_diseases()->sync($request->diseases);
        return $this->sendSuccess('saved');
    }
}
