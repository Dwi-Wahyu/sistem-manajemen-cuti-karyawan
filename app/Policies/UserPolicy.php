<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Metode before() memberikan izin penuh kepada Admin (Superuser).
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null; 
    }

    /**
     * viewAny: Menampilkan daftar pengguna. Hanya Admin dan HRD.
     */
    public function viewAny(User $user): bool
    {
        return $user->isHrd(); // Admin sudah dicakup oleh before()
    }

    /**
     * create: Membuat pengguna baru. Hanya Admin.
     */
    public function create(User $user): bool
    {
        return false; 
    }

    /**
     * update: Memperbarui data pengguna lain. Hanya Admin.
     */
    public function update(User $user, User $model): bool
    {
        // Admin boleh edit semua. Pastikan Admin tidak sengaja mengedit dirinya sendiri (opsional)
        return $user->id !== $model->id; 
    }

    /**
     * delete: Menghapus pengguna. Hanya Admin, dan hanya boleh menghapus Karyawan atau Ketua Divisi.
     */
    public function delete(User $user, User $model): bool
    {
        // 1. Tidak boleh menghapus diri sendiri
        if ($user->id === $model->id) {
            return false;
        }
        
        // 2. Hanya boleh menghapus Karyawan atau Ketua Divisi
        if ($model->isEmployee() || $model->isDivisionHead()) {
            return true;
        }

        return false;
    }

    /**
     * view: Melihat detail pengguna (profil). Boleh oleh Admin, HRD, dan pengguna dapat melihat profilnya sendiri.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isHrd() || $user->id === $model->id;
    }
}