<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        
        $departments = Department::all();
        $roles = Role::all(); // Fetching roles using Spatie\Permission
        return view('admin.users.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cnic' => 'nullable|string|max:15',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'password' => 'required|string|min:8',
            'designation' => 'nullable|string|max:255',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cnic' => $request->cnic,
            'dob' => $request->dob,
            'joining_date' => $request->joining_date,
            'designation' => $request->designation,
            'salary' => $request->salary,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Attach selected departments to the user
        $user->departments()->attach($request->departments);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
            // Fetch roles and departments
            $roles = Role::all(); // Assuming Spatie Role model is used
            $departments = Department::all();

            // Get user roles and departments
            $userRoles = $user->roles->pluck('id')->toArray();
            $userDepartments = $user->departments->pluck('id')->toArray(); // Assuming many-to-many relation

            return view('admin.users.edit', compact('user', 'roles', 'departments', 'userRoles', 'userDepartments'));
    }

    public function update(Request $request, User $user)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cnic' => 'nullable|string|max:15',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'roles' => 'required|array',
            'designation' => 'nullable|string|max:255',
            'departments' => 'required|array',
            'password' => 'nullable|string|min:8|confirmed', // Password validation
        ]);
    
        // Update user details
        $user->update($request->only('name', 'email', 'cnic', 'dob', 'joining_date', 'salary', 'designation'));
    
        // If password is present, update it
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }
    
        // Sync roles and departments
        $user->assignRole($request->role);
        $user->departments()->sync($request->input('departments', []));
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function show(User $user)
    {
        // Get App Settings (assuming you have a model for it)
        $appSetting = AppSetting::first(); // Adjust this as needed

        // Pass user and app setting data to the view
        return view('admin.users.show', compact('user', 'appSetting'));
    }
    

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
