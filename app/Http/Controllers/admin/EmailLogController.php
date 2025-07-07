<?php

namespace App\Http\Controllers\admin;

use App\Models\EmailLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\EmailLogsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class EmailLogController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = EmailLog::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('asset_no', fn($row) => $row->asset_no ?? 'N/A')
                ->editColumn('next_due_date', fn($row) => $row->next_due_date ?? 'N/A')
                ->editColumn('is_sent', function ($row) {
                    return $row->is_sent
                        ? '<span class="badge bg-success">Success</span>'
                        : '<span class="badge bg-danger">Failed</span>';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="#" data-id="' . $row->id . '" class="btn btn-sm btn-primary view-details">View</a>'; // Replace with your actual action buttons
                })
                ->rawColumns(['is_sent', 'actions'])
                ->make(true);
        }

        return view('admin.email-logs.index');
    }

    public function show($id)
    {
        $log = EmailLog::findOrFail($id);
        return view('admin.email-logs.show', compact('log'));
    }

    public function exportPdf(Request $request)
    {
        $data = EmailLog::get();

        $pdf = Pdf::loadView('pdf.email-logs.index', ['logs' => $data])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        return $pdf->stream('email-logs.pdf');
    }

    public function exportExcel()
    {
        $filename = 'email-log-report-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new EmailLogsExport, $filename);
    }
}
