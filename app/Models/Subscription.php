<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'client_id', 'authnet_subscription_id', 'name', 'email',
        'plan', 'amount', 'interval', 'status', 'failed_payments',
        'started_at', 'next_billing_at', 'last_payment_at', 'meta',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'started_at'      => 'date',
        'next_billing_at' => 'date',
        'last_payment_at' => 'datetime',
        'failed_payments' => 'integer',
        'meta'            => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /** A subscription is "at risk" if it has failed payments or is flagged past_due. */
    public function scopeAtRisk($query)
    {
        return $query->whereIn('status', ['at_risk', 'past_due'])
            ->orWhere('failed_payments', '>', 0);
    }
}
