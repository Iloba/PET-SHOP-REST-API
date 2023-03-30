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


//User normal Endpoints
Route::group(['prefix' => 'v1'], function () {
    Route::post('/user/create', [UserController::class, 'store'])->name('create.user');
    Route::post('/user/login', [UserController::class, 'login'])->name('login.user');
    Route::post('admin/create', [AdminController::class, 'store'])->name('create.admin');
    Route::post('admin/login', [AdminController::class, 'login'])->name('login.admin');
});

//User Secured Endpoints
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth']], function () {
    Route::get('user/logout', [UserController::class, 'logout'])->name('logout.user');
    Route::put('user/edit', [UserController::class, 'editUser'])->name('edit.user');
    Route::get('user', [UserController::class, 'profile'])->name('user.profile');
    Route::delete('user', [UserController::class, 'delete'])->name('user.delete');

});

//Admin Middleware and Route
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth', 'admin']], function () {
    Route::get('admin/logout', [AdminController::class, 'logout'])->name('logout.admin');
    Route::get('admin/user-listing', [AdminController::class, 'users'])->name('users.listing');
    Route::put('admin/user-edit/{uuid}', [AdminController::class, 'editUser'])->name('admin.edit.user');
    Route::delete('admin/user-delete/{uuid}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
});
