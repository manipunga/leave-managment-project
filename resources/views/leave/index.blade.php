<x-app-layout :title="$title">
    <div class="max-w-7xl mx-auto py-10">

            <!-- Display Error Message if Present -->
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 001.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/></svg>
                </span>
            </div>
        @endif
        
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Leave Applications</h2>

        <!-- Leave Balance Section -->
        @php
            // Calculate leaves taken and remaining
            $totalLeavesAllowed = $appSetting->total_leaves;
            $leavesTaken = $leaveApplications->sum(function($leave) {
                $startDate = \Carbon\Carbon::parse($leave->start_date);
                $endDate = \Carbon\Carbon::parse($leave->end_date);
                return $startDate->diffInDays($endDate) + 1;
            });
            $remainingLeaves = $totalLeavesAllowed - $leavesTaken;
        @endphp

        <div class="mb-6">
            <p class="text-lg font-semibold text-gray-700">Total Leaves Allowed: {{ $totalLeavesAllowed }}</p>
            <p class="text-lg font-semibold text-green-500">Leaves Taken: {{ $leavesTaken }}</p>
            <p class="text-lg font-semibold text-blue-500">Remaining Leaves: {{ $remainingLeaves }}</p>
        </div>

        <!-- Apply for Leave Button -->
        <div class="mb-6 flex justify-end">
            <a href="{{ route('leave.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                Apply for Leave
            </a>
        </div>

        <!-- Leave Applications Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Leave Type</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Start Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">End Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Days</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($leaveApplications as $leaveApplication)
                        @php
                            // Calculate the number of days for each leave application
                            $startDate = \Carbon\Carbon::parse($leaveApplication->start_date);
                            $endDate = \Carbon\Carbon::parse($leaveApplication->end_date);
                            $days = $startDate->diffInDays($endDate) + 1;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->leave_type }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->start_date }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->end_date }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $days }} days</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm">
                                @if ($leaveApplication->status === 'pending')
                                    <span class="text-yellow-500 font-semibold">Pending</span>
                                @elseif ($leaveApplication->status === 'approved')
                                    <span class="text-green-500 font-semibold">Approved</span>
                                @elseif ($leaveApplication->status === 'rejected')
                                    <span class="text-red-500 font-semibold">Rejected</span>
                                @elseif ($leaveApplication->status === 'partially_approved')
                                    <span class="text-blue-500 font-semibold">Partially Approved</span>
                                @else
                                    <span class="text-gray-500 font-semibold">{{ ucfirst($leaveApplication->status) }}</span>
                                @endif
                            </td>

                            <!-- Conditional Edit Button -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm">
                            <!-- If the current user is an admin or chief editor, show the admin's Edit button -->
                            @can('edit-leave', $leaveApplication)
                                <a href="{{ route('leave.edit', $leaveApplication->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            @else
                                <!-- If the current user is the owner and the leave is in pending status, show the user's Edit button -->
                                @if($leaveApplication->status === 'pending' && Auth::user()->id === $leaveApplication->user_id)
                                    <a href="{{ route('leave.edit', $leaveApplication->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                @endif
                            @endcan
                        </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Empty State -->
            @if($leaveApplications->isEmpty())
                <div class="px-6 py-4 text-gray-500">
                    You haven't applied for any leaves yet.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
