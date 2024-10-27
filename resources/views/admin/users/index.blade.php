<x-app-layout :title="'Manage Users'">
    <div class="max-w-7xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Manage Users</h2>

        <!-- Add New User Button -->
        <div class="mb-6 flex justify-end">
        @role('admin')
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                Add New User
            </a>
        @endrole
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Name</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Email</th>
                        @role('admin')
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Role</th>
                        @endrole
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Departments</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($users as $user)
                    @if(!Auth::user()->hasRole('hr') || !$user->hasRole('admin'))

                        <tr>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $user->email }}</td>
                            @role('admin')
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ ucfirst($user->role) }}</td>
                            @endrole
                            <!-- Displaying User Departments -->
                            <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                @if($user->departments)
                                    @foreach ($user->departments as $department)
                                        <span class="inline-block bg-gray-200 text-gray-800 text-sm px-2 py-1 rounded-lg">{{ $department->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">No department</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 border-b border-gray-200 text-sm flex items-center space-x-4">
                                <a href="{{ route('admin.users.show', $user) }}" class="bg-cyan-500 text-white font-semibold py-1 px-3 rounded hover:bg-cyan-600">View</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 text-white font-semibold py-1 px-3 rounded hover:bg-yellow-600">Edit</a>
                                @role('admin')
                                <!-- Alpine.js Wrapper for Delete Confirmation Modal -->
                                <div x-data="{ openModal: false }">
                                    <!-- Delete Button to Open Modal -->
                                    <button @click="openModal = true" class="bg-red-500 text-white font-semibold py-1 px-3 rounded hover:bg-red-600">
                                        Delete
                                    </button>

                                    <!-- Confirmation Modal -->
                                    <div x-show="openModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>

                                            <!-- Modal Content -->
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Delete User</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500">Are you sure you want to delete this user? This action cannot be undone.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <!-- Confirm Delete Button -->
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                                                            Yes, Delete
                                                        </button>
                                                    </form>
                                                    <!-- Cancel Button -->
                                                    <button @click="openModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Confirmation Modal -->
                                    @endrole
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            @if($users->isEmpty())
                <div class="px-6 py-4 text-gray-500">
                    No users available.
                </div>
            @endif
        </div>
        </div>
    </div>
</x-app-layout>
