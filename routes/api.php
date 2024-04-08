<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create', [UserController::class, 'create']);
Route::put('/updatePassword/{user_id}/{password}', [UserController::class, 'updatePassword']);
Route::put('/updateUser', [UserController::class, 'updateUser']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
Route::put('/deactivateUser', [UserController::class, 'deactivateUser']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::put('/resetPassword', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['web', 'throttle:login']], function () {
    Route::post('/login', [AuthController::class, 'login']);
});


Route::get('/getCoachingData', [CoachingController::class, 'getCoachingData']);
//Route::get('/getCoachingData2', [CoachingController::class, 'getCoachingData2']);
Route::get('/getUsersWithTeam', [CoachingController::class, 'getUsersWithTeam']);
Route::get('/getCoachingLogDetailById/{id}', [CoachingController::class, 'getCoachingLogDetailById']);
Route::get('/getCoachingData2_old' , [CoachingController::class, 'getCoachingData2_old']);
