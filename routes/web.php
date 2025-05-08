<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\TechniciansController;
use App\Http\Controllers\User\ScheduleController;

Route::get('/', [RedirectController::class, 'redirect'])->name('redirect')->middleware('auth');
Route::get('{role}/profile', [ProfileController::class, 'show'])->name('profile.show');

// ===================== Route for admin, user =====================
Route::middleware(['auth', 'verified', 'role:admin|user|technician'])->group(function () {
    // Schedules routes
    Route::resource('schedules', ScheduleController::class);
    Route::get('schedules/export/pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.export.pdf');
    Route::post('schedules/email', [ScheduleController::class, 'sendEmail'])->name('schedules.email');
    Route::get('technicians', [TechniciansController::class, 'index'])->name('technicians.index');
    Route::get('4emus-contact', function () {
        return view('4emus_contact');
    })->name('4emus.contact');
    Route::get('koormal-contact', function () {
        return view('koormal_contact');
    })->name('koormal.contact');
});

Route::post('technicians/change-status', [TechniciansController::class, 'changeStatus'])->name('technicians.change.status')->middleware(['auth', 'role:admin|technician', 'verified']);

Route::get('run-job', function () {
    Artisan::call('queue:work');

    return response()->json(['message' => 'Job started successfully']);
})->middleware('secure.cron');

// Route::get('/run-db-cron-job', [CronJobController::class, 'runDbCronJob'])->middleware('secure.cron');
Route::get('/run-db-cron-job', [CronJobController::class, 'runDbCronJob']);
