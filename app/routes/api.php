<?php

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

Route::prefix('/produk')->controller(\App\Http\Controllers\ProdukController::class)
    ->middleware(['auth:api', 'cors'])->group(function(){
    Route::get('/','index');
    Route::post('/store','store');
    Route::get('/{id}','show');
    Route::delete('/{id}','destroy');
    Route::patch('/update/{id}','update');
    Route::get('/printexcel/excel','excel');
});
Route::prefix('/kategori')->controller(\App\Http\Controllers\KategoriController::class)
    ->middleware(['auth:api', 'cors'])->group(function(){
    Route::get('/','index');
    Route::post('/store','store');
    Route::get('/{id}','show');
    Route::delete('/{id}','destroy');
    Route::patch('/update/{id}','update');
});
Route::controller(\App\Http\Controllers\AuthController::class)
    ->middleware(['cors'])
    ->group(function () {
        Route::post('/login', 'login')->name('login.api');
        Route::post('/register', 'register');
        Route::post('/refreshtoken', 'refreshToken')->name('refresh.api');

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/logout', 'logout');
            Route::get('/profile', 'profile');
            Route::patch('/profile/update/{id}', 'update');
        });
    });
    
    Route::fallback(function () {
        return response('Not Found', 404);
    });