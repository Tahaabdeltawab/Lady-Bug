<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

class AppBaseController extends Controller
{
    public function sendResponse($data, $message, $code = 200)
    {
        return Response::json(ResponseUtil::makeResponse($data, $message, $code));
    }

    public function sendError($message, $code = 404, $data = [])
    {
        return Response::json(ResponseUtil::makeError($message, $code, $data));
    }

    public function sendSuccess($message, $code = 200, $data = [])
    {
        return Response::json([
            'success' => true,
            'data' => (object) $data,
            'message' => $message,
            'code'    => $code,
        ], 200);
    }
}
