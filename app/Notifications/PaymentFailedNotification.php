<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Payment failed for order {$this->order->order_number}")
            ->greeting("Hi {$notifiable->name},")
            ->line("We couldn't process payment for order {$this->order->order_number}.")
            ->line('Your order has been cancelled and no inventory was reserved. Please try again.')
            ->action('Retry Checkout', url('/cart'));
    }

    public function toArray(object $notifiable): array
    {
        return ['order_id' => $this->order->id, 'type' => 'payment_failed'];
    }
}
