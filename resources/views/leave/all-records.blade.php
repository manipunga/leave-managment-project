<x-app-layout :title="$title">
    <div class="max-w-7xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>

        <!-- Leave Records Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Employee</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Leave Type</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Start Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">End Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Days</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Status</th>
                        @if(Auth::user()->hasRole('admin'))
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Actions</th>
                        @endif
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
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->user->name }}</td>
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
                            <!-- Show Edit button only for Admin -->
                            @if(Auth::user()->hasRole('admin'))
                                <td class="px-6 py-4 border-b border-gray-200 text-sm">
                                    <a href="{{ route('leave.edit', $leaveApplication) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Empty State -->
            @if($leaveApplications->isEmpty())
                <div class="px-6 py-4 text-gray-500">
                    No leave applications found.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
