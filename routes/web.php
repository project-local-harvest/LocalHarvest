<?php

use App\Http\Controllers\DeploymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/storage-link', [DeploymentController::class, 'storageLink']);
