<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDeliveredNotification extends Notification implements ShouldQueue
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
            ->subject("Order {$this->order->order_number} delivered")
            ->greeting("Hi {$notifiable->name},")
            ->line('Your order has been marked as delivered. We hope you love it!')
            ->action('Leave a Review', url("/dashboard/orders/{$this->order->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return ['order_id' => $this->order->id, 'type' => 'order_delivered'];
    }
}
