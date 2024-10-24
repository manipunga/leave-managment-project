<x-app-layout :title="'View Leave Application'">
    <div class="max-w-4xl mx-auto py-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">View Leave Application</h2>

        <!-- Leave Application Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Name -->
            <p class="mb-2"><strong>Name:</strong> {{ $leaveApplication->user->name }}</p>

            <!-- Department -->
            <p class="mb-2"><strong>Department:</strong> 
                @foreach ($leaveApplication->user->departments as $department)
                    {{ $department->name }}{{ !$loop->last ? ',' : '' }}
                @endforeach
             </p>

            <!-- Leave Type -->
            <p class="mb-2"><strong>Leave Type:</strong> {{ $leaveApplication->leave_type }}</p>
            
            <!-- Start Date & End Date -->
            <p class="mb-2"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($leaveApplication->start_date)->format('d M Y') }}</p>
            <p class="mb-2"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($leaveApplication->end_date)->format('d M Y') }}</p>
            
            <!-- Number of Days -->
            @php
                $startDate = \Carbon\Carbon::parse($leaveApplication->start_date);
                $endDate = \Carbon\Carbon::parse($leaveApplication->end_date);
                $daysRequested = $startDate->diffInDays($endDate) + 1;
            @endphp
            <p class="mb-4"><strong>Days Requested:</strong> {{ $daysRequested }} days</p>
            
            <!-- Leave Balance -->
            @php
                $totalLeavesAllowed = $appSetting->total_leaves;
                $leavesTaken = $leaveApplications->sum(function($leave) {
                    $startDate = \Carbon\Carbon::parse($leave->start_date);
                    $endDate = \Carbon\Carbon::parse($leave->end_date);
                    return $startDate->diffInDays($endDate) + 1;
                });
                $remainingLeaves = $totalLeavesAllowed - $leavesTaken;
            @endphp
            <p><strong>Leaves Taken:</strong> {{ $leavesTaken }} days</p>
            <p><strong>Remaining Leaves:</strong> {{ $remainingLeaves }} days</p>

            <div class="mt-6">
                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                <textarea id="remarks" name="remarks" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter remarks">{{ $leaveApplication->remarks }}</textarea>
            </div>
        </div>

        <!-- Approval Section -->
        <div class="flex space-x-4 mt-6">
            <!-- For Manager or HOD -->
            @if(isset($canPartiallyApprove))
                <form action="{{ route('leaveApplications.partiallyApprove', $leaveApplication->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">Partially Approve</button>
                </form>

                <form action="{{ route('leaveApplications.reject', $leaveApplication->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">Reject</button>
                </form>
            @endif

            <!-- For Chief Editor or Admin -->
            @if(isset($canFullyApprove))
                <form action="{{ route('leaveApplications.approve', $leaveApplication->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">Approve</button>
                </form>

                <form action="{{ route('leaveApplications.reject', $leaveApplication->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">Reject</button>
                </form>
            @endif

            <a href="{{ route('leave.allRecords') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Back</a>

        </div>
    </div>
</x-app-layout>
