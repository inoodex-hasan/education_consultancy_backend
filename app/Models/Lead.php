<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'email',
        'phone',
        'current_education',
        'preferred_country',
        'preferred_course',
        'source',
        'status',
        'notes',
        'last_contacted_at',
        'next_follow_up_at',
        'follow_up_history',
        'created_by',
        'consultant_id',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'follow_up_history' => 'array',
    ];

    public function getFollowUpDateHistoryAttribute(): array
    {
        $history = collect($this->follow_up_history ?? []);
        $currentFollowUpDate = $this->next_follow_up_at?->toDateString();

        if ($currentFollowUpDate !== null && $history->last() !== $currentFollowUpDate) {
            $history->push($currentFollowUpDate);
        }

        return $history
            ->filter(fn ($date) => filled($date))
            ->map(fn ($date) => $date instanceof Carbon ? $date : Carbon::parse($date))
            ->values()
            ->all();
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function consultant()
    {
        return $this->belongsTo(\App\Models\User::class, 'consultant_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'preferred_country');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'preferred_course');
    }
}
