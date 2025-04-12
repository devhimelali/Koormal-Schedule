<?php

use App\Http\Controllers\TechniciansController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:technician', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('technician.dashboard.index');
    })->name('technician.dashboard');

    Route::get('load-today-works', [TechniciansController::class, 'loadTodayWorks'])->name('technician.load.today.works');
    Route::post('send-email', [TechniciansController::class, 'sendEmail'])->name('technician.send.email');
});
