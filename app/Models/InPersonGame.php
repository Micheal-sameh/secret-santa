<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class InPersonGame extends Model
{
    use HasFactory;

    protected $table = 'in_person_games';

    protected $fillable = [
        'name',
        'price_limit',
        'device_token',
        'assigned',
    ];

    protected $casts = [
        'assigned'    => 'boolean',
        'price_limit' => 'decimal:2',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(InPersonParticipant::class, 'game_id');
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(16);
        } while (static::where('device_token', $token)->exists());

        return $token;
    }
}
