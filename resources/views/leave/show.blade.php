<x-app-layout :title="'View Leave Application'">
    <div class="max-w-4xl mx-auto py-10 space-y-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Leave Application Details</h2>

        <!-- Leave Application Details -->
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-4">
            <!-- User Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">Name:</span>
                    <span class="ml-2 text-gray-900">{{ $leaveApplication->user->name }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">Department:</span>
                    <span class="ml-2 text-gray-900">
                        @foreach ($leaveApplication->user->departments as $department)
                            {{ $department->name }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </span>
                </div>
            </div>

            <!-- Leave Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">Leave Type:</span>
                    <span class="ml-2 text-gray-900">{{ $leaveApplication->leave_type }}</span>
                </div>
                @php
                    $startDate = \Carbon\Carbon::parse($leaveApplication->start_date);
                    $endDate = \Carbon\Carbon::parse($leaveApplication->end_date);
                    $daysRequested = $startDate->diffInDays($endDate) + 1;
                @endphp
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">Days Requested:</span>
                    <span class="ml-2 text-gray-900">{{ $daysRequested }} days</span>
                </div>
            </div>

            <!-- Leave Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">Start Date:</span>
                    <span class="ml-2 text-gray-900">{{ $startDate->format('d M Y') }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-gray-600">End Date:</span>
                    <span class="ml-2 text-gray-900">{{ $endDate->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Leave Balance -->
            @php
                $totalLeavesAllowed = $appSetting->total_leaves;
                $approvedLeaves = $leaveApplications->where('status', 'approved')->sum(function($leave) {
                    $startDate = \Carbon\Carbon::parse($leave->start_date);
                    $endDate = \Carbon\Carbon::parse($leave->end_date);
                    return $startDate->diffInDays($endDate) + 1;
                });
                $remainingLeaves = $totalLeavesAllowed - $approvedLeaves;
            @endphp
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-green-600">Leaves Taken:</span>
                    <span class="ml-2 text-gray-900">{{ $approvedLeaves }} days</span>
                </div>
                <div class="flex items-center">
                    <span class="text-lg font-semibold text-red-600">Remaining Leaves:</span>
                    <span class="ml-2 text-gray-900">{{ $remainingLeaves }} days</span>
                </div>
            </div>

            <!-- Remarks -->

            <div>
                <h3 class="text-lg font-semibold text-gray-600">Remarks</h3>
                <textarea id="remarks" name="remarks" rows="4" class="block w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter remarks" {{ ((isset($canPartiallyApprove) && $leaveApplication->status != 'partially_approved') || (isset($canFullyApprove) && $leaveApplication->status != 'approved')) ? '' : 'disabled' }}>{{ $leaveApplication->remarks }}</textarea>
            </div>
        </div>


        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('chief_editor'))
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Leave Records</h3>
                @if($leaveApplication->leaveRecords->isEmpty())
                    <p class="text-gray-500">No leave records available.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Updated By</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Remarks</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($leaveApplication->leaveRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($record->status) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->updatedBy->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->remarks }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $record->updated_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif


<!-- Approval Section -->
<div class="flex mt-6" x-data="{ showModal: false, action: '' }">
    <!-- Confirmation Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Confirm Action</h3>
            <p class="mb-4">Are you sure you want to <span x-text="action"></span> this leave application?</p>
            <div class="flex justify-end space-x-4">
                <button @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</button>
                <form x-ref="confirmForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" x-text="action"></button>
                </form>
            </div>
        </div>
    </div>

    @if(isset($canPartiallyApprove) && $leaveApplication->status != 'partially_approved')
        <button @click="action = 'Partially Approve'; $refs.confirmForm.action='{{ route('leaveApplications.partiallyApprove', $leaveApplication->id) }}'; showModal = true" 
            class="bg-blue-500 text-white mr-5 px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 transition duration-300">
            Partially Approve
        </button>
        <button @click="action = 'Reject'; $refs.confirmForm.action='{{ route('leaveApplications.reject', $leaveApplication->id) }}'; showModal = true" 
            class="bg-red-500 text-white mr-5 px-4 py-2 rounded-lg shadow-lg hover:bg-red-600 transition duration-300">
            Reject
        </button>
    @endif

    @if(isset($canFullyApprove) && $leaveApplication->status != 'approved')
        <button @click="action = 'Approve'; $refs.confirmForm.action='{{ route('leaveApplications.approve', $leaveApplication->id) }}'; showModal = true" 
            class="bg-green-500 mr-5 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600 transition duration-300">
            Approve
        </button>
        <button @click="action = 'Reject'; $refs.confirmForm.action='{{ route('leaveApplications.reject', $leaveApplication->id) }}'; showModal = true" 
            class="bg-red-500 mr-5 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-600 transition duration-300">
            Reject
        </button>
    @endif

    <a href="{{ route('leave.allRecords') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-600 transition duration-300">Back</a>
</div>

    </div>
</x-app-layout>
