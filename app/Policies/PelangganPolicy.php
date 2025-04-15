<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pelanggan;

class PelangganPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function verifikasi(User $user, Pelanggan $pelanggan)
{
    // Hanya admin atau staff dengan hak verifikasi yang bisa melakukan verifikasi
    return $user->role === 'admin' || ($user->role === 'staff' && $user->can_verify);
}
}
