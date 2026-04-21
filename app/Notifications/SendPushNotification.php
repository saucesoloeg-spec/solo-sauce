<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPushNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param $title
     */
    public function __construct($title, $message, $type)
    {
        $this->type    = $type;
        $this->title   = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['pusher'];
    }

    /**
     * Send the notification via Pusher Beams.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toPusher($notifiable)
    {
        return [
            'interest' => $this->type.'_int',
            'fcm' => [
                'notification' => [
                    'title' => $this->title,
                    'body'  => $this->message
                ],
            ],
            'apns' => [
                'aps' => [
                    'alert' => [
                        'title' => $this->title,
                    'body'  => $this->message
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
