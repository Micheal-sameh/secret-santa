<?php

namespace App\Repositories;

use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use Illuminate\Database\Eloquent\Collection;

class InPersonGameRepository
{
    public function create(array $data): InPersonGame
    {
        return InPersonGame::create($data);
    }

    public function findByToken(string $token): ?InPersonGame
    {
        return InPersonGame::where('device_token', $token)->first();
    }

    public function findById(int $id): ?InPersonGame
    {
        return InPersonGame::find($id);
    }

    public function getUnrevealedParticipants(InPersonGame $game): Collection
    {
        return $game->participants()->where('revealed', false)->orderBy('reveal_order')->get();
    }

    public function getNextParticipant(InPersonGame $game): ?InPersonParticipant
    {
        return $game->participants()->where('revealed', false)->orderBy('reveal_order')->first();
    }

    public function markAsRevealed(InPersonParticipant $participant): bool
    {
        return $participant->update(['revealed' => true]);
    }

    public function createParticipants(InPersonGame $game, array $names): void
    {
        $order = 1;
        $assignedTos = $this->generateDerangement($names);

        foreach ($names as $index => $name) {
            $game->participants()->create([
                'name' => $name,
                'assigned_to' => $assignedTos[$index],
                'reveal_order' => $order++,
                'revealed' => false,
            ]);
        }
    }

    /**
     * Sattolo's cycle algorithm on an array of names — guarantees no self-assignments.
     */
    private function generateDerangement(array $names): array
    {
        $receivers = array_values($names);
        $n = count($receivers);

        for ($i = $n - 1; $i > 0; $i--) {
            $j = random_int(0, $i - 1);
            [$receivers[$i], $receivers[$j]] = [$receivers[$j], $receivers[$i]];
        }

        return $receivers;
    }
}