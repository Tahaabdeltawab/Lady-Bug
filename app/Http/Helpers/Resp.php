<?php

namespace App\Http\Helpers;

use Response;

class Resp{

    public static function sendResponse($data, $message, $code = 200)
    {
        return Response::json(static::makeResponse($data, $message, $code));
    }

    public static function sendError($message, $code = 404, $data = [])
    {
        return Response::json(static::makeError($message, $code, $data));
    }

    public static function sendSuccess($message, $code = 200, $data = [])
    {
        return Response::json(static::makeSuccess($message, $code, $data), 200);
    }

    public static function makeResponse($data, $message, $code = 200)
    {
        return [
            'success' => true,
            'data'    => (object) $data,
            'message' => $message,
            'code'    => $code,
        ];
    }

    public static function makeSuccess($message, $code = 200, $data = [])
    {
        return [
            'success' => true,
            'data' => (object) $data,
            'message' => $message,
            'code'    => $code,
        ];

    }
    public static function makeError($message, $code = 404, $data = [])
    {
        return [
            'success' => false,
            'data'    => (object) $data,
            'message' => $message,
            'code'    => $code,
        ];

    }
}
