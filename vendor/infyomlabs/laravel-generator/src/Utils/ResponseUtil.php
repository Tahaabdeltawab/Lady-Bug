<?php

namespace InfyOm\Generator\Utils;

class ResponseUtil
{
    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($data, $message, $code)
    {
        return [
            'success' => true,
            'data'    => (object) $data,
            'message' => $message,
            'code'    => $code,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, $code, $data = [])
    {
        $res = [
            'success' => false,
            'data'    => (object) $data,  
            'message' => $message,
            'code'    => $code,
        ];

       /*  if (!empty($data)) {
            $res['data'] = $data;
        } */

        return $res;
    }
}
