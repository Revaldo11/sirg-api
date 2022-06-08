<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\JudulPaController;
use App\Http\Controllers\API\CreationController;
use App\Http\Controllers\API\LecturerController;
use App\Http\Controllers\API\ResearchController;

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


// Route needed for authentication
Route::middleware(['auth:sanctum'])->group(function () {

    // Route admin
    Route::get('admin', [AdminController::class, 'index'])->middleware('isAdmin')->name('Admin');
    Route::delete('admin/{id}', [AdminController::class, 'delete'])->middleware('isAdmin')->name('Admin');
    Route::post('dosen/register', [LecturerController::class, 'store'])->middleware('isAdmin')->name('Lecturer');
    Route::post('admin/register', [AdminController::class, 'register'])->middleware('isAdmin')->name('Admin');
    // Route user
    Route::post('users', [UserController::class, 'updateProfile'])->name('User');
    Route::post('logout', [UserController::class, 'logout']);
    // Route group 
    Route::post('create', [GroupController::class, 'store']);
<<<<<<< HEAD
    Route::post('admin/register', [AdminController::class, 'register'])->middleware('isAdmin')->name('Admin');
=======
    // Route research
>>>>>>> origin/develop
    Route::post('riset/create', [ResearchController::class, 'create']);
    Route::delete('riset/{id}', [ResearchController::class, 'delete']);
    Route::post('riset/{id}', [ResearchController::class, 'update']);
    // Route lecturer
    Route::post('dosen/{id}', [LecturerController::class, 'update']);
    Route::delete('dosen/{id}', [LecturerController::class, 'delete']);
    // Route Creation
    Route::post('karya/{id}', [CreationController::class, 'update']);
    Route::delete('karya/{id}', [CreationController::class, 'delete']);
});

<<<<<<< HEAD
// Route::post('admin/register', [AdminController::class, 'register']);
Route::get('group/{id}', [GroupController::class, 'all']);
=======

// Route group
Route::get('group', [GroupController::class, 'all']);
// Route research
>>>>>>> origin/develop
Route::get('riset', [ResearchController::class, 'all']);
Route::get('riset/{filename}', [ResearchController::class, 'download']);
// Route authentication
Route::post('login', [UserController::class, 'login']);
Route::post('login-dosen', [LecturerController::class, 'loginDosen']);
// Route dosen
Route::get('dosen', [LecturerController::class, 'index']);
<<<<<<< HEAD
Route::get('riset/{id}', [ResearchController::class, 'download']);
Route::get('karya', [CreationController::class, 'all']);
Route::post('karya/create', [CreationController::class, 'create']);
Route::get('judul-pa', [JudulPaController::class, 'index']);
Route::post('judul-pa/create', [JudulPaController::class, 'create']);
=======
// Route creation
Route::get('karya', [CreationController::class, 'all']);
Route::post('karya/create', [CreationController::class, 'create']);
// Route judul
Route::get('judulpa', [JudulPaController::class, 'index']);
Route::post('judulpa/create', [JudulPaController::class, 'create']);
>>>>>>> origin/develop
