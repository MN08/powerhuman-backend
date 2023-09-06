<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ResponsibilityController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//auth API Route
Route::name('auth.')->group(function () {
    Route::post('register', [UserController::class, 'register'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});

//company API Route
Route::prefix('company')->middleware(['auth:sanctum'])->name('company.')->group(function () {
    Route::get('', [CompanyController::class, 'index'])->name('index');
    Route::post('', [CompanyController::class, 'create'])->name('create');
    Route::post('update/{id}', [CompanyController::class, 'update'])->name('update');
});

//team API Route
Route::prefix('team')->middleware(['auth:sanctum'])->name('team.')->group(function () {
    Route::get('', [TeamController::class, 'index'])->name('index');
    Route::post('', [TeamController::class, 'create'])->name('create');
    Route::post('update/{id}', [TeamController::class, 'update'])->name('update');
    Route::delete('{id}', [TeamController::class, 'delete'])->name('delete');
});

//role API Route
Route::prefix('role')->middleware(['auth:sanctum'])->name('role.')->group(function () {
    Route::get('', [RoleController::class, 'index'])->name('index');
    Route::post('', [RoleController::class, 'create'])->name('create');
    Route::post('update/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('{id}', [RoleController::class, 'delete'])->name('delete');
});

//Responsibility API Route
Route::prefix('responsibility')->middleware(['auth:sanctum'])->name('responsibility.')->group(function () {
    Route::get('', [ResponsibilityController::class, 'index'])->name('index');
    Route::post('', [ResponsibilityController::class, 'create'])->name('create');
    Route::post('update/{id}', [ResponsibilityController::class, 'update'])->name('update');
    Route::delete('{id}', [ResponsibilityController::class, 'delete'])->name('delete');
});

//Employee API Route
Route::prefix('employee')->middleware(['auth:sanctum'])->name('employee.')->group(function () {
    Route::get('', [EmployeeController::class, 'index'])->name('index');
    Route::post('', [EmployeeController::class, 'create'])->name('create');
    Route::post('update/{id}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('{id}', [EmployeeController::class, 'delete'])->name('delete');
});
