<?php

namespace App\Notifications;

use App\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LoanApproved extends Notification
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
                    ->subject('Loan Approved.')
                    ->greeting('Hi ' . $loan->loanee->firstname)
                    ->line('We are glad to inform you that your loan request with reference number ' . $loan->reference . ' has been approved.')
                    ->line('Your bank account number will be credited with the total amount of ' . $loan->principal . ' within the next 24hrs.')
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
