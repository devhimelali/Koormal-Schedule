<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetEmail extends Model
{
    protected $fillable = [
        'asset_id',
        'email_id',
    ];

    /**
     * Get the asset that owns the AssetEmail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the email that owns the AssetEmail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
