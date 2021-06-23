<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;

class WeatherApi{

    public function weather_api($request)
    {

        $response = Http::get('http://history.openweathermap.org/data/2.5/history/city',
            [
                'appid' => 'cd06a1348bed1b281e3e139a96ee5324',
                'lat' => $request->lat,
                'lon' => $request->lon,
                'lang' => $request->lang,
                'units' =>'metric'//'standard''imperial'
            ]
        );
        // $response = Http::get('api.openweathermap.org/data/2.5/weather',
        //     [
        //         'appid' => 'cd06a1348bed1b281e3e139a96ee5324',
        //         'lat' => $request->lat,
        //         'lon' => $request->lon,
        //         'lang' => $request->lang,
        //         'units' =>'metric'//'standard''imperial'
        //     ]
        // );

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
