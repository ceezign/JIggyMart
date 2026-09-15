<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
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
            ->subject("Order {$this->order->order_number} received")
            ->greeting("Hi {$notifiable->name},")
            ->line("We've received your order {$this->order->order_number} totalling {$this->order->currency} ".number_format((float) $this->order->grand_total, 2).'.')
            ->line('We will email you again once payment is confirmed.')
            ->action('View Order', url("/dashboard/orders/{$this->order->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return ['order_id' => $this->order->id, 'order_number' => $this->order->order_number, 'type' => 'order_placed'];
    }
}
