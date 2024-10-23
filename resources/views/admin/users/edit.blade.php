<x-app-layout>
    <h2>Edit User</h2>
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <label for="name">Name</label>
        <input type="text" name="name" value="{{ $user->name }}" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ $user->email }}" required>
        
        <label for="role">Role</label>
        <select name="role" required>
            <option value="employee" {{ $user->hasRole('employee') ? 'selected' : '' }}>Employee</option>
            <option value="manager" {{ $user->hasRole('manager') ? 'selected' : '' }}>Manager</option>
            <option value="hod" {{ $user->hasRole('hod') ? 'selected' : '' }}>HOD</option>
            <option value="chief_editor" {{ $user->hasRole('chief_editor') ? 'selected' : '' }}>Chief Editor</option>
            <option value="hr" {{ $user->hasRole('hr') ? 'selected' : '' }}>HR</option>
            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
        </select>

        <button type="submit">Update User</button>
    </form>
</x-app-layout>
