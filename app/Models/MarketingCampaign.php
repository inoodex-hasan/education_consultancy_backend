<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'boosting_status',
        'created_by',
        'notes',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function videos()
    {
        return $this->hasMany(MarketingVideo::class, 'campaign_id');
    }

    public function posters()
    {
        return $this->hasMany(MarketingPoster::class, 'campaign_id');
    }
}
