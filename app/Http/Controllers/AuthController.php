<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => __('messages.unauthorized')], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        $token = $user->createToken(config('app.token_name'))->plainTextToken;

        return response()->json([
            'message' =>  __('messages.successful_authentication'),
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
