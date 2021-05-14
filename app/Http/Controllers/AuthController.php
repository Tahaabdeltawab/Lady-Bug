<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Repositories\HumanJobRepository;
use App\Repositories\AssetRepository;
use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\AssetResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use JWTAuth;
// use Mekaeil\LaravelUserManagement\Repository\Eloquents\UserRepository;
// use Mekaeil\LaravelUserManagement\Repository\Eloquents\RoleRepository;

class AuthController extends AppBaseController
{
    private $userRepository;
    private $humanJobRepository;
    private $assetRepository;
    // private $roleRepository;
    // private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HumanJobRepository $humanJobRepo, AssetRepository $assetRepo, UserRepository $userRepo/* ,UserRepository $userRepo,RoleRepository $roleRepo */)
    {
        $this->userRepository = $userRepo;
        $this->humanJobRepository = $humanJobRepo;
        $this->assetRepository = $assetRepo;
        // $this->roleRepository = $roleRepo;
        // $this->userRepository = $userRepo;
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }


    public function login(Request $request)
    {

        $value = $request->input('identify');
        $field = filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $request->merge([$field => $value]);

        $credentials = array_merge($request->only($field, 'password'), ['status'=>'accepted']); //['email_or_phone'=>$email_or_phone, 'password'=>$password, 'status'=>'accepted']

        // return response()->json($credentials);
        try {
            $user = User::where("$field", request()->{$field})->first();
            // $ttl = $request->get('remember_me') ? null : 60;
            $ttl = 1440000; // 1000 days
            // if (! $token = auth('api')->attempt($credentials)) {             // use the default ttl (time period in which the token expires) set in config('jwt.ttl')

            if (! $token = auth('api')->setTTL($ttl)->attempt($credentials)) {  // if setTTL(null) not expiring token //used for mobile application as the token should not expire except with logout
                // $user = $this->userRepository->findBy(["$field" => request()->{$field}]);

                if($user && $user->status != 'accepted')
                {
                     return $this->sendError(__('trans.your_account_is_not_accepted'), 5010);
                }

                if($user) //wrong password
                $code = 5021;
                else    //wrong username
                $code = 5020;
                return $this->sendError(__('invalid credentials'), $code);
            }
        } catch (\Throwable $th) {
            // return response()->json(['error' => 'could_not_create_token'], 500);
            return $this->sendError($th->getMessage(), 500);
        }

        $data = [
            'user'              => new UserResource($user),
            'access_token'      => $token,
            'token_type'        => 'bearer',
            'expires_in_minutes'=>auth('api')->factory()->getTTL()
        ];
        return $this->sendResponse($data, __('Success'));
    }


    public function register(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                "email" => ["required", "string", "email", "max:255", "unique:users,email,".null.",id"],
                "mobile" => ["required", "string", "max:255", "unique:users,mobile,".null.",id"],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'human_job_id' => ['required', 'exists:human_jobs,id'],
                'photo' => ['nullable', 'max:2000', 'image', 'mimes:jpeg,jpg,png'],
            ]);

            if($validator->fails()){
                $errors = $validator->errors();
                if ($errors->has('email') && $errors->has('mobile'))
                    $code = 5031;
                elseif($errors->has('email') && !$errors->has('mobile'))
                    $code = 5032;
                elseif(!$errors->has('email') && $errors->has('mobile'))
                    $code = 5033;
                elseif($errors->has('photo'))
                    $code = 5034;
                else
                    $code = 5030;
                return $this->sendError(json_encode($validator->errors()), $code);
            }

            $user_role = Role::where('name', config('laratrust.taha.user_default_role'))->first();
            if(!$user_role)
            {
                return $this->sendError(__('Role app-user not found'), 4044);
            }


            $user = $this->userRepository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile'),
                'human_job_id' => $request->get('human_job_id'),
                'password' => Hash::make($request->get('password')),
            ]);

            if($user_role)
            {
                $user->attachRole(config('laratrust.taha.user_default_role'));
            }


            if($photo = $request->file('photo'))
            {
                $currentDate = Carbon::now()->toDateString();
                $photoname = 'profile-'.$currentDate.'-'.uniqid().'.'.$photo->getClientOriginalExtension();
                $photosize = $photo->getSize(); //size in bytes 1k = 1000bytes
                $photomime = $photo->getClientMimeType();

                $path = $photo->storeAs('assets/images/profiles', $photoname, 's3');
                // $path = Storage::disk('s3')->putFileAs('photos/images', $photo, $photoname);

                $url  = Storage::disk('s3')->url($path);

                $asset = $user->asset()->create([
                    'asset_name'        => $photoname,
                    'asset_url'         => $url,
                    'asset_size'        => $photosize,
                    'asset_mime'        => $photomime,
                ]);
            }


            $credentials = $request->only('email', 'password');

            // $token = JWTAuth::attempt($credentials); //the same
            $token = auth('api')->attempt($credentials);
            $user = $this->userRepository->find($user->id);
            $data = [
                'user' => new UserResource($user),
                'access_token'=>$token,
                'token_type'=>'bearer',
                'expires_in_minutes'=>auth('api')->factory()->getTTL()
            ];
            return $this->sendResponse($data, __('Success'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500);
        }
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
            'expires_in_minutes' => auth('api')->factory()->getTTL()   // in minutes
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








