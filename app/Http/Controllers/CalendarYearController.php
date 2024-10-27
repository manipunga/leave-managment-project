<?php

namespace App\Http\Controllers;

use App\Models\CalendarYear;
use Illuminate\Http\Request;

class CalendarYearController extends Controller
{
    public function index()
    {
        $calendarYears = CalendarYear::all();
        return view('admin.calendar_years.index', compact('calendarYears'));
    }

    public function create()
    {
        return view('admin.calendar_years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_leaves' => 'required|integer|min:0',
        ]);

        CalendarYear::create($request->all());

        return redirect()->route('calendar_years.index')->with('success', 'Calendar year created successfully.');
    }

    public function edit(CalendarYear $calendarYear)
    {
        return view('admin.calendar_years.edit', compact('calendarYear'));
    }

    public function update(Request $request, CalendarYear $calendarYear)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_leaves' => 'required|integer|min:0',
        ]);

        $calendarYear->update($request->all());

        return redirect()->route('calendar_years.index')->with('success', 'Calendar year updated successfully.');
    }

    public function destroy(CalendarYear $calendarYear)
    {
        $calendarYear->delete();
        return redirect()->route('calendar_years.index')->with('success', 'Calendar year deleted successfully.');
    }
}
