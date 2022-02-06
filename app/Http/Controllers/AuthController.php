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
            'username' => 'required|string|unique:users,username|min:4|max:255',
            'email' => 'required|email|string|unique:users,email|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
        ]);

        $user = User::create($attributes);

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
