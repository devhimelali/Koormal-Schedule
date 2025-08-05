<?php

use App\Http\Controllers\TechniciansController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:technician', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('technician.dashboard.index');
    })->name('technician.dashboard');

    Route::get('load-today-works', [TechniciansController::class, 'loadTodayWorks'])->name('technician.load.today.works');
    Route::post('ckeditor.upload', [TechniciansController::class, 'ckeditorUpload'])->name('technician.ckeditor.upload');

    Route::get('get-schedule-by-id/{id}', [TechniciansController::class, 'getScheduleById'])->name('technician.get.schedule.by.id');
});
