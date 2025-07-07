<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\PumpSchedule;
use Illuminate\Http\Request;
use App\Models\TruckSchedule;
use App\Models\AssetStatusLog;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StatusLogExport;
use App\Http\Controllers\Controller;
use App\Models\LightVehicleSchedule;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LightingTowerSchedule;
use App\Models\ForkliftManitouSchedule;
use Yajra\DataTables\Facades\DataTables;

class AssetStatusLogController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);

        if ($request->ajax()) {
            $data = $this->applyFilters(AssetStatusLog::query()->where('asset_type', $type), $request);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('old_status', fn($row) => ucwords($row->old_status))
                ->editColumn('new_status', fn($row) => ucwords($row->new_status))
                ->rawColumns(['old_status', 'new_status', 'change_date'])
                ->make(true);
        }

        $assets = $model::pluck('asset_no')->toArray();
        return view('admin.asset-status-logs.index', compact('assets'));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->applyFilters(AssetStatusLog::query()->where('asset_type', $request->type), $request)->get();

        $pdf = Pdf::loadView('pdf.status-logs.index', ['data' => $data])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        return $pdf->stream('asset-status-logs' . time() . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->applyFilters(AssetStatusLog::query()->where('asset_type', $request->type), $request)->get();

        return Excel::download(new StatusLogExport($data, 'Status Logs'), 'asset-status-logs' . time() . '.xlsx');
    }

    /**
     * Apply filters for status, asset_no, and date_range to a query.
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('new_status', $request->status);
        }

        if ($request->filled('asset_no')) {
            $query->where('asset_no', $request->asset_no);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
                $end = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
                $query->whereBetween('change_date', [$start, $end]);
            }
        }

        return $query;
    }

    /**
     * Get the appropriate model class for the given type.
     */
    protected function getScheduleModel($type): string
    {
        return match ($type) {
            'lv' => LightVehicleSchedule::class,
            'lt' => LightingTowerSchedule::class,
            'tk' => TruckSchedule::class,
            'fm' => ForkliftManitouSchedule::class,
            'pm' => PumpSchedule::class,
        };
    }
}
