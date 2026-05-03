<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'host_id',
        'name',
        'price_limit',
        'end_date',
        'meeting_date',
        'join_token',
        'assigned_at',
    ];

    protected $casts = [
        'end_date'    => 'date',
        'meeting_date' => 'date',
        'assigned_at' => 'datetime',
        'price_limit' => 'decimal:2',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_participants');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_at);
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(12);
        } while (static::where('join_token', $token)->exists());

        return $token;
    }
}
