<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Penyewaan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenyewaanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function view(User $user, Penyewaan $penyewaan)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->role == 'staff';
    }

    public function update(User $user, Penyewaan $penyewaan)
    {
        // Hanya bisa update jika status reservasi
        return ($user->role == 'admin' || $user->role == 'staff') && $penyewaan->status == 'reservasi';
    }

    public function delete(User $user, Penyewaan $penyewaan)
    {
        // Hanya admin yang bisa menghapus dan hanya jika belum berjalan
        return $user->role == 'admin' && $penyewaan->status != 'berjalan';
    }

    public function start(User $user, Penyewaan $penyewaan)
    {
        return ($user->role == 'admin' || $user->role == 'staff') && $penyewaan->status == 'reservasi';
    }

    public function complete(User $user, Penyewaan $penyewaan)
    {
        return ($user->role == 'admin' || $user->role == 'staff') && $penyewaan->status == 'berjalan';
    }

    public function cancel(User $user, Penyewaan $penyewaan)
    {
        return ($user->role == 'admin' || $user->role == 'staff') && 
               in_array($penyewaan->status, ['reservasi', 'berjalan']);
    }

    public function payment(User $user, Penyewaan $penyewaan)
    {
        return ($user->role == 'admin' || $user->role == 'staff') && 
               $penyewaan->status_pembayaran != 'lunas';
    }
}