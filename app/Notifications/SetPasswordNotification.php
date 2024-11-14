<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false));

        return (new MailMessage)
            ->subject('Establece tu Contraseña')
            ->greeting("¡Hola {$notifiable->name}!")
            ->line('Has sido registrado en nuestro sistema. Por favor, establece tu contraseña para acceder a la plataforma.')
            ->action('Establecer Contraseña', $url)
            ->line('Si no solicitaste esto, puedes ignorar este mensaje.');
    }
}