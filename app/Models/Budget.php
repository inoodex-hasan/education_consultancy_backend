<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'amount',
        'period',
        'start_date',
        'end_date',
        'created_by',
        'notes',
    ];

    protected static function booted()
    {
        static::creating(function ($budget) {
            if (auth()->check() && !$budget->created_by) {
                $budget->created_by = auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
