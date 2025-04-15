<?php

namespace App\Events;

use App\Models\Penyewaan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PenyewaanStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $penyewaan;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     *
     * @param Penyewaan $penyewaan
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Penyewaan $penyewaan, $oldStatus, $newStatus)
    {
        $this->penyewaan = $penyewaan;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}