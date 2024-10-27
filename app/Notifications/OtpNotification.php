<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class OtpNotification extends Notification
{
    use Queueable;

    public $otp;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //$currentDateTime = Carbon::now()->format('d-m-Y H:i:s'); 

        return (new MailMessage)
                    ->subject('Tu código OTP para restablecer la contraseña')
                    ->greeting('Hola, ' . $this->user->name . '!')
                    ->line('Tu código OTP para restablecer tu contraseña es:')
                    ->line($this->otp)
                    ->line('Este código es válido por 10 minutos.')
                    //->line('Fecha y hora de la solicitud: ' . $currentDateTime)
                    ->line('Si no solicitaste este código, puedes ignorar este correo.')
                    ->salutation('Gracias, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
