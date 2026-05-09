<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlannedRoutePoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_route_id',
        'checkpoint_id',
        'sequence_order',
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
