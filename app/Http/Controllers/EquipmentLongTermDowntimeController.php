<?php

namespace App\Http\Controllers;

use App\Models\EquipmentLongTermDowntime;
use Illuminate\Http\Request;

class EquipmentLongTermDowntimeController extends Controller
{
    public function index()
    {
        $pdf = EquipmentLongTermDowntime::first();
        // dd($pdf);
        return view('equipment-long-term-downtime.index', compact('pdf'));
    }
}
