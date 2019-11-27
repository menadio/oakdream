<?php

namespace App\Notifications;

use App\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LoanDenied extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
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
        $loan = $this->loan;

        return (new MailMessage)
                    ->from('finance@oakdream.ng', 'Oakdream')
                    ->subject('Loan Denied')
                    ->greeting('Hi ' . $loan->loanee->firstname . ',')
                    ->line('Unfortunately, your loan request with the reference number ' . $loan->reference . ' was not approved this time.')
                    ->line('Your account officer will call you to explain why it was denied. You can reapply after discussing with him/her.')
                    ->line('Thanks for choosing Oakdream!');
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
