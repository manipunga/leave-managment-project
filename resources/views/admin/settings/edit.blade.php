<x-app-layout :title="'Edit App Settings'">
    <div class="max-w-4xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit App Settings</h2>

        <!-- Edit Settings Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.settings.update', $appSetting) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Total Available Leaves -->
                <div>
                    <label for="total_leaves" class="block text-sm font-medium text-gray-700">Total Available Leaves</label>
                    <input 
                        type="number" 
                        name="total_leaves" 
                        id="total_leaves" 
                        value="{{ $appSetting->total_leaves }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        required>
                </div>

                <!-- Leave Calendar Year Start Date -->
                <div>
                    <label for="leave_calendar_start_date" class="block text-sm font-medium text-gray-700">Leave Calendar Year Start Date</label>
                    <input 
                        type="date" 
                        name="leave_calendar_start_date" 
                        id="leave_calendar_start_date" 
                        value="{{ $appSetting->leave_calendar_start_date }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        required>
                </div>

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <!-- Back Button -->
                    <a href="{{ route('admin.settings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">
                        Back
                    </a>
                    <!-- Update Button -->
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                        Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
