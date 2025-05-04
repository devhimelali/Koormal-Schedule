<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LightVehicleAsset extends Model
{
    protected $connection = 'mysql1';
    protected $fillable = [
        'asset_no',
        'department',
        'description',
        'next_due_date',
        'is_technician_entry',
    ];
}
