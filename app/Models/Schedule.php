<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'status',
    ];
}
