<x-app-layout :title="'Create User'">
    <div class="max-w-4xl mx-auto py-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New User</h2>

        <!-- Create User Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        required>
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role (Using Spatie) -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select 
                        name="role" 
                        id="role" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Departments (Multiple Select) -->
                <div>
                    <label for="departments" class="block text-sm font-medium text-gray-700">Departments</label>
                    <select 
                        name="departments[]" 
                        id="departments" 
                        multiple 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ collect(old('departments'))->contains($department->id) ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('departments')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <small class="text-gray-500">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple options.</small>
                </div>

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Back</a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">Create User</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>