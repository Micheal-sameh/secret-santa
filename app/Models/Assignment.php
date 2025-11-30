<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'giver_participant_id',
        'recipient_participant_id',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function giver()
    {
        return $this->belongsTo(Participant::class, 'giver_participant_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Participant::class, 'recipient_participant_id');
    }
}
