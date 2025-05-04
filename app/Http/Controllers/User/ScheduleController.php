<?php

namespace App\Http\Controllers\User;

use App\Models\Asset;
use App\Models\Email;
use App\Models\LightingTowerAsset;
use App\Models\LightingTowerSchedule;
use App\Models\LightVehicleSchedule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Mail\ScheduleListMail;
use App\Models\AssetTimeFrame;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use App\Jobs\SendScheduleEmailJob;
use App\Http\Controllers\Controller;
use App\Models\LightVehicleAsset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;
        if ($request->ajax()) {
            if ($type == 'lv') {
                $data = LightVehicleSchedule::where('is_technician_entry', 0);
            } elseif ($type == 'lt') {
                $data = LightingTowerSchedule::where('is_technician_entry', 0);
            }

            if (!empty($request->department)) {
                $data = $data->where('department', $request->department);
            }

            if (!empty($request->asset_no)) {
                $data = $data->where('asset_no', $request->asset_no);
            }

            if (!empty($request->date_range)) {
                $dates = explode(' to ', $request->date_range);
                if (count($dates) == 2) {
                    $startDate = $dates[0];
                    $endDate = $dates[1];

                    $data = $data->whereRaw("STR_TO_DATE(next_due_date, '%d-%m-%Y') BETWEEN STR_TO_DATE(?, '%d-%m-%Y') AND STR_TO_DATE(?, '%d-%m-%Y')", [$startDate, $endDate]);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        if ($type == 'lv') {
            $departments = LightVehicleSchedule::where('department', '!=', null)
                ->orderBy('department', 'asc')
                ->distinct()
                ->pluck('department');

            $assets = LightVehicleAsset::orderBy('asset_no', 'asc')
                ->distinct()
                ->pluck('asset_no');
        } elseif ($type == 'lt') {
            $departments = LightingTowerSchedule::where('department', '!=', null)
                ->orderBy('department', 'asc')
                ->distinct()
                ->pluck('department');

            $assets = LightingTowerAsset::orderBy('asset_no', 'asc')
                ->distinct()
                ->pluck('asset_no');
        }

        return view('user.schedule.index', compact('assets', 'departments'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->type;
        if ($type == 'lv') {
            $schedules = LightVehicleSchedule::where('is_technician_entry', 0);
        } elseif ($type == 'lt') {
            $schedules = LightingTowerSchedule::where('is_technician_entry', 0);
        }

        if (!empty($request->department)) {
            $schedules = $schedules->where('department', $request->department);
        }

        if (!empty($request->asset_no)) {
            $schedules = $schedules->where('asset_no', $request->asset_no);
        }

        if (!empty($request->time_frame)) {
            $today = date('Y-m-d');
            $next_due_date = date('Y-m-d', strtotime($today . ' + ' . $request->time_frame . ' days'));

            $schedules = $schedules->whereRaw('STR_TO_DATE(next_due_date, "%d-%m-%Y") = ?', [$next_due_date]);
        }

        $schedules = $schedules->get();

        $data = [
            'schedules' => $schedules,
        ];

        $pdf  = PDF::loadView('pdf.schedule', $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        return $pdf->stream('schedules-' . time() . '.pdf');
    }

    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:lv,lt',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $emailArray = array_map('trim', explode(',', $request->email));

        foreach ($emailArray as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['errors' => ['email' => ['One or more email addresses are invalid.']]], 422);
            }
        }

        $type = $request->type;
        if ($type == 'lv') {
            $schedules = LightVehicleSchedule::where('is_technician_entry', 0);
        } elseif ($type == 'lt') {
            $schedules = LightingTowerSchedule::where('is_technician_entry', 0);
        }

        if (!empty($request->department)) {
            $schedules = $schedules->where('department', $request->department);
        }

        if (!empty($request->asset_no)) {
            $schedules = $schedules->where('asset_no', $request->asset_no);
        }
        if (!empty($request->time_frame)) {
            $today = date('Y-m-d');
            $next_due_date = date('Y-m-d', strtotime($today . ' + ' . $request->time_frame . ' days'));

            $schedules = $schedules->whereRaw('STR_TO_DATE(next_due_date, "%d-%m-%Y") = ?', [$next_due_date]);
        }

        $schedules = $schedules->get();
        foreach ($emailArray as $email) {
            dispatch(new SendScheduleEmailJob($email, $request->subject, $request->message, $schedules));
        }

        return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
    }
}
