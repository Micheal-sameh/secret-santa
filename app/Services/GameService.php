<?php

namespace App\Services;

use App\Models\Game;
use App\Repositories\AssignmentRepository;
use App\Repositories\GameRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class GameService
{
    public function __construct(
        private GameRepository $gameRepository,
        private AssignmentRepository $assignmentRepository
    ) {}

    public function getUserGames(int $userId): array
    {
        return [
            'createdGames' => $this->gameRepository->getCreatedGames($userId),
            'joinedGames' => $this->gameRepository->getJoinedGames($userId),
            'assignedGames' => $this->gameRepository->getAssignedGames($userId),
        ];
    }

    public function createGame(array $data, int $hostId): Game
    {
        $game = $this->gameRepository->create([
            ...$data,
            'host_id' => $hostId,
            'join_token' => Game::generateToken(),
        ]);

        // Host auto-joins
        $this->gameRepository->attachParticipant($game, $hostId);

        return $game->load('host', 'participants');
    }

    public function getGameWithDetails(string $token): Game
    {
        return $this->gameRepository->findByToken($token)
            ->load(['host', 'participants']);
    }

    public function joinGame(string $token, int $userId): Game
    {
        $game = $this->gameRepository->findByToken($token);

        if ($game->isExpired()) {
            throw new \Exception('The registration deadline for this game has passed.');
        }

        if ($game->isAssigned()) {
            throw new \Exception('Assignments have already been made. You cannot join now.');
        }

        $this->gameRepository->attachParticipant($game, $userId);

        return $game->load('participants');
    }

    public function assignParticipants(int $gameId, int $hostId): Game
    {
        $game = $this->gameRepository->findById($gameId);

        if ($game->host_id !== $hostId) {
            throw new \Exception('Only the host can assign participants.');
        }

        if ($game->isAssigned()) {
            throw new \Exception('Assignments have already been made for this game.');
        }

        if (!$game->isExpired()) {
            throw new \Exception('You can only assign after the registration deadline has passed.');
        }

        $participantIds = $this->gameRepository->getParticipantIds($game);

        if (count($participantIds) < 2) {
            throw new \Exception('You need at least 2 participants to run assignments.');
        }

        $pairs = $this->generateDerangement($participantIds);

        foreach ($pairs as $pair) {
            $this->assignmentRepository->create([
                'game_id' => $game->id,
                'giver_id' => $pair['giver'],
                'receiver_id' => $pair['receiver'],
            ]);
        }

        $this->gameRepository->update($game, ['assigned_at' => now()]);

        return $game->fresh();
    }

    public function getGameParticipants(string $token): array
    {
        $game = $this->gameRepository->findByToken($token)->load('participants');

        return [
            'count' => $game->participants->count(),
            'participants' => $game->participants->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'avatar' => $p->avatar,
                'is_host' => $p->id === $game->host_id,
            ]),
        ];
    }

    public function getUserAssignment(int $gameId, int $userId): ?array
    {
        $assignment = $this->assignmentRepository->findByGameAndGiver($gameId, $userId);

        return $assignment ? $assignment->toArray() : null;
    }

    /**
     * Sattolo's cycle algorithm — guarantees a single-cycle derangement (no self-assignments).
     */
    private function generateDerangement(array $ids): array
    {
        $receivers = array_values($ids);
        $n = count($receivers);

        for ($i = $n - 1; $i > 0; $i--) {
            $j = random_int(0, $i - 1);
            [$receivers[$i], $receivers[$j]] = [$receivers[$j], $receivers[$i]];
        }

        $pairs = [];
        foreach (array_values($ids) as $idx => $giverId) {
            $pairs[] = ['giver' => $giverId, 'receiver' => $receivers[$idx]];
        }

        return $pairs;
    }
}