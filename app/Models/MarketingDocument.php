<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingDocument extends Model
{
    protected $fillable = [
        'application_id',
        'document_name',
        'document_type',
        'status',
        'notes',
        'created_by',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'received' => 'Received',
            'not_received' => 'Not Received',
            'ready' => 'Ready',
            'submitted' => 'Submitted',
            default => $this->status,
        };
    }

    public function getStatusClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-outline-dark',
            'received' => 'badge-outline-info',
            'not_received' => 'badge-outline-danger',
            'ready' => 'badge-outline-warning',
            'submitted' => 'badge-outline-success',
            default => 'badge-outline-dark',
        };
    }

    public function getDocumentTypeLabel(): string
    {
        return match($this->document_type) {
            'sop' => 'SOP',
            'cv' => 'CV',
            'cl' => 'Cover Letter',
            default => $this->document_type,
        };
    }
}
