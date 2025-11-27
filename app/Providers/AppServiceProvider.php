<?php

namespace App\Providers;

// --- Imports yang diperlukan ---
use App\Models\User; // Import Model User
use App\Policies\UserPolicy; // Import Policy User
use App\Models\Division; // Import Model Division
use App\Policies\DivisionPolicy; // Import Policy Division

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Mendaftarkan kebijakan (Policy) untuk setiap Model
        User::class => UserPolicy::class,
        Division::class => DivisionPolicy::class, // Baris ini juga harus diimport!
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- Definisi Gate (Hak Akses Area Luas) ---

        // 1. Gate untuk Akses Panel Admin (Hanya Super Admin)
        Gate::define('access-admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        // 2. Gate untuk Akses Fitur HRD (Admin dan HRD)
        Gate::define('access-hrd-features', function (User $user) {
            return $user->isAdmin() || $user->isHrd();
        });

        // 3. Gate untuk Verifikasi Ketua Divisi (Leader Approval)
        Gate::define('leader-verification', function (User $user) {
            return $user->isAdmin() || $user->isDivisionHead();
        });
        
        // 4. Gate untuk Hak Pembatalan Cuti (Hanya pemilik dan status pending)
        Gate::define('cancel-leave-request', function (User $user, $leaveRequest) {
            return $user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending';
        });
    }
}