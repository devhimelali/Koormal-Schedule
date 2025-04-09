<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect(Request $request)
    {
        if (auth()->user()->roles->pluck('name')->first() == 'admin') {
            return redirect()->route('admin.dashboard');
        } else if (auth()->user()->roles->pluck('name')->first() == 'technician') {
            return redirect()->route('technician.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
}
