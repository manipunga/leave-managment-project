<x-app-layout :title="'All Leave Applications'">
    <div class="max-w-7xl mx-auto py-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">All Leave Applications</h2>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">User</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Department</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Leave Type</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Start Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">End Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Days</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($leaveApplications as $leaveApplication)
                        @php
                            $startDate = \Carbon\Carbon::parse($leaveApplication->start_date);
                            $endDate = \Carbon\Carbon::parse($leaveApplication->end_date);
                            $days = $startDate->diffInDays($endDate) + 1;
                        @endphp
                        <tr>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->user->name }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                @foreach ($leaveApplication->user->departments as $department)
                                    {{ $department->name }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $leaveApplication->leave_type }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ \Carbon\Carbon::parse($leaveApplication->start_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ \Carbon\Carbon::parse($leaveApplication->end_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $days }} days</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
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

                            <!-- Action Buttons: View/Edit for Manager, HOD, Chief Editor, and Admin -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm">
                                <!-- Show View/Edit buttons only for specific roles -->
                                @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hod') || auth()->user()->hasRole('chief_editor') || auth()->user()->hasRole('admin'))
                                    <a href="{{ route('leave.show', $leaveApplication->id) }}" class="text-blue-500 hover:text-blue-700">View</a>

                                    <!-- Show Edit button for Chief Editor and Admin -->
                                    @can('edit-leave', $leaveApplication)
                                        <a href="{{ route('leave.edit', $leaveApplication->id) }}" class="ml-4 text-blue-500 hover:text-blue-700">Edit</a>
                                    @endcan
                                @endif
                            </td>
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
