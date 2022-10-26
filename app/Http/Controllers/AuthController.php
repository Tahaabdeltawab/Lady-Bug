<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Twilio;


class AuthController extends AppBaseController
{
    private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepo
    ) {

        $this->ttl = 1440000; // 1000 days
        $this->userRepository = $userRepo;
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        if ($request->type == 'social') {
            $user = User::where("email", $request->email)->first();
            if ($user) {

                if ($user->status != 'accepted') {
                    return $this->sendError(__('trans.your_account_is_not_accepted'), 5010);
                }

                $code = 1111;
                $msg = 'User Exists';
                if (!$request->provider == $user->provider) {
                    $user->provider = $request->provider;
                    $user->name = $request->name;
                    $user->fcm = $request->fcm;
                    $user->avatar = $request->avatar;
                    $user->save();
                }

                // $token = auth('api')->setTTL($this->ttl)->attempt(['email' => $user->email]);
                $token = auth('api')->setTTL($this->ttl)->login($user);
                $data = [
                    'user' => new UserLoginResource($user),
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in_minutes' => auth('api')->factory()->getTTL(),
                ];

                return $this->sendResponse($data, __($msg), $code);

            }
            $code = 1112;
            $msg = 'User Does not Exist';
            return $this->sendSuccess(__($msg), $code);
        }

        $value = $request->input('identify');
        $field = filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $request->merge([$field => $value]);

        $credentials = array_merge($request->only($field, 'password'), ['status' => 'accepted']); //['email_or_phone'=>$email_or_phone, 'password'=>$password, 'status'=>'accepted']

        // return response()->json($credentials);
        try {
            $user = User::where("$field", request()->{$field})->first();
            // $ttl = $request->get('remember_me') ? null : 60;
            // if (! $token = auth('api')->attempt($credentials)) {             // use the default ttl (time period in which the token expires) set in config('jwt.ttl')

            if (!$token = auth('api')->setTTL($this->ttl)->attempt($credentials)) { // if setTTL(null) not expiring token //used for mobile application as the token should not expire except with logout
                // $user = $this->userRepository->findBy(["$field" => request()->{$field}]);

                if ($user && $user->status != 'accepted') {
                    return $this->sendError(__('trans.your_account_is_not_accepted'), 5010);
                }

                if ($user) //wrong password
                {
                    $code = 5021;
                } else //wrong username
                {
                    $code = 5020;
                }

                return $this->sendError(__('invalid credentials'), $code);
            }
        } catch (\Throwable $th) {
            // return response()->json(['error' => 'could_not_create_token'], 500);
            return $this->sendError($th->getMessage(), 500);
        }

        $user->fcm = $request->fcm ?? $user->fcm;
        $user->save();

        $data = [
            'user' => new UserLoginResource($user),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_minutes' => auth('api')->factory()->getTTL(),
        ];
        return $this->sendResponse($data, __('Success'));
    }

    public function register(Request $request)
    {
        try
        {
            $password_required = $request->provider ? 'nullable' : 'required'; // if social reg., don't require password
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                "email" => ["required", "string", "email", "max:255", "unique:users,email," . null . ",id"],
                "mobile" => ["required", "string", "max:255", "unique:users,mobile," . null . ",id"],
                'password' => [$password_required, 'string', 'min:8', 'confirmed'],
                'human_job_id' => ['required', 'exists:human_jobs,id'],
                'photo' => ['nullable', 'max:5000', 'image', 'mimes:jpeg,jpg,png'],
                'fcm' => ['nullable'],
                'provider' => ['nullable'],
                'avatar' => ['nullable'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('email') && $errors->has('mobile')) {
                    $code = 5031;
                } elseif ($errors->has('email') && !$errors->has('mobile')) {
                    $code = 5032;
                } elseif (!$errors->has('email') && $errors->has('mobile')) {
                    $code = 5033;
                } elseif ($errors->has('photo')) {
                    $code = 5034;
                } else {
                    $code = 5030;
                }

                return $this->sendError(json_encode($validator->errors()), $code);
            }

            $user_role = Role::where('name', config('myconfig.user_default_role'))->first();
            if (!$user_role) {
                return $this->sendError(__('Role app-user not found'), 4044);
            }

            $user = $this->userRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'type' => "app_user",
                'mobile' => $request->get('mobile'),
                'human_job_id' => $request->get('human_job_id'),
                'password' => $password_required == 'required' ? Hash::make($request->get('password')) : null,
                'fcm' => $request->fcm,
                'provider' => $request->provider,
                'avatar' => $request->avatar,
                'balance' => 1000,
            ]);

            $user->attachRole(config('myconfig.user_default_role'));
            // default values = 1
            $user->notification_settings()->create([]);

            if ($photo = $request->file('photo')) {
                $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo);
                $user->asset()->create($oneasset);
            }

            $credentials = $request->only('email', 'password');

            // $token = JWTAuth::attempt($credentials); //the same
            $token = auth('api')->attempt($credentials);
            $user = $this->userRepository->find($user->id);
            $data = [
                'user' => new UserLoginResource($user),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in_minutes' => auth('api')->factory()->getTTL(),
            ];
            return $this->sendResponse($data, __('Success'));
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);
        }
    }


    public function forgetPassword(Request $request)
    {

        //make validation
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails())
        return $this->sendError(json_encode($validator->errors()));

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user = User::where('mobile', $request->mobile)->first())
            return $this->sendError(__('No user found'));

        $user->code = User::generate_code();
        $user->save();

        //send sms to user
        $msg = __('Your verification code is ') . $user->code;
        $mobile = $user->mobile;
        $res_msg = __('success');
        try{
            Twilio::message('+2'.$mobile, $msg);
        } catch (\Throwable $th) {
            $res_msg = $th->getMessage();
        }

        $data = [
            'code' => $user->code,
        ];
        return $this->sendResponse($data, $res_msg);
    }


    public function resetPassword(Request $request)
    {
        //make validation
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'mobile' => ['required'],
            'password' => ["required", "string", "min:8", "confirmed"],
        ]);

        if ($validator->fails())
            return $this->sendError(json_encode($validator->errors()));

        if (!$user = User::where('mobile', $request->mobile)->first())
            return $this->sendError(__('No user found'));

        if ($user->code != $request->code)
            return $this->sendError(__('Wrong code'));

        $user->password = bcrypt($request->password);
        $user->code = null;
        $user->save();

        return $this->sendSuccess(__('success'));
    }


    // before calling this you should add in register method [save code in db, send it to user in sms]
    public function codeCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails())
            return $this->sendError(json_encode($validator->errors()));

        if (!$user = User::where('mobile', $request->mobile)->first())
            return $this->sendError(__('No user found'));

        if ($user->code != $request->code)
            return $this->sendError(__('Wrong code'));

        $user->update(['mobile_verified' => true, 'code' => null]);

        return $this->sendResponse([], __('success'));
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->toUser();
        // $user = auth('api')->user();
        return $this->sendResponse(new UserProfileResource($user), __('user retrieved successfully'));
    }

    public function logout()
    {
        // $token = substr($request->header('Authorization'), 7); //7 to remove "bearer "
        // JWTAuth::setToken($token)->invalidate(); // also the same but use the Request to get the Authorization header (token)
        // JWTAuth::parseToken()->invalidate(); // the same
        auth()->user()->fcm = null;
        auth()->user()->save();

        auth('api')->logout();
        return $this->sendSuccess(__('Successfully logged out'));
    }

    public function refresh()
    {
        // return $this->respondWithToken(JWTAuth::parseToken()->refresh()); // the same
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_minutes' => auth('api')->factory()->getTTL(), // in minutes
            // 'expires_in_minutes' => JWTAuth::factory()->getTTL()
        ];
        return $this->sendResponse($data, __('Success'));
    }

}

/**
 **  add functionalities in jwt[
 **      remember me ,
 **      forget password,
 **      if authenticated, cant reach login page
 **  ]
 **/

// for remember me functionality
// $token = auth()->setTTL(60*24*365)->attempt($credentials); for one year or
// if you want it to be permenant, make setTTL(null) and remove exp from config(jwt.required_claims)
