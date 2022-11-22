<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSettingAPIRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\DB;

/**
 * Class SettingController
 * @package App\Http\Controllers\API
 */

class SettingAPIController extends AppBaseController
{

    public function store(CreateSettingAPIRequest $request)
    {
        $input = $request->validated();
        $asset = $request->file('asset');
        if($input['name'] == 'report_price')
            $input['type'] = 'payment';
        elseif($input['name'] == 'weather_background'){
            $input['type'] = 'weather';
            if(!$asset) return $this->sendError('Weather background cannot be empty');
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
}
