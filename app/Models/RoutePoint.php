<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutePoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_route_id',
        'checkpoint_id',
        'latitude',
        'longitude',
        'visited_at',
        'sequence_order',
        'is_planned',
        'is_visited',
        'speed_from_previous',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_planned' => 'boolean',
        'is_visited' => 'boolean',
        'speed_from_previous' => 'decimal:2',
    ];

    public function dailyRoute(): BelongsTo
    {
        return $this->belongsTo(DailyRoute::class);
    }

    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
