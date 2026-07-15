<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    use HasFactory;

    protected $fillable = ['source', 'event_type', 'reference', 'summary', 'payload'];

    protected $casts = ['payload' => 'array'];
}
