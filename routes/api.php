<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
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


//  Protected routes User -> :
//                            Make requests for service
//                            Make an appointment
//                            Send a message to the service technician
//                            View and update personal data
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Protected routes Service Technician -> :
//                                         View and update personal data
//                                         View messages and service requests -> archive requests and messages
//                                         Accept or decline service requests

Route::group(['middleware' => ['auth:sanctum', 'ability:service-technician,admin']], function () {
});


// Protected routes Administrator -> :
//                                    Give or take permissions from users
//                                    Update any data in the system (database)
//                                    Upkeep, backup and restore database

Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    Route::resource('roles', RoleController::class);
});

