<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio;

class AuthController2 extends AppBaseController
{
    public function __construct() {
        $this->ttl = 1440000; // 1000 days
    }


    public function forgetPassword(Request $request)
    {
        return app('App\Http\Controllers\AuthController')->forgetPassword($request);
    }

    public function resetPassword(Request $request)
    {
        return app('App\Http\Controllers\AuthController')->resetPassword($request);
    }

    // before calling this you should add in register method [save code in db, send it to user in sms]
    public function codeCheck(Request $request)
    {
        return app('App\Http\Controllers\AuthController')->codeCheck($request);
    }

}
