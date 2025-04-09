<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:technician', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('technician.dashboard.index');
    })->name('technician.dashboard');
});
