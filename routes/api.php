<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\UserController;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//  Public routes Guest auth -> :
//                              View posts
//                              Register as user

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordController::class, 'reset']);

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/job/{id}', [JobController::class, 'show']);

Route::get('/user/{id}', [UserController::class, 'show']);



//  Protected routes User -> :
//                            Make requests for service
//                            Make an appointment
//                            Send a message to the service technician
//                            View and update personal data
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
});

// Protected routes Service Technician -> :
//                                         View and update personal data
//                                         View messages and service requests -> archive requests and messages
//                                         Accept or decline service requests

Route::group(['middleware' => ['auth:sanctum', 'ability:Serviser,Admin']], function () {
    Route::resource('services', ServiceController::class);
    Route::post('/job', [JobController::class, 'store']);

    Route::put('/job/{id}', [JobController::class, 'update']);
    Route::delete('/job/{id}', [JobController::class, 'destroy']);
});


// Protected routes Administrator -> :
//                                    Give or take permissions from users
//                                    Update any data in the system (database)jobs
//                                    Upkeep, backup and restore database

Route::group(['middleware' => ['auth:sanctum', 'ability:Admin']], function () {
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/users', UserController::class);

    Route::post('admin/register', [AuthController::class, 'adminRegister']);
    Route::put('admin/job/{id}', [JobController::class, 'adminUpdate']);
    Route::delete('admin/job/{id}', [JobController::class, 'adminDestroy']);
});

