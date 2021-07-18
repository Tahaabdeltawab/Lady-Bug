<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;

class WeatherApi{

    public function weather_history($lat, $lon, $day)
    {
        $response = Http::get('https://api.worldweatheronline.com/premium/v1/past-weather.ashx',
        [
            'key' => '874d43b2addd44c8b8e121336211107',
            // 'key' => 'c150bb44b59c4ac5be6204447193108',
            // 'key' => '02bf992d90fe471aaf1133645211807',
            'q' => "$lat,$lon",
            'date' => $day,
            'format' => 'json',
            'tp' => 24,
        ]
    );

    if($response->ok())
    {
        $data = $response->json()['data'];
        if(isset($data['error'])){
            $resp['error'] = $data['error'][0]['msg'];
        }else{
            $resp['temperature'] = $data['weather'][0]['avgtempC'];
            $resp['humidity'] = $data['weather'][0]['hourly'][0]['humidity'];
        }

        return $resp;
        return $data['weather'][0];
    }
    else
    {
        return ['status' => false, 'data' => $response->json()];
        // return $this->sendError('Error fetching the weather data', $response->status(), $response->json());
    }
    }
    public function weather_api($request)
    {

        $response = Http::get('api.openweathermap.org/data/2.5/weather',
            [
                'appid' => 'cd06a1348bed1b281e3e139a96ee5324',
                'lat' => $request->lat,
                'lon' => $request->lon,
                'lang' => $request->lang,
                'units' =>'metric'//'standard''imperial'
            ]
        );

        if($response->ok())
        {
            $data = $response->json();
            $weather_icon = $data['weather'][0]['icon'];

            $carbon = new \Carbon\Carbon('+02:00');

            $date = $carbon->parse(date("Y-m-d"))->locale($request->lang);
            $date_new = $date->isoFormat('dddd D MMMM');

            // $sunset = $carbon->parse($data['sys']['sunset'])->locale($request->lang);
            // $sunset_new = $sunset->isoFormat('hh:mm a');

            // $sunrise = $carbon->parse($data['sys']['sunrise'])->locale($request->lang);
            // $sunrise_new = $sunset->isoFormat('hh:mm a');

            $resp['weather_description']    = $data['weather'][0]['description'];
            $resp['weather_icon_url']       = "https://openweathermap.org/img/w/$weather_icon.png";
            $resp['temp']                   = $data['main']['temp']." C";
            $resp['date']                   = $date_new;
            $resp['sunrise']                = date("h:i a", $data['sys']['sunrise']);
            $resp['sunset']                 = date("h:i a", $data['sys']['sunset']);
            $resp['location']               = $data['name'];

            return ['status' => true, 'data' => $resp];
            // return $this->sendResponse($resp , 'Weather data retrieved successfully');
        }
        else
        {
            return ['status' => false, 'data' => $response->json()];
            // return $this->sendError('Error fetching the weather data', $response->status(), $response->json());
        }
    }

    public static function instance()
    {
        return new WeatherApi();
    }
}
