<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InPersonParticipant extends Model
{
    use HasFactory;

    protected $table = 'in_person_participants';

    protected $fillable = [
        'game_id',
        'name',
        'assigned_to',
        'revealed',
        'reveal_order',
    ];

    protected $casts = [
        'revealed' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(InPersonGame::class, 'game_id');
    }
}
