<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        return Service::with('children')->whereNull('parent_id')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable',
        ]);
        return Service::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        if (Service::find($id)) {
            return Service::find($id);
        } else {
            return response()->json([
                'message' => 'Show: There is no service with id of '.$id.' in database'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Service::find($id)) {
            return Service::find($id)->update($request->all());
        }else {
            return response()->json([
                'message' => "Update: Requested service does not exist",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service) {
            $name = $service->name;
            Service::destroy($service->id);
            return response()->json([
                'message' => $name.' service has been deleted',
            ]);
        } else {
            return response()->json([
                'message' => 'Delete: There is no service with id of '.$id.' in database'
            ]);
        }
    }
}
