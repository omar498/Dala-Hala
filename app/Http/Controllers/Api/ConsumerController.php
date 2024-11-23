<?php

namespace App\Http\Controllers\Api;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\ConsumerResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class ConsumerController extends Controller
{



    public function register(UserRegisterRequest $request) {
        $validator =$request->validated();
        $validator['password'] = bcrypt($validator['password']);
        $consumer = Consumer::create($validator);
        return response()->json([
            'message' => ' successfully registered',
            'data' => $consumer
        ], 201);
    }


    public function login(UserLoginRequest $request)
    {
        $validator =$request->validated();

        $validator = $request->only('phone_number','password');

        if (!$token = auth()->guard('consumer-api')->attempt($validator)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        $consumer = auth()->guard('consumer-api')->user();

        return response()->json([
            'message' => 'Login successful',
            'data'=>new ConsumerResource($consumer),
            'access_token' => $token,

        ],200);
    }

    public function delete_user(Request $request)
    {
        try{
        $id = $request->input('id');

        $user = Consumer::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully',
        'data'=>new ConsumerResource($user),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->header('Authorization');


        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $token]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        }

    }
    public function logout(Request $request)
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out, please try again'], 500);
        }
    }

}



