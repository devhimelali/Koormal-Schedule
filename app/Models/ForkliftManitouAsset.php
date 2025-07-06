<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForkliftManitouAsset extends Model
{
    protected $connection = 'mysql1';
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'is_technician_entry',
    ];

    /**
     * Get the asset emails associated with the light vehicle asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function assetEmails(): MorphMany
    {
        return $this->morphMany(AssetEmail::class, 'assetable');
    }
}
