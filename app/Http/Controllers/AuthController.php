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
use Illuminate\Notifications\Messages\MailMessage;
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
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify']]);
        // codeCheck, resetPassword, getCode--- were not put in except because called from authcont2 which is not auth protected
        // $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        if ($request->type == 'social') {
            $user = User::where("email", $request->email)->first();
            if ($user) {

                if ($user->status != 'accepted') {
                    return $this->sendError(__('Your account is not accepted'), 5010);
                }

                $code = 1111;
                $msg = 'User Exists';
                if (!$request->provider == $user->provider) {
                    $user->provider = $request->provider;
                    $user->name = $request->name;
                    $user->fcm = $request->fcm;
                    $user->avatar = $request->avatar;
                    $user->email_verified = 1;
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

        $credentials = array_merge($request->only($field, 'password'), ['status' => 'accepted', 'email_verified' => 1]); //['email_or_phone'=>$email_or_phone, 'password'=>$password, 'status'=>'accepted']

        // return response()->json($credentials);
        try {
            $user = User::where("$field", request()->{$field})->first();
            // $ttl = $request->get('remember_me') ? null : 60;
            // if (! $token = auth('api')->attempt($credentials)) {             // use the default ttl (time period in which the token expires) set in config('jwt.ttl')

            if (!$token = auth('api')->setTTL($this->ttl)->attempt($credentials)) { // if setTTL(null) not expiring token //used for mobile application as the token should not expire except with logout
                // $user = $this->userRepository->findBy(["$field" => request()->{$field}]);

                if ($user) {
                    if ($user->status != 'accepted') {
                        return $this->sendError(__('Your account is not accepted'), 5010);
                    }else if ($user->email_verified == 0) {
                        return $this->sendError(__('Please verify your email address first'), 5011);
                    }else{ // wrong password
                        $code = 5021;
                    }
                }else{ // wrong username
                    $code = 5020;
                }

                return $this->sendError(__('Invalid credentials'), $code);
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . null . ',id',
                'mobile' => 'required|string|max:255|unique:users,mobile,' . null . ',id',
                'password' => $password_required . '|string|min:8|confirmed',
                'human_job_id' => 'required|exists:human_jobs,id',
                'photo' => 'nullable|max:5000|image',
                'fcm' => 'nullable',
                'provider' => 'nullable',
                'avatar' => 'nullable',
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

                return $this->sendError($validator->errors()->first(), $code);
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

            return $this->sendVerificationCode($user);

            // return $this->respondWithUser($user);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);
        }
    }

    /**
     * Get verification code for forgot-password mobile verification
     */
    public function getCode(Request $request)
    {
        $method = (config('myconfig.verification_method') == 'email') ? 'email' : 'mobile';
        $validator = Validator::make($request->all(), [$method => 'required']);
        if ($validator->fails()) return $this->sendError($validator->errors()->first());
        if (!$user = User::where($method, $request->$method)->first()) return $this->sendError(__('No user found'));

        return $this->sendVerificationCode($user);
    }

    private function sendVerificationCode(User $user){
        // $user->code = User::generate_code();
        $user->code = '111111';
        $user->save();

        // send sms to user
        $msg = $user->code . ' ' . __('is your verification code for Ladybug');
        try{
            if(config('myconfig.verification_method') == 'email'){
                $res_msg = __("verification code sent to $user->email");
                $contact_data['subject'] = __('Verify Email Address');
                $contact_data['message'] = $msg;
                \Mail::to($user->email)->send(new \App\Mail\VerificationEmail($contact_data));
            }else{
                $mobile = '+2'.$user->mobile;
                $res_msg = __("verification code sent to $mobile");
                Twilio::message($mobile, $msg);
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }

        // return $this->sendSuccess($res_msg);
        return $this->sendResponse(['code' => $user->code], $res_msg); // for testing
    }


    public function resetPassword(Request $request)
    {
        $method = (config('myconfig.verification_method') == 'email') ? 'email' : 'mobile';
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            $method => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first());
        if (!$user = User::where($method, $request->$method)->first()) return $this->sendError(__('No user found'));
        if ($user->code != $request->code) return $this->sendError(__('Wrong code'));

        $user->password = bcrypt($request->password);
        $user->code = null;
        $user->save();

        return $this->sendSuccess(__('success'));
    }


    public function verify(Request $request)
    {
        $method = (config('myconfig.verification_method') == 'email') ? 'email' : 'mobile';
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            $method => 'required',
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first());
        if (!$user = User::where($method, $request->$method)->first()) return $this->sendError(__('No user found'));
        if ($user->code != $request->code) return $this->sendError(__('Wrong code'));

        $user->update([$method."_verified" => true, 'code' => null]);

        return $this->respondWithUser($user);
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

    private function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_minutes' => auth('api')->factory()->getTTL(), // in minutes
        ];
        return $this->sendResponse($data, __('Success'));
    }

    private function respondWithUser(User $user){
        $user = $this->userRepository->find($user->id);
        $token = auth('api')->fromUser($user);
        $data = [
            'user' => new UserLoginResource($user),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_minutes' => auth('api')->factory()->getTTL(),
        ];
        return $this->sendResponse($data, __('Success'));
    }
}

/**
 * all methods of JWTAuth:: are available for auth('api')->
 **  add functionalities in jwt[
 **      remember me ,
 **      forget password,
 **      if authenticated, cant reach login page
 **  ]
 **/

// for remember me functionality
// $token = auth()->setTTL(60*24*365)->attempt($credentials); for one year or
// if you want it to be permenant, make setTTL(null) and remove exp from config(jwt.required_claims)
