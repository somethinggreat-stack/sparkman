<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'status', 'qualified', 'name', 'email', 'phone',
        'goal', 'funding_type', 'amount', 'message',
        'source_url', 'ip_address', 'meta', 'admin_notes',
    ];

    protected $casts = [
        'meta'      => 'array',
        'qualified' => 'boolean',
    ];

    /** Lead pipeline statuses. */
    public const STATUSES = ['new', 'contacted', 'qualified', 'won', 'lost'];

    public function statusColor(): string
    {
        return [
            'new'       => 'tag--gold',
            'contacted' => 'tag',
            'qualified' => 'tag--gold',
            'won'       => 'tag--green',
            'lost'      => 'tag--red',
        ][$this->status] ?? 'tag--slate';
    }

    /** Human-readable label for each lead type. */
    public const TYPES = [
        'funding'            => 'Funding Ready',
        'credit-repair'      => 'Credit Repair Ready',
        // legacy buckets (retired on-site forms)
        'popup'              => 'Popup Leads',
        'business-credit'    => 'Business Credit Leads',
        'debt-validation'    => 'Debt Validation Leads',
        'credit-building'    => 'Credit Building Leads',
        'financial-coaching' => 'Financial Coaching Leads',
        'credit'             => 'General Credit Leads',
    ];

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('-', ' ', (string) $this->type));
    }
}
