<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AdminAppSettingController extends Controller
{
    public function index()
    {
        $appSetting = AppSetting::first();
        return view('admin.settings.index', compact('appSetting'));
    }

    public function edit()
    {
        $appSetting = AppSetting::first();
        return view('admin.settings.edit', compact('appSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'total_leaves' => 'required|integer',
            'leave_calendar_start_date' => 'required|date',
        ]);

        $appSetting = AppSetting::first();
        $appSetting->update([
            'total_leaves' => $request->total_leaves,
            'leave_calendar_start_date' => $request->leave_calendar_start_date,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
