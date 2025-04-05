<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [RedirectController::class, 'redirect'])->name('redirect')->middleware('auth');
Route::get('{role}/profile', [ProfileController::class, 'show'])->name('profile.show');

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
