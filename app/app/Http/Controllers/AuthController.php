<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAkunRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\AkunResource;
use App\Models\User;
use App\Service\AkunService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected $akun;
    /**
     * @param $akun
     */
    public function __construct(AkunService $akun)
    {
        $this->akun = $akun;
    }

    public function logout (): JsonResponse
    {
        $token = auth('api')->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response()->json([
            'message' => $response
        ]);
    }

    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required'
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException(response([
                'errors'=>'not found'
            ],400));
        }
        $validator = $validator->validated();

        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $validator['refresh_token'],
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),

        ];

        $url = route('passport.token');
        $request->request->add($data);

        $tokenRequest = $request->create(
            $url,
            'post',
        );

        $instance = Route::dispatch($tokenRequest);

        if (!$instance->isSuccessful()) {

            Log::error('OAuth request failed', ['response' => $instance]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return $instance;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            throw new HttpResponseException(response([
                'errors'=>$validator->errors()->all()
            ],422));
        }
        $validator = $validator->validated();
        try {
            $user = User::where('email', $validator['email'])->firstOrFail();
            if (Hash::check($validator['password'], $user->password)) {

                $data = [
                    'grant_type' => config('services.passport.grant_type'),
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $validator['email'],
                    'password' => $validator['password'],
                ];

                $request->request->add($data);
                $url = url('/oauth/token');
                $tokenRequest = Request::create($url, 'post', $data);
                $instance = Route::dispatch($tokenRequest);
                if (!$instance->isSuccessful()) {
                    Log::error('OAuth request failed', ['response' => $instance]);
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                $responseData = json_decode($instance->getContent(), true);
                return response()->json([
                    'access_token' => $responseData['access_token'],
                    'refresh_token' => $responseData['refresh_token'],
                    'token_type' => $responseData['token_type'],
                    'expires_in' => $responseData['expires_in'],
                    'user' => new AkunResource($user),
                ])->setStatusCode(200);
            }
            else {
                $response = ["message" => "Password mismatch"];
                return response()->json([
                    $response
                ])->setStatusCode(422);
            }
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            $response = ["message" =>'User does not exist'];
            return response()->json([
                $response
            ])->setStatusCode(422);
        }

    }

    public function profile():AkunResource
    {
        $user_id = auth('api')->user();
        return (new AkunResource($user_id));
    }

    public function update(UpdateProfileRequest $request,$id)
    {
        $request = $request->validated();
        $request = $this->akun->update($request,$id);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);    
    }

    public function register(StoreAkunRequest $request)
    {
        $request = $request->validated();
        $response = $this->akun->storeData($request);
        return response()->json([
            'message' => $response
        ])->setStatusCode(201);
    }
}
