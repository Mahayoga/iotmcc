<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.index');
});

Route::resource('dashboard', DashboardController::class);
