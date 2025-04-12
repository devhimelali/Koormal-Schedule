<?php

namespace App\Http\Controllers;

use App\Mail\WorkStatusNotifyMail;
use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TechniciansController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Schedule::with('asset.assetEmails.email')->where('is_today_works', 1);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status);
                })
                ->addColumn('action', function ($row) {
                    $emails = $row->asset?->assetEmails ?? [];

                    // Collect actual email addresses from the related email model
                    $emailList = [];
                    foreach ($emails as $assetEmail) {
                        if ($assetEmail->email && isset($assetEmail->email->email)) {
                            $emailList[] = $assetEmail->email->email;
                        }
                    }
                    $assetEmails = implode(', ', $emailList);
                    $btn = '<a href="javascript:void(0)" class="changeStatus btn btn-primary btn-sm" data-id="' . $row->id . '" data-status="' . $row->status . '">
                    <i class="ri-edit-line"></i>
                    Change Status</a>
                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm sendEmail" data-asset_emails="' . $assetEmails . '" data-asset_no="' . $row->asset_no . '" data-status="' . $row->status . '" data-next_due_date="' . $row->next_due_date . '">
                    <i class="ri-mail-send-line"></i>
                    Send Email</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('technicians.index');
    }

    public function changeStatus(Request $request)
    {
        $schedule = Schedule::find($request->id);
        $schedule->status = $request->status;
        $schedule->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Status changed successfully.'
        ]);
    }

    public function loadTodayWorks(Request $request)
    {
        $today = date('d-m-Y');

        // Reset all "today work" flags
        Schedule::where('is_today_works', 1)->update(['is_today_works' => 0]);

        // Set schedules where next_due_date matches today's date
        Schedule::where('next_due_date', $today)->update(['is_today_works' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => 'Today works loaded successfully.'
        ]);
    }

    public function sendEmail(Request $request)
    {
        $statusDetails = [
            'not yet touched' => [
                'background' => '#ffffff',
                'color' => '#000000',
                'message' => 'Not yet touched',
            ],
            'delivered' => [
                'background' => '#00ff00',
                'color' => '#000000',
                'message' => 'Delivered',
            ],
            'no show' => [
                'background' => '#ff00ff',
                'color' => '#ffffff',
                'message' => 'No show',
            ],
            'work underway' => [
                'background' => '#ffff00',
                'color' => '#000000',
                'message' => 'Work underway',
            ],
            'tagged out – further work found' => [
                'background' => '#ff0000',
                'color' => '#ffffff',
                'message' => 'Tagged out – further work found',
            ],
            'work completed, ready for pickup' => [
                'background' => '#00ffff',
                'color' => '#000000',
                'message' => 'Work completed, ready for pickup',
            ],
        ];

        $statusData = $statusDetails[$request->status];

        foreach ($request->emails as $email) {
            $body = '<span style="background-color: ' . $statusData['background'] . ' ; padding: 4px 8px; border-radius: 2px;border: 1px solid #000; color: ' . $statusData['color'] . '">' . $statusData['message'] . '</span>';
            Mail::to($email)->send(new WorkStatusNotifyMail($request->subject, $body));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully.'
        ]);
    }
}
