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
        $users = User::with('role')->get();
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
        //
    }

    public function update(Request $request, $email)
    {
        if (User::where('email', $email)->first()) {
            return User::where('email', $email)->first()->update($request->all());
        } else {
            return response()->json([
                'message' => "Update: User doesn't exist",
            ]);
        }
    }

    public function destroy($email)
    {
        if (User::where('email', $email)->first()) {
            $user = User::where('name', $email)->first();
            $name = $user->name;
            User::destroy($user->id);
            return response()->json([
                'message' => $name." role deleted",
            ]);
        } else {
            return response()->json([
                'message' => 'Delete: Requested role does not exist',
            ]);
        }
    }


}
