<?php

namespace API\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use API\User;
use JWTAuth;

class AuthenticateController extends Controller
{
    public function authJWT(Request $request)
    {
        $data = $request->only('email', 'password');

        $user = User::where('email', $data['email'])->first();

        if (count($user) > 0 && Hash::check($data['password'], $user->password)) {
            $token = JWTAuth::fromUser($user);
            return response()->json(['status' => 'success', 'data' => ['token' => $token]], 200);
        }

        return response()->json(['status' => 'fail', 'data' => ['email' => 'email is required', 'password' => 'password is required']], 401);
    }
}
