<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function loginn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }

        $credentials = $request->only('email','password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        $user = auth()->guard('api')->user();
        return response()->json([
            'message' => 'Login successful',
            'consumer'=>new UserResource($user),
            'access_token' => $token,
          'token_type' => 'Bearer'

        ],200);

    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    //  public function login()
    // {
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth('api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token);

    // }

    public function delete_admin(Request $request)
    {
        try{
        $id = $request->input('id');

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Admin deleted successfully',
        'user'=>new UserResource($user),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Admin not found'], 404);
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
