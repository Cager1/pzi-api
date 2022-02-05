<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request) {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users,email|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
        ]);

        $user = User::create($attributes);

        $token = $user->createToken('userToken', [''])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email'=>'email|required',
            'password'=>'required',
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data is invalid',
                'errors' => [
                    'password' => [
                        'Invalid Credentials'
                    ],
                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
        ]);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return ['message' => 'Logged out', auth()];
    }
}
