<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\LeaveApprovalController;
use App\Http\Controllers\Admin\UserRolesTableController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Menggunakan DashboardController untuk menyiapkan data statistik
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- Route Umum (Profile) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Route Pengajuan Cuti (Employee Features) ---
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    Route::get('/leave-requests/{leaveRequest}/pdf', [LeaveRequestController::class, 'generatePdf'])
        ->name('leave-requests.download.pdf');

    // --- Route Daftar Karyawan Divisi ---
    Route::get('my-employees', [UserRolesTableController::class, 'index'])->name('division.employee.list');

    // --- Route Approval (Untuk Ketua Divisi & HRD) ---
    Route::get('/approvals', [LeaveApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{leaveRequest}', [LeaveApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{leaveRequest}/approve', [LeaveApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{leaveRequest}/reject', [LeaveApprovalController::class, 'reject'])->name('approvals.reject');

    // Route Log Aktivitas
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index')
        ->middleware('can:access-admin-panel');
});


// --- Route Khusus Admin (Super Admin) ---
Route::middleware(['auth', 'verified', 'can:access-admin-panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users/role/{role?}', [UserRolesTableController::class, 'index'])->name('users.index');

    // Manajemen Pengguna (CRUD)
    Route::resource('users', UserController::class)->except('index');

    // Manajemen Divisi (CRUD)
    Route::resource('divisions', DivisionController::class);
    Route::post('divisions/{division}/add-member', [DivisionController::class, 'addMember'])->name('divisions.addMember');
    Route::post('/divisions/{division}/remove-member/{user}', [DivisionController::class, 'removeMember'])
        ->name('divisions.removeMember');

    // Route Log Aktivitas
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');
});


require __DIR__ . '/auth.php';
