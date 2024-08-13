<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NilaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('custom.sanctum');

Route::prefix('divisions')->middleware('custom.sanctum')->group(function () {
    Route::get('/', [DivisionController::class, 'index']);
    Route::post('/', [DivisionController::class, 'store']);
    Route::put('/{id}', [DivisionController::class, 'update']);
    Route::delete('/{id}', [DivisionController::class, 'destroy']);
});

Route::prefix('employees')->middleware('custom.sanctum')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/', [EmployeeController::class, 'store']);
    Route::put('/{id}', [EmployeeController::class, 'update']);
    Route::delete('/{id}', [EmployeeController::class, 'destroy']);
});

Route::post('/nilaiRT/bulk-insert', [NilaiController::class, 'bulkInsert']);
Route::get('/nilaiRT', [NilaiController::class, 'index']);
