<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    public function routePoints(): HasMany
    {
        return $this->hasMany(RoutePoint::class);
    }

    public function scopeNear(
        Builder $query,
        float $latitude,
        float $longitude,
        float $radius = 0.5
    ): Builder {
        return $query
            ->whereBetween('latitude', [
                $latitude - $radius,
                $latitude + $radius,
            ])
            ->whereBetween('longitude', [
                $longitude - $radius,
                $longitude + $radius,
            ]);
    }
}
