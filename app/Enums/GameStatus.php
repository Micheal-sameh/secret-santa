<?php

namespace App\Enums;

enum GameStatus: string
{
    case Open   = 'open';
    case Closed = 'closed';
    case Drawn  = 'drawn';

    public function label(): string
    {
        return match($this) {
            self::Open   => 'Open',
            self::Closed => 'Closed',
            self::Drawn  => 'Drawn',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Open   => 'bg-blue-500/20 text-blue-400',
            self::Closed => 'bg-slate-500/20 text-slate-400',
            self::Drawn  => 'bg-green-500/20 text-green-400',
        };
    }

    public static function fromGame(\App\Models\Game $game): self
    {
        if ($game->assigned_at !== null) {
            return self::Drawn;
        }

        if ($game->end_date !== null && $game->end_date->isPast()) {
            return self::Closed;
        }

        return self::Open;
    }
}
