<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VfsChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'checklist_item',
        'is_checked',
        'checked_by',
        'checked_at',
        'notes',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
