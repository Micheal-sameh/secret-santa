<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'online_game_participant_limit',
        'inperson_game_participant_limit',
        'is_active',
    ];

    protected $casts = [
        'price'                           => 'float',
        'online_game_participant_limit'   => 'integer',
        'inperson_game_participant_limit' => 'integer',
        'is_active'                       => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
