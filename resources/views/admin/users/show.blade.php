<x-app-layout :title="'View User - ' . $user->name">
    <div class="max-w-7xl mx-auto py-10 space-y-8">

        <!-- User Details Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">User Details</h2>
            <div class="grid grid-cols-2 gap-6">
                <div><strong>Name:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Date of Birth:</strong> {{ $user->dob }}</div>
                <div><strong>Joining Date:</strong> {{ $user->joining_date }}</div>
                <div><strong>Salary:</strong> {{ $user->salary }}</div>
                <div><strong>Designation:</strong> {{ $user->designation }}</div>
                <div><strong>Departments:</strong>
                    @foreach ($user->departments as $department)
                        {{ $department->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </div>
            </div>
        </div>

<!-- Leave Stats Section -->
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Leave Statistics</h2>
    @php
        // Get current date
        $currentDate = now();

        // Find the calendar year that the current date falls into
        $calendarYear = \App\Models\CalendarYear::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->first();

        if ($calendarYear) {
            $totalLeavesAllowed = $calendarYear->total_leaves;

            // Calculate approved leaves for the current calendar year
            $approvedLeaves = $user->leaveApplications->where('calendar_year_id', $calendarYear->id)
                ->where('status', 'approved')->sum(function($leave) {
                    return \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                });

            // Calculate partially approved leaves for the current calendar year
            $partiallyApprovedLeaves = $user->leaveApplications->where('calendar_year_id', $calendarYear->id)
                ->where('status', 'partially_approved')->sum(function($leave) {
                    return \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                });

            // Calculate pending leaves for the current calendar year
            $pendingLeaves = $user->leaveApplications->where('calendar_year_id', $calendarYear->id)
                ->where('status', 'pending')->sum(function($leave) {
                    return \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                });

            // Calculate remaining leaves for the current calendar year
            $remainingLeaves = $totalLeavesAllowed - $approvedLeaves;
        } else {
            // If no calendar year is found, default values
            $totalLeavesAllowed = 0;
            $approvedLeaves = 0;
            $partiallyApprovedLeaves = 0;
            $pendingLeaves = 0;
            $remainingLeaves = 0;
        }
    @endphp
    <div class="grid grid-cols-4 gap-6 text-center">
        <div class="p-4 bg-green-100 rounded-lg">
            <strong class="text-green-600 text-xl">{{ $approvedLeaves }} days</strong>
            <p class="text-gray-600">Approved Leaves</p>
        </div>
        <div class="p-4 bg-blue-100 rounded-lg">
            <strong class="text-blue-600 text-xl">{{ $partiallyApprovedLeaves }} days</strong>
            <p class="text-gray-600">Partially Approved</p>
        </div>
        <div class="p-4 bg-yellow-100 rounded-lg">
            <strong class="text-yellow-600 text-xl">{{ $pendingLeaves }} days</strong>
            <p class="text-gray-600">Pending Leaves</p>
        </div>
        <div class="p-4 bg-red-100 rounded-lg">
            <strong class="text-red-600 text-xl">{{ $remainingLeaves }} days</strong>
            <p class="text-gray-600">Remaining Leaves</p>
        </div>
    </div>
</div>


        <!-- Leave Applications List Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Leave Applications</h2>
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Leave Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Start Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">End Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Days</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($user->leaveApplications->sortByDesc('created_at') as $leave)
                        @php
                            $startDate = \Carbon\Carbon::parse($leave->start_date);
                            $endDate = \Carbon\Carbon::parse($leave->end_date);
                            $days = $startDate->diffInDays($endDate) + 1;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->leave_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $startDate->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $endDate->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if ($leave->status === 'approved')
                                    <span class="text-green-600 font-semibold">Approved</span>
                                @elseif ($leave->status === 'partially_approved')
                                    <span class="text-blue-600 font-semibold">Partially Approved</span>
                                @elseif ($leave->status === 'pending')
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @else
                                    <span class="text-gray-500 font-semibold">{{ ucfirst($leave->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $days }} days</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Back Button -->
        <div class="flex justify-end">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Back</a>
        </div>

    </div>
</x-app-layout>
