<?php

namespace App\Listeners;

use App\Events\PenyewaanStatusUpdated;
use App\Notifications\PenyewaanStatusNotification;


class SendPenyewaanStatusNotification
{
    /**
     * Handle the event.
     *
     * @param PenyewaanStatusUpdated $event
     * @return void
     */
    public function handle(PenyewaanStatusUpdated $event)
    {
        $messages = [
            'reservasi' => 'Penyewaan Anda telah berhasil dibuat dan menunggu konfirmasi.',
            'berjalan' => 'Penyewaan Anda telah dimulai. Selamat menggunakan kendaraan!',
            'selesai' => 'Penyewaan Anda telah selesai. Terima kasih!',
            'batal' => 'Penyewaan Anda telah dibatalkan.'
        ];
        
        $event->penyewaan->pelanggan->notify(
            new PenyewaanStatusNotification(
                $event->penyewaan,
                $messages[$event->newStatus] ?? 'Status penyewaan Anda telah berubah.',
                route('penyewaan.show', $event->penyewaan)
            )
        );
    }
}