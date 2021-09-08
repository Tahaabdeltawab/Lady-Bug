<?php

namespace App\Http\Helpers;


class Alerts
{
    public static function sendMobileNotification($title, $message, $registrationId = null, $info = [], $receivers = 'single')
    {
        $server_key =  env('FIREBASE_SERVER_KEY', 'AAAA5dV7edQ:APA91bENVOrfYuUmkuslx6qFbj_XGkMg-u5QLtyPjpYnLOS6wJ2ScLsDLwnOvRn3ks1Rm2G5A1SCFJbFFYTFieIuozO9UjhxC5FBNjylZUjHLvQ999QgXuG-IFYUtnQccOT3NXFnSC2T');

        $msg['notification'] = [
            'body'         => $message,
            'title'        => $title,
            // 'image'     => $image,
            // 'sound'        => 1,
            // 'subtitle'	=> 'This is a subtitle. subtitle',
            // 'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
            // 'vibrate'	=> 1,
            // 'largeIcon'	=> 'large_icon',
            // 'smallIcon'	=> 'small_icon'
        ];

        $data['data'] = $info;

        if($receivers == 'single' && $registrationId == null)
        return;

        $to = ($receivers == 'all' && $registrationId == null)
        ? ["to" => "/topics/all"]
        : ["registration_ids" => !is_array($registrationId) ? [$registrationId] : $registrationId];

        $fields = array_merge($msg, $data, $to);

        $headers = [
            'Authorization: key=' . $server_key,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        // var_dump($result);return $result;
    }
}
