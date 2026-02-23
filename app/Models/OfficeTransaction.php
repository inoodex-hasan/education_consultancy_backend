<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
        'transaction_date',
        'transaction_type',
        'reference',
        'notes',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (auth()->check() && !$transaction->created_by) {
                $transaction->created_by = auth()->id();
            }
        });

        static::created(function ($transaction) {
            // Deduct from source account
            if ($transaction->from_account_id) {
                $fromAccount = OfficeAccount::find($transaction->from_account_id);
                if ($fromAccount) {
                    $fromAccount->decrement('remaining_balance', $transaction->amount);
                }
            }

            // Add to destination account
            if ($transaction->to_account_id) {
                $toAccount = OfficeAccount::find($transaction->to_account_id);
                if ($toAccount) {
                    $toAccount->increment('remaining_balance', $transaction->amount);
                }
            }
        });

        static::deleted(function ($transaction) {
            // Reverse the transaction when deleted
            if ($transaction->from_account_id) {
                $fromAccount = OfficeAccount::find($transaction->from_account_id);
                if ($fromAccount) {
                    $fromAccount->increment('remaining_balance', $transaction->amount);
                }
            }

            if ($transaction->to_account_id) {
                $toAccount = OfficeAccount::find($transaction->to_account_id);
                if ($toAccount) {
                    $toAccount->decrement('remaining_balance', $transaction->amount);
                }
            }
        });
    }

    public function fromAccount()
    {
        return $this->belongsTo(OfficeAccount::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(OfficeAccount::class, 'to_account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
