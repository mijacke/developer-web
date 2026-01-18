<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        $url = "{$frontendUrl}/reset-password?token={$this->token}&email={$notifiable->getEmailForPasswordReset()}";

        return (new MailMessage)
            ->subject('Obnovenie hesla - Rezidencia Žilina')
            ->greeting('Dobrý deň!')
            ->line('Dostali sme žiadosť o obnovenie hesla pre váš účet.')
            ->action('Obnoviť heslo', $url)
            ->line('Tento odkaz vyprší za 60 minút.')
            ->line('Ak ste nepožiadali o obnovenie hesla, ignorujte tento email.')
            ->salutation('S pozdravom, Rezidencia Žilina');
    }
}
