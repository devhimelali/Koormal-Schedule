<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'status',
        'is_today_works',
        'is_technician_entry',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_no', 'asset_no');
    }
}
