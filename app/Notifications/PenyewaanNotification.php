<?php

namespace App\Notifications;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenyewaanNotification extends Notification
{
    use Queueable;

    public $penyewaan;
    public $message;
    public $actionUrl;

    public function __construct(Penyewaan $penyewaan, $message, $actionUrl)
    {
        $this->penyewaan = $penyewaan;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Status Penyewaan Kendaraan')
                    ->line($this->message)
                    ->action('Lihat Detail', $this->actionUrl)
                    ->line('Terima kasih telah menggunakan layanan kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'penyewaan_id' => $this->penyewaan->id
        ];
    }
}