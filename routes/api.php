<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\LecturerController;
use App\Http\Controllers\API\ResearchController;
use App\Http\Controllers\API\CreationController;
use App\Http\Controllers\API\JudulPaController;
use App\Models\Lecturer;

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
// Route for admin
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('admin', [AdminController::class, 'index'])->middleware('isAdmin')->name('Admin');
    Route::delete('admin/{id}', [AdminController::class, 'delete'])->middleware('isAdmin')->name('Admin');
    Route::post('dosen/register', [LecturerController::class, 'store'])->middleware('isAdmin')->name('Lecturer');
    Route::post('users', [UserController::class, 'updateProfile'])->name('User');
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('create', [GroupController::class, 'store']);
    // Route::post('admin/register', [AdminController::class, 'register'])->middleware('isAdmin')->name('Admin');
    Route::post('riset/create', [ResearchController::class, 'create']);
    Route::delete('riset/{id}', [ResearchController::class, 'delete']);
    Route::post('riset/{id}', [ResearchController::class, 'update']);
    Route::post('dosen/{id}', [LecturerController::class, 'update']);
    Route::delete('dosen/{id}', [LecturerController::class, 'delete']);
    Route::post('karya/{id}', [CreationController::class, 'update']);
    Route::delete('karya/{id}', [CreationController::class, 'delete']);
});

Route::post('admin/register', [AdminController::class, 'register']);
Route::get('group', [GroupController::class, 'all']);
Route::get('riset', [ResearchController::class, 'all']);
Route::post('login', [UserController::class, 'login']);
Route::post('login-dosen', [LecturerController::class, 'loginDosen']);
Route::get('dosen', [LecturerController::class, 'index']);
Route::get('riset/{id}', [ResearchController::class, 'download']);
Route::get('karya', [CreationController::class, 'all']);
Route::post('karya/create', [CreationController::class, 'create']);
Route::get('judulpa', [JudulPaController::class, 'index']);
Route::post('judulpa/create', [JudulPaController::class, 'create']);
