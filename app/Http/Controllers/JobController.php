<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Job[]|\Illuminate\Database\Eloquent\Collection|Response
     */
    public function index()
    {
        return Job::all()->load('services','user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $services = $request->selection;
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:1000',
        ]);
        $job = Job::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
        ]);
        foreach ($services as $service) {
            $job->services()->attach($service);
        }
        return $job->load('user');
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
            return $job->load('services','user');
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
            $job = Job::find($id);
            $user = Auth::user();
            if ($user->can('update', $job)) {
                return response()->json([
                    "job" => $job->update($request->all()),
                    'message' => 'Posao "'.$job->name.'" pode??en'
                ]);
            } else {
                throw new AccessDeniedHttpException('Ne mo??ete pode??avati tu??e poslove.');
            }
        } else {
            return response()->json([
                'message' => 'Ovaj posao ne postoji.',
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
            $user = Auth::user();
            if ($user->can('update', $job)) {
                $name = $job->name;
                Job::destroy($job->id);
                return response()->json([
                    'message' => 'Posao "'.$name.'" obrisan',
                ]);
            } else {
                throw new AccessDeniedHttpException('Ne mo??ete brisati tu??e poslove.');
            }
        } else {
            return response()->json([
                'message' => 'Ovaj posao ne postoji.',
            ]);
        }
    }

    public function adminUpdate(Request $request, $id)
    {
        $services = $request->selection;
        $job = Job::find($id);

        if (Job::find($id)) {
            if ($request->selection) {
                $job->services()->wherePivot('job_id','=',$job->id)->detach();
                foreach ($services as $service) {
                    $job->services()->attach($service);
                }
            }
            return response()->json([
                "job" => $job->update($request->all()),
                'message' => 'Posao "'.$job->name.'" pode??en'
            ]);
        } else {
            return response()->json([
                'message' => 'Ovaj posao ne postoji.',
            ]);
        }
    }

    public function adminDestroy($id)
    {

        if (Job::find($id)) {
            $job = Job::find($id);
            $name = $job->name;
            Job::destroy($job->id);
            return response()->json([
                'message' => $name.' job deleted',
            ]);
        } else {
            return response()->json([
                'message' => 'Ovaj posao ne postoji.',
            ]);
        }
    }
}
