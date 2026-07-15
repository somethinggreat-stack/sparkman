<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id', 'client_id', 'name', 'email', 'phone', 'plan', 'description',
        'amount', 'monthly_amount', 'type', 'authnet_transaction_id',
        'authnet_subscription_id', 'card_type', 'card_last4', 'status',
        'onboarding_token', 'onboarded_at', 'meta',
        'agreement_signed_at', 'agreement_signature', 'agreement_ip', 'agreement_name',
    ];

    protected $casts = [
        'amount'              => 'decimal:2',
        'monthly_amount'      => 'decimal:2',
        'onboarded_at'        => 'datetime',
        'agreement_signed_at' => 'datetime',
        'meta'                => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function isPaidCreditRepair(): bool
    {
        return $this->status === 'paid'
            && in_array($this->plan, ['individual', 'aggressive', 'couple'], true);
    }
}
