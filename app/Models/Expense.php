<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'expense_date',
        'category',
        'payment_method',
        'office_account_id',
        'salary_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($expense) {
            if (!$expense->expense_date) {
                $expense->expense_date = today();
            }
            if (auth()->check() && !$expense->created_by) {
                $expense->created_by = auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function account()
    {
        return $this->belongsTo(OfficeAccount::class, 'office_account_id');
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
