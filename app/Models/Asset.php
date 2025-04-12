<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
    ];

    public function assetEmails(): HasMany
    {
        return $this->hasMany(AssetEmail::class);
    }

    public function assetTimeFrames(): HasMany
    {
        return $this->hasMany(AssetTimeFrame::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(Schedule::class, 'asset_no', 'asset_no');
    }
}
