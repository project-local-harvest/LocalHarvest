<?php

use App\Http\Controllers\Api\Admin\MasterFertilizerController;
use App\Http\Controllers\Api\ShopOwner\ShopInventoryController;
use App\Http\Controllers\Api\ShopOwner\ShopProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
});


Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::post('/create-user', [UserController::class, 'register']);


    Route::get('/fertilizers',[MasterFertilizerController::class, 'index']);
    Route::post('/add-fertilizers',[MasterFertilizerController::class, 'store']);
    Route::get('/fertilizers/{id}',[MasterFertilizerController::class, 'show']);
    Route::post('/fertilizers/edit/{id}',[MasterFertilizerController::class, 'update']);
    Route::delete('/fertilizers/{id}',[MasterFertilizerController::class, 'destroy']);

});


Route::middleware(['auth:sanctum'])->prefix('shop_owner')->group(function () {

    Route::get('/shop-profile', [ShopProfileController::class, 'show']);
    Route::post('/setup-shop-profile', [ShopProfileController::class, 'store']);
    Route::post('/edit-shop-profile', [ShopProfileController::class, 'update']);

    Route::apiResource('inventory', ShopInventoryController::class)->except(['update']);
    Route::post('inventory/{id}', [ShopInventoryController::class, 'update']);
    Route::get('/available-fertilizers', [ShopInventoryController::class, 'getAvailableFertilizers']);
});
