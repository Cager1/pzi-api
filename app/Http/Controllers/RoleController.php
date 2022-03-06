<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Role[]
     */
    public function index()
    {
        //
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $role = Role::create($request->all());
        return response()->json([
            "role" => $role,
            "message" => "Uloga ".$role->name." kreirana"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (Role::find($id)) {
            $role = Role::find($id);
            return respone()->json([
                "role" => $role,
                "message" => "Uloga ".$role->name." pronađena"
            ]);
        } else {
            return response()->json([
                'message' => 'Tražena uloga nije pronađena'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $name)
    {
        if (Role::where('name', $name)->first()) {
            Role::where('name', $name)->first()->update($request->all());
            $role = Role::where('name', $request->name)->first();
            return response()->json([
                'role' => $role,
                'message' => "Uloga ".$name." je podešena u ".$role->name
            ]);
        } else {
            return response()->json([
                'message' => "Update: Requested role does no exist",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($name)
    {
        if (Role::where('name', $name)->first()) {
            $role = Role::where('name', $name)->first();
            Role::destroy($role->id);
            return response()->json([
                'message' => $name." uloga izbrisana",
            ]);
        } else {
            return response()->json([
                'message' => $name.' uloga ne postoji',
            ]);
        }
    }
}
