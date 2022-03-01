<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Events\EventController;
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

Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');

    Route::get('/events/list', [EventController::class, 'getEvents'])->name('api.event.list');
    Route::get('/events/{id}', [EventController::class, 'getEvent'])->name('api.event.get');
    Route::put('/event/{id}', [EventController::class, 'put'])->name('api.event.put');
    Route::patch('/event/{id}', [EventController::class, 'patch'])->name('api.event.patch');
    Route::delete('/event/{id}', [EventController::class, 'delete'])->name('api.event.delete');
});
