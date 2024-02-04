<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'authLogin']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


// forgot password and reset password
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot');
Route::post('forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot');
Route::get('reset/{token}', [AuthController::class, 'reset'])->name('reset');
Route::post('reset/{token}', [AuthController::class, 'postReset'])->name('reset');


Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/admin/list', [AdminController::class, 'list'])->name('admin.list');
    Route::get('admin/add', [AdminController::class, 'add'])->name('admin.add');
    Route::post('admin/add', [AdminController::class, 'insert'])->name('admin.add');
    Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('admin/edit/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::get('admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
});

Route::group(['middleware' => 'teacher'], function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');
});

Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard'])->name('student.dashboard');
});

Route::group(['middleware' => 'parent'], function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard'])->name('parent.dashboard');
});
