<?php

use App\Http\Controllers\AuthController;
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

//  Public routes

Route::post('/register', [AuthController::class, 'register']);


//  Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/hello', function () {
        return User::all();
    });
});
