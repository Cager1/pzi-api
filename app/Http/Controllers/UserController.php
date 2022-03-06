<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $users = User::with('role', 'jobs')->get();
        return response()->json([
            $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (User::find($id)) {
            return User::find($id)->load('jobs', 'role');
        } else {
            return response()->json([
                'message' => 'Ovaj korisnik ne postoji'
            ]);
        }
    }

    public function update(Request $request, $email)
    {
        if (User::where('email', $email)->first()) {
            User::where('email', $email)->first()->update($request->all());
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'user' => $user,
                'message' => "Korisnik ".$user->name." je podešen"
            ]);
        } else {
            return response()->json([
                'message' => "Ovaj korisnik ne postoji",
            ]);
        }
    }

    public function destroy($email)
    {
        if (User::where('email', $email)->first()) {
            $user = User::where('email', $email)->first();
            $name = $user->name;
            User::destroy($user->id);
            return response()->json([
                'message' => "Korisnik ".$name."  je uklonjen.",
            ]);
        } else {
            return response()->json([
                'message' => 'Korisnik ne postoji.',
            ]);
        }
    }


}
