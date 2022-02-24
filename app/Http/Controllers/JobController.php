<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Job[]|\Illuminate\Database\Eloquent\Collection|Response
     */
    public function index()
    {
        return Job::all()->load('service','user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:1000',
        ]);
        $job = Job::create($request->all());
        return $job->load('service','user');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (Job::find($id)) {
            $job = Job::find($id);
            return $job->load('service','user');
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
     * @param  \App\Models\Job  $jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Job::find($id)) {
            return Job::find($id)->update($request->all());
        } else {
            return response()->json([
                'message' => 'Update: Requested job does not exist',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Job::find($id)) {
            $job = Job::find($id);
            $name = $job->name;
            Job::destroy($job->id);
            return resonse()->json([
                'message' => $name.' job deleted',
            ]);
        } else {
            return response()->json([
                'message' => 'Delete: Requested job does not exist',
            ]);
        }
    }
}
