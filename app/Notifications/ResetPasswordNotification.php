<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $token;
    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = url("{$this->token}");

        return (new MailMessage)
            ->subject('Notification de réinitialisation du mot de passe')
            ->greeting('Bonjour à vous !')
            ->line('Vous recevez cet e-mail parce que nous avons reçu une demande de réinitialisation du mot de passe de votre compte.')
            ->action('Reset Password', $url)
            ->line('Si vous n\'avez pas demandé la réinitialisation de votre mot de passe, aucune autre action n\'est requise.');
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
