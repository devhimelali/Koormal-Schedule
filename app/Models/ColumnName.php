<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColumnName extends Model
{
    protected $fillable = [
        'column_name',
        'column_title',
    ];
}
