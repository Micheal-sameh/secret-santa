<?php

namespace App\Services;

use App\DTOs\CreateInPersonGameData;
use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use App\Repositories\InPersonGameRepository;

class InPersonGameService
{
    public function __construct(
        private InPersonGameRepository $inPersonGameRepository
    ) {}

    public function createGame(CreateInPersonGameData $data): InPersonGame
    {
        $game = $this->inPersonGameRepository->create([
            'name'         => $data->name,
            'price_limit'  => $data->priceLimit,
            'device_token' => InPersonGame::generateToken(),
            'assigned'     => true,
        ]);

        $this->inPersonGameRepository->createParticipants($game, $data->participantNames);

        return $game->load('participants');
    }

    public function getGameWithParticipants(string $token): InPersonGame
    {
        return $this->inPersonGameRepository->findByToken($token)
            ->load('participants');
    }

    public function getNextParticipant(string $token): ?InPersonParticipant
    {
        $game = $this->inPersonGameRepository->findByToken($token);
        return $this->inPersonGameRepository->getNextParticipant($game);
    }

    public function revealParticipant(string $token, int $participantId): InPersonParticipant
    {
        $game        = $this->inPersonGameRepository->findByToken($token);
        $participant = $game->participants()->findOrFail($participantId);

        if ($participant->revealed) {
            throw new \Exception('This participant has already been revealed.');
        }

        $this->inPersonGameRepository->markAsRevealed($participant);

        return $participant;
    }

    public function getParticipants(string $token): array
    {
        $game = $this->inPersonGameRepository->findByToken($token)->load('participants');

        return [
            'total'        => $game->participants->count(),
            'revealed'     => $game->participants->where('revealed', true)->count(),
            'remaining'    => $game->participants->where('revealed', false)->count(),
            'participants' => $game->participants->map(fn ($p) => [
                'id'       => $p->id,
                'name'     => $p->name,
                'revealed' => $p->revealed,
            ]),
        ];
    }
}
