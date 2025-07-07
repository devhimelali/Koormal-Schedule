<?php

use App\Http\Controllers\admin\AssetStatusLogController;
use App\Http\Controllers\admin\EmailLogController;
use Carbon\Carbon;
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
use App\Models\AssetStatusLog;

Route::get('/', [RedirectController::class, 'redirect'])->name('redirect')->middleware('auth');
Route::get('{role}/profile', [ProfileController::class, 'show'])->name('profile.show');

// ===================== Route for admin, user =====================
Route::middleware(['auth', 'verified', 'role:admin|user|technician'])->group(function () {
    // Schedules routes
    Route::resource('schedules', ScheduleController::class);
    Route::get('schedules/export/pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.export.pdf');
    Route::get('schedules/export/excel', [ScheduleController::class, 'exportExcel'])->name('schedules.export.excel');
    Route::post('schedules/email', [ScheduleController::class, 'sendEmail'])->name('schedules.email');
    Route::get('technicians', [TechniciansController::class, 'index'])->name('technicians.index');
    Route::post('send-email', [TechniciansController::class, 'sendEmail'])->name('technician.send.email');
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


Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
    Route::resource('status-logs', AssetStatusLogController::class);
    Route::get('status-logs/export/pdf', [AssetStatusLogController::class, 'exportPdf'])->name('status-logs.export.pdf');
    Route::get('status-logs/export/excel', [AssetStatusLogController::class, 'exportExcel'])->name('status-logs.export.excel');
    Route::resource('email-logs', EmailLogController::class);
    Route::get('email-logs/export/pdf', [EmailLogController::class, 'exportPdf'])->name('email-logs.export.pdf');
    Route::get('email-logs/export/excel', [EmailLogController::class, 'exportExcel'])->name('email-logs.export.excel');
});