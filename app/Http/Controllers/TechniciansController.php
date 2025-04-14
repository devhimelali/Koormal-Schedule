<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Mail\WorkStatusNotifyMail;
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
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="javascript:void(0)" class="changeStatus btn btn-primary btn-sm" data-id="' . $row->id . '" data-status="' . $row->status . '">
                    <i class="ri-pencil-ruler-line"></i>
                    Change Status</a>
                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm sendEmail" data-asset_emails="' . $assetEmails . '" data-asset_no="' . $row->asset_no . '" data-status="' . $row->status . '" data-next_due_date="' . $row->next_due_date . '" data-description="' . $row->description . '">
                    <i class="ri-mail-send-line"></i>
                    Send Email</a>';

                    if ($row->is_technician_entry && auth()->user()->roles->first()->name == 'technician') {
                        $btn .= '<a href="javascript:void(0)" class="editAsset btn btn-warning btn-sm" data-id="' . $row->id . '">
                        <i class="ri-edit-line"></i>
                        Edit</a>';
                        $btn .= '<a href="javascript:void(0)" class="deleteAsset btn btn-danger btn-sm" data-id="' . $row->id . '">
                        <i class="ri-delete-bin-line"></i>
                        Delete</a>';
                    }
                    $btn .= '</div>';
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
        Schedule::where('next_due_date', $today)->update(['is_today_works' => 1, 'status' => 'no status yet']);

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
            $body = '<div style="margin-top: 20px;">';
            $body .= '<span style="background-color: ' . $statusData['background'] . ' ; padding: 4px 8px; border-radius: 2px;border: 1px solid #000; color: ' . $statusData['color'] . '">' . $statusData['message'] . '</span>';
            $body .= $request->message;
            $body .= '</div>';
            Mail::to($email)->send(new WorkStatusNotifyMail($request->subject, $body));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully.'
        ]);
    }

    public function addAsset(Request $request)
    {
        $request->validate([
            'asset_no' => 'required',
            'description' => 'required',
            'next_due_date' => 'required',
        ]);

        Asset::create([
            'asset_no' => $request->asset_no,
            'description' => $request->description,
            'department' => $request->department,
            'next_due_date' => $request->next_due_date,
            'is_technician_entry' => 1,
        ]);

        Schedule::create([
            'asset_no' => $request->asset_no,
            'description' => $request->description,
            'department' => $request->department,
            'next_due_date' => $request->next_due_date,
            'status' => 'not yet touched',
            'is_today_works' => 1,
            'is_technician_entry' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Asset added successfully.'
        ]);
    }

    public function editAsset($id)
    {
        $asset = Schedule::find($id);
        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset not found.'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $asset
        ]);
    }

    public function updateAsset(Request $request, $id)
    {
        $request->validate([
            'asset_no' => 'required',
            'description' => 'required',
            'next_due_date' => 'required',
        ]);

        $schedule = Schedule::find($id);
        $schedule->asset_no = $request->asset_no;
        $schedule->description = $request->description;
        $schedule->department = $request->department;
        $schedule->next_due_date = $request->next_due_date;
        $schedule->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Asset updated successfully.'
        ]);
    }

    public function deleteAsset($id)
    {
        $schedule = Schedule::find($id);
        $schedule->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Asset deleted successfully.'
        ]);
    }

    public function ckeditorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('uploads/emails'), $fileName);
            $url = asset('uploads/emails/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
