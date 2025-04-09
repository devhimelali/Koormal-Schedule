<?php

namespace App\Http\Controllers\User;

use App\Models\Asset;
use App\Models\Email;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Mail\ScheduleListMail;
use App\Models\AssetTimeFrame;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use App\Jobs\SendScheduleEmailJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Schedule::query();

            if (!empty($request->department)) {
                $data = $data->where('department', $request->department);
            }

            if (!empty($request->asset_no)) {
                $data = $data->where('asset_no', $request->asset_no);
            }

            if (!empty($request->time_frame)) {
                $today = date('Y-m-d');
                $next_due_date = date('Y-m-d', strtotime($today . ' + ' . $request->time_frame . ' days'));

                $data = $data->whereRaw('STR_TO_DATE(next_due_date, "%d-%m-%Y") = ?', [$next_due_date]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        $departments = Schedule::where('department', '!=', null)
            ->orderBy('department', 'asc')
            ->distinct()
            ->pluck('department');

        $assets = Asset::orderBy('asset_no', 'asc')
            ->distinct()
            ->pluck('asset_no');

        return view('user.schedule.index', compact('assets', 'departments'));
    }

    public function exportPdf(Request $request)
    {
        $schedules = Schedule::query();

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

        $schedules = Schedule::query();

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
