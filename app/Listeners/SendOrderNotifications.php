<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Events\OrderDelivered;
use App\Events\OrderPlaced;
use App\Events\OrderShipped;
use App\Events\PaymentFailed;
use App\Events\PaymentSuccessful;
use App\Notifications\OrderCancelledNotification;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderShippedNotification;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PaymentSuccessfulNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderNotifications implements ShouldQueue
{
    public function handleOrderPlaced(OrderPlaced $event): void
    {
        $event->order->user->notify(new OrderPlacedNotification($event->order));
    }

    public function handlePaymentSuccessful(PaymentSuccessful $event): void
    {
        $event->order->user->notify(new PaymentSuccessfulNotification($event->order, $event->transaction));
    }

    public function handlePaymentFailed(PaymentFailed $event): void
    {
        $event->order->user->notify(new PaymentFailedNotification($event->order));
    }

    public function handleOrderShipped(OrderShipped $event): void
    {
        $event->order->user->notify(new OrderShippedNotification($event->order));
    }

    public function handleOrderDelivered(OrderDelivered $event): void
    {
        $event->order->user->notify(new OrderDeliveredNotification($event->order));
    }

    public function handleOrderCancelled(OrderCancelled $event): void
    {
        $event->order->user->notify(new OrderCancelledNotification($event->order));
    }

    public function subscribe(): array
    {
        return [
            OrderPlaced::class => 'handleOrderPlaced',
            PaymentSuccessful::class => 'handlePaymentSuccessful',
            PaymentFailed::class => 'handlePaymentFailed',
            OrderShipped::class => 'handleOrderShipped',
            OrderDelivered::class => 'handleOrderDelivered',
            OrderCancelled::class => 'handleOrderCancelled',
        ];
    }
}
