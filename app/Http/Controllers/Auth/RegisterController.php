<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\AssetRepository;
use App\Http\Resources\AssetResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Repositories\HumanJobRepository;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $humanJobRepository;
    private $assetRepository;
    // private $roleRepository;
    // private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HumanJobRepository $humanJobRepo, AssetRepository $assetRepo/* ,UserRepository $userRepo,RoleRepository $roleRepo */)
    {
        $this->humanJobRepository = $humanJobRepo;
        $this->assetRepository = $assetRepo;
        // $this->roleRepository = $roleRepo;
        // $this->userRepository = $userRepo;
        $this->middleware('guest');
    }



    public function showRegistrationForm()
    {
        $jobs = $this->humanJobRepository->all();
        return view('auth.register', compact('jobs'));
    }




    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'max:255', 'unique:users'],
            'human_job_id' => ['required'],
            'photo' => ['required', 'max:2000', 'mimes:jpeg,jpg,png'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

   
    protected function create($data)
    {

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'mobile' => $data->mobile,
            'human_job_id' => $data->human_job_id,
            'photo_id' => $saved_photo->id,
            'password' => Hash::make($data->password),
        ]);
        

        if($photo = $data->file('photo')){
            $oneasset = app('\App\Http\Controllers\API\BusinessAPIController')->store_file($photo);
            $user->asset()->create($oneasset);
        }
        
      

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator(request()->all())->validate();

        // $userDefaultRole = $this->roleRepository->findBy([
        //     'name'  => config('laravel_user_management.auth.user_default_role')
        // ]);

        // if (!$userDefaultRole) 
        // {
        //     return redirect()->back()->with('fail_message',trans('trans.default_role_does_not_exist'));
        // }

        event(new Registered($user = $this->create($request)));
        // $this->roleRepository->setRoleToMember($user, $userDefaultRole);

        $this->guard()->login($user);

        if ($response = $this->registered(request(), $user)) {
            return $response;
        }

        return request()->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}
