<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id', 'gateway', 'gateway_reference', 'amount', 'status', 'raw_payload',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'raw_payload' => 'array'];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
