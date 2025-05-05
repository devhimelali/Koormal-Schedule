<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DatabaseBackupController;

Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');
    Route::get('create-database-backup', [DatabaseBackupController::class, 'create'])->name('database.backup.create');
    Route::get('database-backups', [DatabaseBackupController::class, 'index'])->name('database.backups.index');
    Route::get('download-database-backup/{name}', [DatabaseBackupController::class, 'download'])->name('database.download');
});
