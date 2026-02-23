<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'symbol', 'is_active', 'exchange_rate', 'last_updated_at'];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'last_updated_at' => 'datetime',
    ];
}
