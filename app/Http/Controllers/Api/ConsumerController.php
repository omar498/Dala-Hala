<?php

namespace App\Http\Controllers\Api;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConsumerResource;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class ConsumerController extends Controller
{



    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phone_number' => 'required|unique:consumers,phone_number',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $consumer = Consumer::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => ' successfully registered',
            'consumer' => $consumer
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }

        $credentials = $request->only('phone_number','password');

        if (!$token = auth()->guard('consumer-api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        $consumer = auth()->guard('consumer-api')->user();

        return response()->json([
            'message' => 'Login successful',
            'consumer'=>new ConsumerResource($consumer),
            'access_token' => $token,
          'token_type' => 'Bearer'

        ],200);
    }

    public function delete_admin(Request $request)
    {
        try{
        $id = $request->input('id');

        $user = Consumer::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully',
        'user'=>new ConsumerResource($user),

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



