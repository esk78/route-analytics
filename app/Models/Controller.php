<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Controller extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'email',
        'phone',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function dailyRoutes(): HasMany
    {
        return $this->hasMany(DailyRoute::class);
    }
}
