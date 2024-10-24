<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\LeaveApplication;
use App\Models\LeaveRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationController extends Controller
{


    public function index()
    {
        // Get the authenticated user's leave applications
        $leaveApplications = LeaveApplication::where('user_id', Auth::id())
        ->get();

        // Get the total allowed leaves from AppSetting
        $appSetting = AppSetting::first();

        $title = 'Your Leave Applications';

        // Pass the leave applications and app settings to the view
        return view('leave.index', compact('leaveApplications', 'appSetting', 'title'));
    }

    public function allRecords()
{
    $user = Auth::user();

    // Admin can view and edit all leave applications
    if ($user->hasRole('admin')) {
        $leaveApplications = LeaveApplication::with('user.departments')->get();
    }
    // Managers can see leave applications of subordinates in their department(s)
    elseif ($user->hasRole('manager')) {
        $leaveApplications = LeaveApplication::with('user.departments')->whereHas('user.departments', function ($query) use ($user) {
            $query->whereIn('department_id', $user->departments->pluck('id'));
        })->get();
    }
    // HODs can see leave applications of employees and managers in their department(s)
    elseif ($user->hasRole('hod')) {
        $leaveApplications = LeaveApplication::with('user.departments')->whereHas('user.departments', function ($query) use ($user) {
            $query->whereIn('department_id', $user->departments->pluck('id'));
        })->get();
    }
    // HR can see all leave applications
    elseif ($user->hasRole('hr')) {
        $leaveApplications = LeaveApplication::with('user.departments')->get();
    }
    // Chief Editors can see all leave applications
    elseif ($user->hasRole('chief_editor')) {
        $leaveApplications = LeaveApplication::with('user.departments')->get();
    }

    // Set the title for the page
    $title = 'All Leave Records';

    return view('leave.all-records', compact('leaveApplications', 'title'));
}

        /**
     * Show the form to apply for leave.
     */
    public function create()
    {
        $appSetting = AppSetting::first();
        $totalLeavesAllowed = $appSetting->total_leaves;
    
        $leavesTaken = LeaveApplication::where('user_id', Auth::id())
            ->whereIn('status', ['approved'])
            ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1'));
    
        $remainingLeaves = $totalLeavesAllowed - $leavesTaken;

        $title = 'Apply for Leave';

    
        return view('leave.create', compact('remainingLeaves', 'title'));
    }

    /**
     * Store a newly created leave application in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Calculate the number of days requested
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $daysRequested = $startDate->diff($endDate)->days + 1;

        // Get the total leaves allowed and the leaves already taken by the user
        $appSetting = AppSetting::first();
        $totalLeavesAllowed = $appSetting->total_leaves;

        $leavesTaken = LeaveApplication::where('user_id', Auth::id())
            ->whereIn('status', ['approved'])
            ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1'));

        $remainingLeaves = $totalLeavesAllowed - $leavesTaken;

        // Check if the user is trying to take more leaves than allowed
        if ($daysRequested > $remainingLeaves) {
            return redirect()->back()->withErrors(['error' => 'You do not have enough remaining leaves.']);
        }

        // Store the leave application
        $leaveApplication = LeaveApplication::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('leave.index')->with('success', 'Leave application submitted successfully.');
    }


    public function edit(LeaveApplication $leaveApplication)
    {
        // Check if the user is authorized to edit the leave application
        if ($leaveApplication->status === 'pending' && auth()->user()->id === $leaveApplication->user_id) {
            // The user is allowed to edit their own pending leave
            return view('leave.edit', compact('leaveApplication'));
        }

        // Check if the current user is an admin or chief editor (permission check)
        if (auth()->user()->can('edit-leave', $leaveApplication)) {
            // Admin or chief editor can edit any leave
            return view('leave.edit', compact('leaveApplication'));
        }

        // If none of the above, return unauthorized response or redirect
        return redirect()->route('leave.index')->with('error', 'You are not authorized to edit this leave application.');
    }


    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        // Validate the request
        $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:pending,approved,rejected,partially_approved,cancelled',
        ]);

        // Update the leave application fields
        $leaveApplication->update([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            // Allow status update only if the user is an admin or chief editor
            'status' => $request->user()->can('edit-leave', $leaveApplication) ? $request->status : $leaveApplication->status,
        ]);

        return redirect()->route('leave.index')->with('success', 'Leave application updated successfully.');
    }

    // /**
    //  * Update the status of a leave application.
    //  */
    // public function updateStatus(Request $request, LeaveApplication $leaveApplication)
    // {
    //     // Only managers, HODs, and chief editors can update the status
    //     if (!Auth::user()->hasAnyRole(['manager', 'hod', 'chief_editor'])) {
    //         return redirect()->back()->with('error', 'You do not have permission to approve or reject leave applications.');
    //     }
    
    //     $request->validate([
    //         'status' => 'required|in:partially_approved,approved,rejected,cancelled',
    //     ]);
    
    //     // Update the leave application status
    //     $leaveApplication->update([
    //         'status' => $request->status,
    //         'modified_by' => Auth::id(),
    //         'modified_at' => now(),
    //     ]);
    
    //     // Log the action in leave_records
    //     LeaveRecord::create([
    //         'leave_application_id' => $leaveApplication->id,
    //         'user_id' => Auth::id(),
    //         'action' => $request->status,
    //         'action_at' => now(),
    //     ]);
    
    //     return redirect()->back()->with('success', 'Leave status updated.');
    // }

    public function show(LeaveApplication $leaveApplication)
    {
        $user = auth()->user();

        // Get the total allowed leaves from AppSetting
        $appSetting = AppSetting::first();

        $leaveApplications = LeaveApplication::with('user.departments')->where('user_id', $leaveApplication->user_id)->get();

        // Load related leave records
        $leaveApplication->load('leaveRecords.updatedBy'); // Assuming leaveRecords has a relationship with updatedBy (User)


        // If user is Manager or HOD, they can partially approve or reject the application
        if ($user->hasRole('manager') || $user->hasRole('hod')) {
            return view('leave.show', compact('leaveApplication', 'appSetting', 'leaveApplications'))->with('canPartiallyApprove', true);
        }

        // If user is Chief Editor or Admin, they can fully approve or reject the application
        if ($user->hasRole('chief_editor') || $user->hasRole('admin')) {
            return view('leave.show', compact('leaveApplication', 'appSetting', 'leaveApplications'))->with('canFullyApprove', true);
        }

        return redirect()->route('leave.index')->with('error', 'Unauthorized to view this application');
    }

    public function partiallyApprove(Request $request, LeaveApplication $leaveApplication)
    {
        // Only managers and HODs can partially approve
        if (auth()->user()->hasRole('manager') || auth()->user()->hasRole('hod')) {
            $leaveApplication->update(['status' => 'partially_approved', 'remarks' => $request->remarks]);

            // Log the action in leave_records
            LeaveRecord::create([
                'leave_application_id' => $leaveApplication->id,
                'user_id' => Auth::id(),
                'action' => 'partially_approved',
                'action_at' => now(),
            ]);

            return redirect()->route('leave.show', $leaveApplication)->with('success', 'Leave application partially approved.');
        }
        return redirect()->route('leave.show', $leaveApplication)->with('error', 'Unauthorized action.');
    }

    public function approve(Request $request, LeaveApplication $leaveApplication)
    {
        // Only chief editors and admins can fully approve
        if (auth()->user()->hasRole('chief_editor') || auth()->user()->hasRole('admin')) {
            $leaveApplication->update(['status' => 'approved', 'remarks' => $request->remarks]);

            // Log the action in leave_records
            LeaveRecord::create([
                'leave_application_id' => $leaveApplication->id,
                'user_id' => Auth::id(),
                'action' => 'approved',
                'action_at' => now(),
            ]);

            return redirect()->route('leave.show', $leaveApplication)->with('success', 'Leave application approved.');
        }
        return redirect()->route('leave.show', $leaveApplication)->with('error', 'Unauthorized action.');
    }

    public function reject(Request $request, LeaveApplication $leaveApplication)
    {
        // All roles (manager, HOD, chief editor, admin) can reject
        $leaveApplication->update(['status' => 'rejected', 'remarks' => $request->remarks]);
        // Log the action in leave_records
        LeaveRecord::create([
            'leave_application_id' => $leaveApplication->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'action_at' => now(),
            'remarks' => $request->remarks
        ]);
        return redirect()->route('leave.allRecords')->with('success', 'Leave application rejected.');
    }

}
