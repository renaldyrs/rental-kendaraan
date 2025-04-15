<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kendaraan;
use Illuminate\Auth\Access\HandlesAuthorization;

class KendaraanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function view(User $user, Kendaraan $kendaraan)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function update(User $user, Kendaraan $kendaraan)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function delete(User $user, Kendaraan $kendaraan)
    {
        // Hanya admin yang bisa menghapus
        return $user->role == 'admin';
    }

    public function updateStatus(User $user, Kendaraan $kendaraan)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }
}