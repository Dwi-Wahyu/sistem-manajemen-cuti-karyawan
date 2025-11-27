<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Division;
use Illuminate\Auth\Access\Response;

class DivisionPolicy
{
    /**
     * Metode before() untuk Divisi: Hanya Admin yang boleh.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        return false; // Tolak akses jika bukan Admin
    }

    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Division $division): bool { return false; }
    public function create(User $user): bool { return false; }
    public function update(User $user, Division $division): bool { return false; }
    public function delete(User $user, Division $division): bool { return false; }
}