<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class TechniciansController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $toDate = date('d-m-Y');
            $data = Schedule::where('next_due_date', $toDate);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="changeStatus btn btn-primary btn-sm" data-id="' . $row->id . '" data-status="' . $row->status . '">
                    <i class="ri-edit-line"></i>
                    Change Status</a>';
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
}
