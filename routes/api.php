<?php

use App\Helpers\AdminHelper;
use App\Http\Controllers\Auth\AdminController;
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
    Route::post('/user/create', [UserController::class, 'store'])->name('create.user');
    Route::post('/user/login', [UserController::class, 'login'])->name('login.user');
    Route::post('admin/create', [AdminController::class, 'store'])->name('create.admin');
    Route::post('admin/login', [AdminController::class, 'login'])->name('login.admin');
});

//User Secured Endpoints
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth']], function () {
    Route::post('/user/logout', [UserController::class, 'logout'])->name('logout.user');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('logout.admin');
});
