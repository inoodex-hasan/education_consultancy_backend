<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VfsChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'country_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public static function getActiveItems(?int $countryId = null): array
    {
        $query = self::where('is_active', true);

        if ($countryId) {
            // Get global items (no country) + country-specific items
            $query->where(function ($q) use ($countryId) {
                $q->whereNull('country_id')
                    ->orWhere('country_id', $countryId);
            });
        }

        return $query->orderBy('sort_order')
            ->pluck('item_name')
            ->toArray();
    }

    public static function getItemsForCountry(?int $countryId = null): array
    {
        return self::getActiveItems($countryId);
    }
}
