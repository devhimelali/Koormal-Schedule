<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EmailLog extends Model
{
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'sent_date',
        'sent_time',
        'email_body',
        'is_sent',
        'recipient_email',
        'email_subject',
        'asset_type',
    ];


    /**
     * Format the sent date as d-m-Y when retrieving it from the database, and
     * format it as Y-m-d when saving it to the database.
     */
    protected function sentDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    /**
     * Format the sent time as h:i A when retrieving it from the database, and
     * format it as H:i:s when saving it to the database.
     */
    protected function sentTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('h:i A'),
            set: fn($value) => Carbon::parse($value)->format('H:i:s'),
        );
    }
}
