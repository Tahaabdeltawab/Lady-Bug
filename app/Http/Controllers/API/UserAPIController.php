<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Requests\API\CreateUserFavoritesAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\UserResource;
use App\Http\Resources\FarmedTypeResource;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Repositories\FarmedTypeRepository;
use App\Repositories\HumanJobRepository;
use App\Repositories\AssetRepository;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $humanJobRepository;
    private $assetRepository;
    private $farmedTypeRepository;

    public function __construct(HumanJobRepository $humanJobRepo, AssetRepository $assetRepo, UserRepository $userRepo, FarmedTypeRepository $farmedTypeRepo)
    {
        $this->userRepository = $userRepo;
        $this->humanJobRepository = $humanJobRepo;
        $this->assetRepository = $assetRepo;
        $this->farmedTypeRepository = $farmedTypeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/users",
     *      summary="Get a listing of the Users.",
     *      tags={"User"},
     *      description="Get all Users",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(['all' => UserResource::collection($users)], 'Users retrieved successfully');
    }

    public function get_weather(Request $request)
    {
        
        $response = Http::get('api.openweathermap.org/data/2.5/weather',
            [
                'appid' => 'cd06a1348bed1b281e3e139a96ee5324',
                'lat' => $request->lat,
                'lon' => $request->lon,
                'lang' => $request->lang,
                'units' =>'metric'//'standard''imperial'
            ]
        );

        if($response->ok())
        {
            $data = $response->json();
            $weather_icon = $data['weather'][0]['icon'];

            $carbon = new \Carbon\Carbon('+02:00');

            $date = $carbon->parse(date("Y-m-d"))->locale($request->lang);
            $date_new = $date->isoFormat('dddd D MMMM');

            // $sunset = $carbon->parse($data['sys']['sunset'])->locale($request->lang);
            // $sunset_new = $sunset->isoFormat('hh:mm a');

            // $sunrise = $carbon->parse($data['sys']['sunrise'])->locale($request->lang);
            // $sunrise_new = $sunset->isoFormat('hh:mm a');

            $resp['weather_description']    = $data['weather'][0]['description'];
            $resp['weather_icon_url']       = "https://openweathermap.org/img/w/$weather_icon.png";
            $resp['temp']                   = $data['main']['temp']." C";
            $resp['date']                   = $date_new;
            $resp['sunrise']                = date("h:i a", $data['sys']['sunrise']);
            $resp['sunset']                 = date("h:i a", $data['sys']['sunset']);
            $resp['location']               = $data['name'];

            return $this->sendResponse($resp , 'Weather data retrieved successfully');
        }
        else
        {
            return $this->sendError('Error fetching the weather data', $response->status(), $response->json());
        }
    }


    /**
     * @param CreateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users",
     *      summary="Store a newly created User in storage",
     *      tags={"User"},
     *      description="Store User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->validated();

        $user = $this->userRepository->save_localized($input);

        return $this->sendResponse(new UserResource($user), 'User saved successfully');
    }


    public function my_favorites()
    {
        $my_favorites = auth()->user()->favorites;
     
        return $this->sendResponse(['all' => FarmedTypeResource::collection($my_favorites)], 'User selected favorites retrieved successfully');
    }
    
    
    public function store_favorites(CreateUserFavoritesAPIRequest $request)
    {
        $input = $request->validated();
        auth()->user()->favorites()->sync($input['favorites']);
        return $this->sendSuccess(__('User favorites saved successfully'));
    }



    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/{id}",
     *      summary="Display the specified User",
     *      tags={"User"},
     *      description="Get User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/users/{id}",
     *      summary="Update the specified User in storage",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, /* CreateUserAPI */Request $request)
    {
        try
        {

            $user = $this->userRepository->find($id);

            if (empty($user)) {
                return $this->sendError('User not found');
            }

            $validator = Validator::make($request->all(), [
                "name" => ["required", "string", "max:255"],
                "email" => ["required", "string", "email", "max:255", "unique:users,email,$id,id"],
                "mobile" => ["required", "string", "max:255", "unique:users,mobile,$id,id"],
                "password" => ["nullable", "string", "min:8", "confirmed"],
                "human_job_id" => ["required", "exists:human_jobs,id"],
                "photo" => ["nullable", "max:2000", "mimes:jpeg,jpg,png"],
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

            $to_save = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile'),
                'human_job_id' => $request->get('human_job_id'),
            ];
            
            if($request->password)
            {
                $to_save['password'] = Hash::make($request->password);
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
                
                $saved_photo = $this->assetRepository->create([
                    'asset_name'        => $photoname,
                    'asset_url'         => $url,
                    'asset_size'        => $photosize,
                    'asset_mime'        => $photomime,
                    'assetable_type'    => 'profile'
                ]);

                $to_save['photo_id'] = $saved_photo->id;
            }

            $user = $this->userRepository->save_localized($to_save, $id);

            // $this->roleRepository->setRoleToMember($user, $userDefaultRole);
            return $this->sendResponse(new UserResource($user), __('Success'));
        }
        catch(\Throwable $th)
        {
            return $this->sendError($th->getMessage(), 500); 
        } 
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/users/{id}",
     *      summary="Remove the specified User from storage",
     *      tags={"User"},
     *      description="Delete User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }
}
