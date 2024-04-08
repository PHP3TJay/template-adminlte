<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\Module;
use Illuminate\Support\Facades\Cache;



// Route::get('/', function () {
//     if (Auth::check()) {
//         return redirect('/home');
//     }
//     return redirect()->route('login');
// });

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/account-helper', [AuthController::class, 'account_helper'])->name('login');
Route::post('/forgotUsernameRequest', [AuthController::class, 'forgotUsernameRequest']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {

    // ******** DashboardController **********//
    Route::namespace('Dashboard')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
    
    // ******** TeamController **********//
    Route::namespace('Team')->group(function () {
        //Route::get('/team', [TeamController::class, 'index'])->name('team');
        Route::post('/team/save', [TeamController::class, 'save_team'])->name('team.save_team');
        Route::put('/team/edit', [TeamController::class, 'update'])->name('team.edit_team');
        Route::delete('/team/delete/{id}', [TeamController::class, 'delete'])->name('team.delete_team');
        Route::get('/getTeam', [TeamController::class, 'getTeam'])->name('getTeam');
        Route::get('/getTeamById/{id}', [TeamController::class, 'getTeamById'])->name('getTeamById');
        Route::put('/team/updatePosition', [TeamController::class, 'updatePosition']);
        Route::get('/team/{id}', [TeamController::class, 'view_team'])->name('team');
        Route::get('/getTeamUsers', [TeamController::class, 'getTeamUsers']);
        Route::post('/saveTeamUsers', [TeamController::class, 'saveTeamUsers']);
        Route::get('/get-team-position', [TeamController::class, 'getTeamPositions']);
        Route::post('/removeUser/{id}/{user_id}', [TeamController::class, 'removeUser']);
    });
    
    // ******** UserController **********//
    Route::namespace('User')->group(function () {
        //Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user/create', [UserController::class, 'create']);
        Route::get('/getUsers', [UserController::class, 'getUsers'])->name('getUsers');
        Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
        Route::put('/saveChanges', [UserController::class, 'updateUser']);
        Route::get('/user/data', [UserController::class, 'getUsersData'])->name('user.data');
        Route::get('/getUsersForTeam/{team_id}', [UserController::class, 'getUsersForTeam']);
    
    });

    // ******** CoachingController **********//
    Route::namespace('Coaching')->group(function () {
        Route::get('/coaching', [CoachingController::class, 'index'])->name('coaching');
        Route::get('/coaching2', [CoachingController::class, 'coaching2'])->name('coaching2');
        Route::post('/coaching/create', [CoachingController::class, 'create'])->name('coaching.create');
        Route::get('/getCoachingLogDetailById/{id}', [CoachingController::class, 'getCoachingLogDetailById']);
        Route::get('/coaching/print/{id}', [CoachingController::class, 'getPrint']);
        Route::put('/coaching/saveChanges/', [CoachingController::class, 'saveChanges']);
        Route::put('/coaching/acceptCoaching', [CoachingController::class, 'acceptCoaching']);
        Route::put('/coaching/cancelCoaching/{id}', [CoachingController::class, 'cancelCoaching']);
        Route::put('/coaching/declineCoaching/{id}/{reason}', [CoachingController::class, 'declineCoaching']);
        Route::put('/coaching/completeCoaching', [CoachingController::class, 'completeCoaching']);
        Route::get('/getCoachingData/{type?}', [CoachingController::class, 'getCoachingData']);
        Route::get('/getCoachingData2', [CoachingController::class, 'getCoachingData2']);
    });

    // ******** AuthController **********//
    Route::namespace('Auth')->group(function () {
        Route::put('/reset/{user_id}', [AuthController::class, 'adminResetPassword']);
        Route::put('/unlock/{username}', [AuthController::class, 'unlockAccount']);
        Route::get('/logout', [AuthController::class, 'logout']);   
        Route::get('/password-reset', function () { return view('auth.password_reset'); })->name('login');
        Route::post('/change_password', [AuthController::class, 'change_password']);
        
    });


    // ******** DynamicController **********//
    $minutes = 60;
    $modules = Cache::remember('modules', $minutes, function () {
        return Module::all();
    });

    foreach ($modules as $module) {
        $method = strtoupper($module->method);
        $controllerClass = 'App\\Http\\Controllers\\' . $module->controller;
        switch ($method) {
            case 'GET':
                Route::get($module->path, [$controllerClass, $module->function])->name(trim($module->path, '/'));
                break;

            case 'POST':
                Route::post($module->path, [$controllerClass, $module->function]);
                break;

            case 'PUT':
                Route::put($module->path, [$controllerClass, $module->function]);
                break;

            case 'DELETE':
                Route::delete($module->path, [$controllerClass, $module->function]);
                break;
            default:
                break;
        }
    }

    
    // ******** TestController **********//
    Route::get('/test-user/{offset}/{limit}', [TestController::class, 'index']);
    Route::get('/test-user-ptc/{offset}/{limit}', [TestController::class, 'test_user_ptc']);
    Route::get('/test-user-upper/{offset}/{limit}', [TestController::class, 'test_user_upper']);
    Route::get('/final-user/{offset}/{limit}', [TestController::class, 'final_user']);
    Route::get('/email-accounts/{offset}/{limit}', [TestController::class, 'emailAccounts']);
    Route::get('/roles', [TestController::class, 'roles']);
});



Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
Route::get('/reset/{reset_token}', [AuthController::class, 'passwordResetLink'])->name('reset');

Route::get('/check-database', [TestController::class, 'checkDatabaseConnection']);
Route::get('/get-mypat-site-address', [UserController::class, 'get_mypat_site_address']);

