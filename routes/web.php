<?php

use App\Http\Controllers\AdminAppSettingController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::middleware(['auth'])->group(function () {
    Route::get('/', [LeaveApplicationController::class, 'index'])->name('home');

    Route::get('/leave/all-records', [LeaveApplicationController::class, 'allRecords'])->name('leave.allRecords');
    Route::get('/leave/{leaveApplication}/edit', [LeaveApplicationController::class, 'edit'])->name('leave.edit');
    Route::put('/leave/{leaveApplication}', [LeaveApplicationController::class, 'update'])->name('leave.update');
    

    // Employees can create and view their own leave applications
    Route::get('/leave', [LeaveApplicationController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [LeaveApplicationController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveApplicationController::class, 'store'])->name('leave.store');
    Route::patch('/leaveApplications/{leaveApplication}/partially-approve', [LeaveApplicationController::class, 'partiallyApprove'])->name('leaveApplications.partiallyApprove');
    Route::patch('/leaveApplications/{leaveApplication}/approve', [LeaveApplicationController::class, 'approve'])->name('leaveApplications.approve');
    Route::patch('/leaveApplications/{leaveApplication}/reject', [LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');
    Route::get('/leave/{leaveApplication}', [LeaveApplicationController::class, 'show'])->name('leave.show');


    // Managers, HODs, and chief editors can approve/reject leaves
    Route::middleware('role:manager|hod|chief_editor')->group(function () {
        Route::patch('/leave/{leaveApplication}/status', [LeaveApplicationController::class, 'updateStatus'])->name('leave.update.status');
    });


    Route::middleware('role:admin')->group(function () {

    // User management
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    });


    Route::middleware('role:admin|hr')->group(function () {
    // User management
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    
    });

    Route::middleware('role:admin')->group(function () {

   
       // Department management
       Route::get('/admin/departments', [AdminDepartmentController::class, 'index'])->name('admin.departments.index');
       Route::get('/admin/departments/create', [AdminDepartmentController::class, 'create'])->name('admin.departments.create');
       Route::post('/admin/departments', [AdminDepartmentController::class, 'store'])->name('admin.departments.store');
       Route::get('/admin/departments/{department}/edit', [AdminDepartmentController::class, 'edit'])->name('admin.departments.edit');
       Route::put('/admin/departments/{department}', [AdminDepartmentController::class, 'update'])->name('admin.departments.update');
       Route::delete('/admin/departments/{department}', [AdminDepartmentController::class, 'destroy'])->name('admin.departments.destroy');
   
       // App settings management
       Route::get('/admin/settings', [AdminAppSettingController::class, 'index'])->name('admin.settings.index');
       Route::get('/admin/settings/edit', [AdminAppSettingController::class, 'edit'])->name('admin.settings.edit');
       Route::put('/admin/settings', [AdminAppSettingController::class, 'update'])->name('admin.settings.update');
   });


});



require __DIR__.'/auth.php';
