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
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        return Role::create($request->all());
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
            return Role::find($id);
        } else {
            return response()->json([
                'message' => 'Show: There is no role with id of '.$id.' in database'
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
            return Role::where('name', $name)->first()->update($request->all());
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
                'message' => $name." role deleted",
            ]);
        } else {
            return response()->json([
                'message' => 'Delete: Requested role does not exist',
            ]);
        }
    }
}
