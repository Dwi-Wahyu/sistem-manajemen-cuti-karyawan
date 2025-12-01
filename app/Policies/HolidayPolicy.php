<?php

namespace App\Policies;

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HolidayPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Admin memiliki izin penuh
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function view()
    {
        return true;
    }

    public function viewAny(User $user): bool
    {
        return $user->isHrd();
    }

    public function create(User $user): bool
    {
        return $user->isHrd();
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return $user->isHrd();
    }

    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->isHrd();
    }
}
