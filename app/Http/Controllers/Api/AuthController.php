<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        $validator = $request->validated();

        $validator = $request->only('email','password');
        if (!$token = auth()->guard('api')->attempt($validator)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        $user = auth()->guard('api')->user();
        return response()->json([
            'message' => 'Login successful',
            'data'=>new UserResource($user),
            'access_token' => $token,
          'token_type' => 'Bearer'

        ],200);

    }

    public function register(AdminRegisterRequest $request) {
        $validator =$request->validated();
         $user = User::create(array_merge(
        $validator,
        ['password' => bcrypt($validator['password'])] // Hash the password
    ));
        return response()->json([
            'message' => 'User successfully registered',
            'data' => new UserResource($user)
        ], 201);
}




    public function delete_admin(Request $request)
    {
        try{
        $id = $request->input('id');

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Admin deleted successfully',
        'data'=>new UserResource($user),

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
            $refreshToken = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $refreshToken]);
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
