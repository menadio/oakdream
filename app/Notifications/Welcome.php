<?php

namespace App\Notifications;

use App\Loanee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Welcome extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loanee $loanee)
    {
        $this->loanee = $loanee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $loanee = $this->loanee;

        return (new MailMessage)
                    ->from('hello@oakdream.ng', 'Oakdream')
                    ->subject('Welcome')
                    ->greeting('Hi ' . $loanee->firstname)
                    ->line('Your new Oakdream financing account number is ' . $loanee->account)
                    ->line('Thanks for choosing us!');
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
            //
        ];
    }
}
