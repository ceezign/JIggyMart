<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Order $order, public readonly Transaction $transaction)
    {
    }
}
