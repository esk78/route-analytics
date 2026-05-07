<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'controller_id',
        'route_date',
        'planned_points_count',
        'completed_points_count',
        'completion_percentage',
        'average_speed',
    ];

    protected $casts = [
        'route_date' => 'date',
        'completion_percentage' => 'decimal:2',
        'average_speed' => 'decimal:2',
    ];

    public function controller(): BelongsTo
    {
        return $this->belongsTo(Controller::class);
    }

    public function routePoints(): HasMany
    {
        return $this->hasMany(RoutePoint::class);
    }
}
