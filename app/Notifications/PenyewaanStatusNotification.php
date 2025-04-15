<?php

namespace App\Notifications;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenyewaanStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $penyewaan;
    public $message;
    public $actionUrl;

    /**
     * Create a new notification instance.
     *
     * @param Penyewaan $penyewaan
     * @param string $message
     * @param string $actionUrl
     */
    public function __construct(Penyewaan $penyewaan, $message, $actionUrl)
    {
        $this->penyewaan = $penyewaan;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Status Penyewaan Kendaraan')
                    ->line($this->message)
                    ->action('Lihat Detail', $this->actionUrl)
                    ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'penyewaan_id' => $this->penyewaan->id
        ];
    }
}