<?php

use App\Http\Controllers\TechniciansController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:technician', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('technician.dashboard.index');
    })->name('technician.dashboard');

    Route::get('load-today-works', [TechniciansController::class, 'loadTodayWorks'])->name('technician.load.today.works');
    Route::post('send-email', [TechniciansController::class, 'sendEmail'])->name('technician.send.email');
    Route::post('add-asset', [TechniciansController::class, 'addAsset'])->name('technician.add.asset');
    Route::get('edit-asset/{id}', [TechniciansController::class, 'editAsset'])->name('technician.edit.asset');
    Route::put('update-asset/{id}', [TechniciansController::class, 'updateAsset'])->name('technician.update.asset');
    Route::delete('delete-asset/{id}', [TechniciansController::class, 'deleteAsset'])->name('technician.delete.asset');
    Route::post('ckeditor.upload', [TechniciansController::class, 'ckeditorUpload'])->name('technician.ckeditor.upload');
    Route::get('schedule-list', [TechniciansController::class, 'scheduleList'])->name('technician.schedule.list');
    Route::get('get-schedule-by-id/{id}', [TechniciansController::class, 'getScheduleById'])->name('technician.get.schedule.by.id');
});
