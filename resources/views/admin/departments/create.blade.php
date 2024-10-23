<x-app-layout :title="'Create New Department'">
    <div class="max-w-4xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Department</h2>

        <!-- Create Department Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Department Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Department Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <!-- Create Department Button -->
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                        Create Department
                    </button>
                    <!-- Back Button -->
                    <a href="{{ route('admin.departments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
