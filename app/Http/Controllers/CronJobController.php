<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronJobController extends Controller
{
    public function runDbCronJob()
    {
        Artisan::call('app:daily-db-backup');
        $output = Artisan::output();
        return response()->json([
            'status' => 'success',
            'message' => 'Database backup command executed successfully!',
            'log'   => $output
        ]);
    }
}
