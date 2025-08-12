<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadPdf extends Model
{
    protected $connection = 'mysql1';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
