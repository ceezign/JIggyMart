<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessfulNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Order $order, public readonly Transaction $transaction)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Payment confirmed for order {$this->order->order_number}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your payment of {$this->order->currency} ".number_format((float) $this->transaction->amount, 2).' was successful.')
            ->line('Your order is now being processed.')
            ->action('View Order', url("/dashboard/orders/{$this->order->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return ['order_id' => $this->order->id, 'transaction_reference' => $this->transaction->reference, 'type' => 'payment_successful'];
    }
}
