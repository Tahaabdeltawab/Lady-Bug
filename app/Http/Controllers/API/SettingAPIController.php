<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSettingAPIRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SettingSmResource;
use App\Http\Resources\SettingXsResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class SettingController
 * @package App\Http\Controllers\API
 */

class SettingAPIController extends AppBaseController
{

    private $settings_keys = ['report_price', 'weather_background', 'info_pdf'];

    public function store(Request $request)
    {
        $assetV = $request->name == 'info_pdf' ? 'mimes:pdf' : 'image';
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::in($this->settings_keys)],
            'value' => 'requiredIf:asset,null',
            'type' => 'nullable',
            'asset' => "nullable|max:5000|file|$assetV",
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $input = $validator->validated();

        $asset = $request->file('asset');
        if($input['name'] == 'report_price'){
            $input['type'] = 'payment';
        }elseif($input['name'] == 'weather_background'){
            $input['type'] = 'weather';
            if(!$asset) return $this->sendError('Weather background cannot be empty');
        }elseif($input['name'] == 'info_pdf'){
            $input['type'] = 'info';
            if(!$asset) return $this->sendError('Info PDF cannot be empty');
        }

        if(isset($input['asset'])) unset($input['asset']);

        $setting = Setting::updateOrCreate(['name' => $input['name']], $input);

        if($asset){
            if($setting->asset)
                $setting->asset->delete();
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($asset, $input['name']);
            $setting->asset()->create($oneasset);
        }

        return $this->sendSuccess('Setting saved successfully');
    }

    public function show($name)
    {
        if($name == 'all'){
            $settings = Setting::whereIn('name', $this->settings_keys)->get();
            return $this->sendResponse(SettingSmResource::collection($settings), 'Settings retrieved successfully');
        }

        if(!in_array($name, $this->settings_keys) || !($setting = Setting::whereName($name)->first()))
            return $this->sendError('Setting not found');

        return $this->sendResponse(new SettingSmResource($setting), 'Setting retrieved successfully');
    }
}
