<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $files = Storage::files('backup');

            // Map files to desired structure
            $data = collect($files)->map(function ($file) {
                return [
                    'filename' => basename($file),
                    'size'     => $this->formatBytes(Storage::size($file)),
                    'backup_at'  => Carbon::createFromTimestamp(File::lastModified(Storage::path($file)))
                        ->format('d-m-Y h:i A'),
                    'download' => route('database.download', ['name' => basename($file)]),
                ];
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . $row['download'] . '" class="btn btn-sm btn-danger">
                        <i class="bi bi-download me-2"></i>Download
                    </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.database.index');
    }

    public function download($name)
    {
        return Storage::download('backup/' . $name);
    }

    public function create()
    {
        // create database backup
        Artisan::call('app:daily-db-backup');
        return redirect()->back()->with('success', 'Database backup created successfully');
    }

    /**
     * Convert bytes to a human-readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
