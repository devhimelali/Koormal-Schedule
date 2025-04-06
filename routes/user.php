<?php

use App\Http\Controllers\User\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:user', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('user.dashboard.index');
    })->name('user.dashboard');
});

Route::resource('schedules', ScheduleController::class);
Route::get('schedules/export/pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.export.pdf');
Route::post('schedules/email', [ScheduleController::class, 'sendEmail'])->name('schedules.email');
