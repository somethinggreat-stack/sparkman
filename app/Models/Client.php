<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'lead_id', 'first_name', 'last_name', 'middle_name', 'suffix',
        'email', 'phone', 'street', 'city', 'state', 'zip',
        'ssn', 'dob', 'service', 'status', 'crc_synced_at', 'crc_contact_id', 'meta',
    ];

    protected $casts = [
        'ssn'           => 'encrypted', // SSN encrypted at rest
        'dob'           => 'date',
        'crc_synced_at' => 'datetime',
        'meta'          => 'array',
    ];

    protected $hidden = [
        'ssn',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /** Last 4 of SSN for admin display (never show full SSN). */
    public function ssnLast4(): ?string
    {
        $ssn = preg_replace('/\D/', '', (string) $this->ssn);
        return $ssn ? substr($ssn, -4) : null;
    }
}
