<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;



// JWT-Auth
// use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
// use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum', [
    //         'except' => ['login']
    //     ]);
    // }

    public function login(Request $request) {

        $user = User::query()->where('username', $request->input('username'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages("Unauthorize");
        }

        return $this->respondWithToken($user->createToken('api')->plainTextToken);
    }

    public function logout() {
        if (auth()->check()) {
            auth()->logout();
            return true;
        }
        return false;
    }

    public function refresh(Request $request) {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'user' => auth()->user()
        ]);
    }
}