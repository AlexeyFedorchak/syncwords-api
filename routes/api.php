<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/list', [EventController::class, 'getEvents'])->name('api.event.list');
    Route::get('/{id}', [EventController::class, 'getEvent'])->name('api.event.get');
    Route::put('/{id}', [EventController::class, 'put'])->name('api.event.put');
    Route::patch('/{id}', [EventController::class, 'patch'])->name('api.event.patch');
    Route::delete('/{id}', [EventController::class, 'delete'])->name('api.event.delete');
});
