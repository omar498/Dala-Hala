<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{

    public function register(UserRegisterRequest $request){
        $validateDate = $request->validated();
        $user=User::create([
            'name'=>$validateDate['name'],
            'email'=>$validateDate['email'],
            'password'=>bcrypt( $validateDate['password']),
        ]);
        $token = auth('api')->login($user);
        return $this->respondWithToken($token);

    }
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth::refresh());
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth('api')->factory()->getTTL() * 60
        ]);
    }
}