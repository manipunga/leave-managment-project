<x-app-layout :title="$title">
    <div class="max-w-4xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Apply for Leave</h2>

        <!-- Leave Application Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('leave.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700">Leave Type</label>
                    <input 
                        type="text" 
                        name="leave_type" 
                        id="leave_type" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Sick leave, vacation, etc."
                        required>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="start_date" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <!-- Submit Button -->
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                        Submit Application
                    </button>
                    
                    <!-- Back Button -->
                    <a href="{{ route('leave.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
