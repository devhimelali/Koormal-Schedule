<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForkliftManitouSchedule extends Model
{
    protected $connection = 'mysql1';
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'status',
        'is_today_works',
        'is_technician_entry',
    ];

    /**
     * Get the user that owns the Light Vehicle Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(ForkliftManitouAsset::class, 'asset_no', 'asset_no');
    }
}
