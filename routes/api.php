<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MasterFertilizerController;
use App\Http\Controllers\Api\ShopOwner\ShopInventoryController;
use App\Http\Controllers\Api\ShopOwner\ShopProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);

Route::get('/fertilizers', [ConsumerController::class, 'listFertilizers']);
Route::get('/fertilizers/{id}', [ConsumerController::class, 'fertilizerDetails']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    Route::get('/shops', [ShopProfileController::class, 'listShops']);
    Route::get('/fertilizers',[MasterFertilizerController::class, 'index']);
    Route::post('/add-fertilizers',[MasterFertilizerController::class, 'store']);
    Route::get('/fertilizers/{id}',[MasterFertilizerController::class, 'show']);
    Route::post('/fertilizers/edit/{id}',[MasterFertilizerController::class, 'update']);
    Route::delete('/fertilizers/{id}',[MasterFertilizerController::class, 'destroy']);

    Route::post('/create-user', [UserController::class, 'register']);

    Route::get('/admin-dashboard-summary', [DashboardController::class, 'adminOverview']);
    Route::patch('/shops/{id}/toggle-status', [ShopProfileController::class, 'toggleStatus']);
});

Route::middleware(['auth:sanctum'])->prefix('shop_owner')->group(function () {
    Route::get('/shop-profile', [ShopProfileController::class, 'show']);
    Route::post('/setup-shop-profile', [ShopProfileController::class, 'store']);
    Route::post('/edit-shop-profile', [ShopProfileController::class, 'update']);

    Route::apiResource('inventory', ShopInventoryController::class)->except(['update']);
    Route::post('inventory/{id}', [ShopInventoryController::class, 'update']);

    Route::get('/available-fertilizers', [ShopInventoryController::class, 'getAvailableFertilizers']);
    Route::get('/shop-dashboard-summary', [DashboardController::class, 'inventorySummary']);

});
