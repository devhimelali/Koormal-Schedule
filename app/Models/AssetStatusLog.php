<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AssetStatusLog extends Model
{
    protected $fillable = [
        'asset_no',
        'description',
        'next_due_date',
        'change_time',
        'change_date',
        'old_status',
        'new_status',
        'asset_type',
    ];

    protected function changeDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function changeTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('h:i A'),
            set: fn($value) => Carbon::parse($value)->format('H:i:s'),
        );
    }
}
