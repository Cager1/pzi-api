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
            'role_id' => 'numeric|lt:4|gt:1',
        ]);

        var_dump($attributes);

        $user = User::create($attributes);

        $response = [
            'user' => $user,
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
                    'E-mail' => [
                        'Netočni email ili zaporka'
                    ],
                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user_role = $user->role;

        $authToken = $user->createToken('auth-token', [$user_role->name])->plainTextToken;

        return response()->json([
            'user' => $user->jobs,
            'jobs' => $user->jobs,
            'access_token' => $authToken,
        ]);
    }

    public function getUser(Request $request) {
        $authUser = auth()->user();
        $user = User::find($authUser->id);
        return \response()->json([
            'role' => $user->role,
            'user' => $user,
            'jobs' => $user->jobs,
        ]);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return ['message' => 'Logged out', auth()];
    }

    public function adminRegister(Request $request) {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users,email|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
            'role_id' => 'numeric',
        ]);

        var_dump($attributes);

        $user = User::create($attributes);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }
}
