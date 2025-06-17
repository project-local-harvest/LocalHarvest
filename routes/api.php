<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Api\Admin\MasterFertilizerController;
use App\Http\Controllers\ShopOwnerAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::post('admin/logout', [AdminAuthController::class, 'logout']);

Route::post('shop_owner/login', [ShopOwnerAuthController::class, 'login']);
Route::post('shop_owner/logout', [ShopOwnerAuthController::class, 'logout']);


Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::post('/create-user', [UserController::class, 'register']);


    Route::get('/fertilizers',[MasterFertilizerController::class, 'index']);
    Route::post('/add-fertilizers',[MasterFertilizerController::class, 'store']);
    Route::get('/fertilizers/{id}',[MasterFertilizerController::class, 'show']);
    Route::post('/fertilizers/edit/{id}',[MasterFertilizerController::class, 'update']);
    Route::delete('/fertilizers/{id}',[MasterFertilizerController::class, 'destroy']);

});
