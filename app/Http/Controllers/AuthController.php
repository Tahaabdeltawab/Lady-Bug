<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\AnimalFodderSourceRepository;
use App\Repositories\AnimalMedicineSourceRepository;
use App\Repositories\AssetRepository;
use App\Repositories\ChemicalFertilizerSourceRepository;
use App\Repositories\HumanJobRepository;
use App\Repositories\SeedlingSourceRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Twilio;

// use Mekaeil\LaravelUserManagement\Repository\Eloquents\UserRepository;
// use Mekaeil\LaravelUserManagement\Repository\Eloquents\RoleRepository;

class AuthController extends AppBaseController
{
    private $userRepository;
    private $humanJobRepository;
    private $assetRepository;

    private $animalMedicineSourceRepository;
    private $animalFodderSourceRepository;
    private $chemicalFertilizerSourceRepository;
    private $seedlingSourceRepository;

    // private $roleRepository;
    // private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        HumanJobRepository $humanJobRepo,
        AssetRepository $assetRepo,
        UserRepository $userRepo,
        SeedlingSourceRepository $seedlingSourceRepo,
        ChemicalFertilizerSourceRepository $chemicalFertilizerSourceRepo,
        AnimalFodderSourceRepository $animalFodderSourceRepo,
        AnimalMedicineSourceRepository $animalMedicineSourceRepo
    ) {

        $this->ttl = 1440000; // 1000 days
        $this->userRepository = $userRepo;
        $this->humanJobRepository = $humanJobRepo;
        $this->assetRepository = $assetRepo;
        $this->seedlingSourceRepository = $seedlingSourceRepo;
        $this->chemicalFertilizerSourceRepository = $chemicalFertilizerSourceRepo;
        $this->animalFodderSourceRepository = $animalFodderSourceRepo;
        $this->animalMedicineSourceRepository = $animalMedicineSourceRepo;
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
                    'user' => new UserResource($user),
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
            'user' => new UserResource($user),
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
                'photo' => ['nullable', 'max:2000', 'image', 'mimes:jpeg,jpg,png'],
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
            ]);

            $user->attachRole(config('myconfig.user_default_role'));

            // when a user registers
            // if he selected his job, for example, plant nursery, a new record should be added to the seedlings_sources table,
            $companiesJobs = config('myconfig.companies_jobs');
            $userJob = $user->job;
            $userJobEName = $userJob->translate('en')->name;
            if (in_array($userJobEName, array_values($companiesJobs))) {
                if ($userJobEName == $companiesJobs['pharma']) {
                    $this->animalMedicineSourceRepository->save_localized([
                        'name_ar_localized' => $user->name,
                        'name_en_localized' => $user->name,
                    ]);
                } elseif ($userJobEName == $companiesJobs['chem']) {
                    $this->chemicalFertilizerSourceRepository->save_localized([
                        'name_ar_localized' => $user->name,
                        'name_en_localized' => $user->name,
                    ]);} elseif ($userJobEName == $companiesJobs['feed']) {
                    $this->animalFodderSourceRepository->save_localized([
                        'name_ar_localized' => $user->name,
                        'name_en_localized' => $user->name,
                    ]);} elseif ($userJobEName == $companiesJobs['seed']) {
                    $this->seedlingSourceRepository->save_localized([
                        'name_ar_localized' => $user->name,
                        'name_en_localized' => $user->name,
                    ]);}
            }

            if ($photo = $request->file('photo')) {
                $currentDate = Carbon::now()->toDateString();
                $photoname = 'profile-' . $currentDate . '-' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
                $photomime = $photo->getClientMimeType();

                $path = $photo->storeAs('assets/images/profiles', $photoname, 's3');
                // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

                $url = Storage::disk('s3')->url($path);

                $asset = $user->asset()->create([
                    'asset_name' => $photoname,
                    'asset_url' => $url,
                    'asset_size' => $photosize,
                    'asset_mime' => $photomime,
                ]);
            }

            $credentials = $request->only('email', 'password');

            // $token = JWTAuth::attempt($credentials); //the same
            $token = auth('api')->attempt($credentials);
            $user = $this->userRepository->find($user->id);
            $data = [
                'user' => new UserResource($user),
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

        return $this->sendResponse($data, __('success'));
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->toUser();
        // $user = auth('api')->user();
        return $this->sendResponse(new UserResource($user), __('user retrieved successfully'));
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
