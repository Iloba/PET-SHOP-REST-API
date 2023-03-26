<?php

use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;

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

// Route::middleware('jwt.auth')->get('/user', function (Request $request) {
//     $adminHelper = new AdminHelper();
//     $user = $adminHelper->GetAuthUser();
//     return response()->json(['data' => $user], 200);
// });


// Route::get('/home', [HomeController::class, 'home'])->middleware('jwt.auth');

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login'])->name('login');

//User normal Endpoints
Route::group(['prefix' => 'v1'], function () {
    Route::post('/user/create', [UserController::class, 'create'])->name('create.user');
});
// Route::post('/create', [UserController::class, 'create'])->name('create.user');
//User Secured Endpoints
// Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth']], function () {
   
//   });