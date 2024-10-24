<x-app-layout :title="'Edit User'">
    <div class="max-w-7xl mx-auto py-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit User</h2>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $user->name) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        @if(!Auth::user()->hasRole('admin')) disabled @endif
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
                        value="{{ old('email', $user->email) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        @if(!Auth::user()->hasRole('admin')) disabled @endif
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CNIC -->
                <div>
                    <label for="cnic" class="block text-sm font-medium text-gray-700">CNIC</label>
                    <input 
                        type="text" 
                        name="cnic" 
                        id="cnic" 
                        value="{{ old('cnic', $user->cnic) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        >
                    @error('cnic')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input 
                        type="date" 
                        name="dob" 
                        id="dob" 
                        value="{{ old('dob', $user->dob) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        >
                    @error('dob')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Joining Date -->
                <div>
                    <label for="joining_date" class="block text-sm font-medium text-gray-700">Joining Date</label>
                    <input 
                        type="date" 
                        name="joining_date" 
                        id="joining_date" 
                        value="{{ old('joining_date', $user->joining_date) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        >
                    @error('joining_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Salary -->
                <div>
                    <label for="salary" class="block text-sm font-medium text-gray-700">Salary</label>
                    <input 
                        type="number" 
                        name="salary" 
                        id="salary" 
                        value="{{ old('salary', $user->salary) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                        >
                    @error('salary')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Show Password, Role, and Departments Only for Admin -->
                @role('admin')
                    <!-- Password Reset (optional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <p class="text-sm text-gray-500">Leave blank if you don't want to change the password.</p>
                        @error('password')
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
                            <option value="{{ $role->name }}" {{ $user->roles->first()->name == $role->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Departments -->
                    <div>
                        <label for="departments" class="block text-sm font-medium text-gray-700">Departments</label>
                        <select 
                            name="departments[]" 
                            id="departments" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" 
                            multiple>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ in_array($department->id, $userDepartments) ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('departments')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-700">Designation</label>
                        <input 
                            type="text" 
                            name="designation" 
                            id="designation" 
                            value="{{ old('designation', $user->designation) }}" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @error('designation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endrole

                <!-- Submit and Back Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Back</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
