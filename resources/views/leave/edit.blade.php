<x-app-layout :title="'Edit Leave Application'">
    <div class="max-w-4xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Leave Application</h2>

        <!-- Leave Edit Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('leave.update', $leaveApplication->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700">Leave Type</label>
                    <input 
                        type="text" 
                        name="leave_type" 
                        id="leave_type" 
                        value="{{ old('leave_type', $leaveApplication->leave_type) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        required>
                    @error('leave_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="start_date" 
                        value="{{ old('start_date', $leaveApplication->start_date) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        required>
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date" 
                        value="{{ old('end_date', $leaveApplication->end_date) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        required>
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status (Only for Admin or Chief Editor) -->
                @can('edit-leave', $leaveApplication)
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select 
                            name="status" 
                            id="status" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="pending" {{ $leaveApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $leaveApplication->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $leaveApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="partially_approved" {{ $leaveApplication->status == 'partially_approved' ? 'selected' : '' }}>Partially Approved</option>
                            <option value="cancelled" {{ $leaveApplication->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endcan

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">Update Leave</button>
                    <a href="{{ route('leave.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Back</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
