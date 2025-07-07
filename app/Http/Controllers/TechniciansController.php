<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EmailLog;
use App\Models\PumpSchedule;
use Illuminate\Http\Request;
use App\Models\TruckSchedule;
use App\Models\AssetStatusLog;
use App\Mail\WorkStatusNotifyMail;
use App\Models\LightVehicleSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\LightingTowerSchedule;
use App\Models\ForkliftManitouSchedule;

class TechniciansController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        if ($request->ajax()) {
            $data = $model::with('asset.assetEmails.email')->where('is_today_works', 1);

            if ($request->shift) {
                $data = $data->where('description', 'like', '%' . $request->shift . '%');
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('description', function ($row) {
                    return $row->description ?? 'N/A';
                })
                ->addColumn('department', function ($row) {
                    return $row->department ?? 'N/A';
                })
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
                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm sendEmail" data-asset_emails="' . $assetEmails . '" data-asset_no="' . $row->asset_no . '" data-status="' . $row->status . '" data-next_due_date="' . $row->next_due_date . '" data-description="' . $row->description . '" data-department="' . $row->department . '">
                    <i class="ri-mail-send-line"></i>
                    Send Email</a>';

                    if ($row->is_technician_entry && Auth::user()->roles->first()->name == 'technician') {
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
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        $schedule = $model::find($request->id);
        $oldStatus = $schedule->status;
        $schedule->status = $request->status;
        $schedule->save();

        AssetStatusLog::create([
            'asset_no' => $schedule->asset_no,
            'description' => $schedule->description,
            'next_due_date' => $schedule->next_due_date,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'change_time' => Carbon::now()->timezone(config('app.timezone'))->format('H:i:s'),
            'change_date' => Carbon::now()->timezone(config('app.timezone'))->format('d-m-Y'),
            'asset_type' => $type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status changed successfully.'
        ]);
    }

    public function loadTodayWorks(Request $request)
    {
        $today = Carbon::now()->timezone(config('app.timezone'))->format('d-m-Y');

        $model = $this->getScheduleModel($request->type);

        // Reset all today works
        $model::where('is_today_works', 1)->update(['is_today_works' => 0]);

        // Set today works
        $model::where('next_due_date', $today)->update([
            'is_today_works' => 1,
            'status' => 'no status yet',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Today works loaded successfully.'
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'emails' => 'required',
        ]);

        $statusDetails = [
            'no status yet' => [
                'background' => '#ffffff',
                'color' => '#000000',
                'message' => 'No Status Yet',
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
            'mud buildup unsafe' => [
                'background' => '#C4A484',
                'color' => '#000000',
                'message' => 'Mud buildup Unsafe',
            ],
            'late delivery' => [
                'background' => '#FFD580',
                'color' => '#000000',
                'message' => 'Late Delivery',
            ],
        ];

        $statusData = $statusDetails[$request->status];

        foreach ($request->emails as $email) {
            $body = '<div style="margin-top: 20px;">';
            $body .= '<div style="margin-bottom: 20px;">' . $request->message . '</div>';
            $body .= '<span style="background-color: ' . $statusData['background'] . ' ; padding: 4px 8px; margin-bottom: 10px; border-radius: 2px;border: 1px solid #000; color: ' . $statusData['color'] . '">' . $statusData['message'] . '</span>';
            $body .= '</div>';
            try {
                Mail::to($email)->send(new WorkStatusNotifyMail($request->subject, $body));

                // Log successful email
                EmailLog::create([
                    'asset_no' => $request->asset_no,
                    'department' => $request->department,
                    'description' => $request->description,
                    'next_due_date' => $request->next_due_date ?? null,
                    'sent_date' => Carbon::now()->timezone(config('app.timezone'))->format('d-m-Y'),
                    'sent_time' => Carbon::now()->timezone(config('app.timezone'))->format('H:i:s'),
                    'email_body' => $body,
                    'is_sent' => true,
                    'recipient_email' => $email,
                    'email_subject' => $request->subject,
                    'asset_type' => $request->type
                ]);
            } catch (\Exception $e) {
                // Log successful email
                EmailLog::create([
                    'asset_no' => $request->asset_no,
                    'department' => $request->department,
                    'description' => $request->description,
                    'next_due_date' => $request->next_due_date,
                    'sent_date' => Carbon::now()->timezone(config('app.timezone'))->format('d-m-Y'),
                    'sent_time' => Carbon::now()->timezone(config('app.timezone'))->format('H:i:s'),
                    'email_body' => $body,
                    'is_sent' => false,
                    'recipient_email' => $email,
                    'email_subject' => $request->subject,
                    'asset_type' => $request->type
                ]);
            }
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
            'next_due_date' => 'required',
            'type' => 'required|in:lv,lt,tk,fm,pm',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $type = $request->type;

        $model = $this->getScheduleModel($type);

        $model::create([
            'asset_no' => $request->asset_no,
            'department' => $request->department,
            'next_due_date' => $request->next_due_date,
            'description' => $request->description,
            'is_technician_entry' => 1,
            'status' => 'no status yet',
            'is_today_works' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule added successfully.'
        ]);
    }

    public function editAsset(Request $request, $id)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        $asset = $model::find($id);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found.'
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
            'next_due_date' => 'required',
            'type' => 'required|in:lv,lt,tk,fm,pm',
        ]);

        $type = $request->type;

        $model = $this->getScheduleModel($type);

        $schedule = $model::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found.'
            ]);
        }

        $schedule->update([
            'asset_no' => $request->asset_no,
            'department' => $request->department,
            'next_due_date' => $request->next_due_date,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule updated successfully.'
        ]);
    }

    public function deleteAsset(Request $request, $id)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        $schedule = $model::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found.'
            ]);
        }

        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule deleted successfully.'
        ]);
    }

    public function ckeditorUpload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('uploads/emails'), $fileName);
            $url = asset('uploads/emails/' . $fileName);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => 0, 'error' => ['message' => 'No file uploaded.']], 400);
    }


    public function scheduleList(Request $request)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        return $model::select('id', 'asset_no')->orderBy('asset_no')->get();
    }

    public function getScheduleById(Request $request, $id)
    {
        $type = $request->type;
        $model = $this->getScheduleModel($type);
        $schedule = $model::with('asset.assetEmails.email')->find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule not found.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $schedule
        ]);
    }

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
