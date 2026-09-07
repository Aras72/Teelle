<?php

use App\Http\Controllers\Homepage\ShowHomepageController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomepageController::class)->name('home');
