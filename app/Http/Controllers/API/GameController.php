<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $created = Game::where('host_id', $user->id)
            ->withCount('participants')
            ->orderBy('meeting_date')->get();

        $joined = $user->participatedGames()
            ->where('host_id', '!=', $user->id)
            ->withCount('participants')
            ->orderBy('meeting_date')->get();

        $assigned = Game::whereHas('assignments', fn($q) => $q->where('giver_id', $user->id))
            ->withCount('participants')
            ->orderBy('meeting_date')->get();

        return response()->json(compact('created', 'joined', 'assigned'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'price_limit'  => ['nullable', 'numeric', 'min:0'],
            'end_date'     => ['required', 'date', 'after:today'],
            'meeting_date' => ['required', 'date', 'after:end_date'],
        ]);

        $game = Game::create([
            ...$data,
            'host_id'    => $request->user()->id,
            'join_token' => Game::generateToken(),
        ]);

        $game->participants()->attach($request->user()->id);

        return response()->json($game->load('host', 'participants'), 201);
    }

    public function show(string $token): JsonResponse
    {
        $game = Game::where('join_token', $token)
            ->with(['host', 'participants'])
            ->firstOrFail();

        return response()->json($game);
    }

    public function join(Request $request, string $token): JsonResponse
    {
        $game = Game::where('join_token', $token)->firstOrFail();

        if ($game->isExpired()) {
            return response()->json(['error' => 'Registration deadline has passed.'], 422);
        }

        if ($game->isAssigned()) {
            return response()->json(['error' => 'Assignments already made.'], 422);
        }

        $game->participants()->syncWithoutDetaching([$request->user()->id]);

        return response()->json(['message' => 'Joined successfully.', 'game' => $game->load('participants')]);
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        $game = Game::findOrFail($id);

        if ($game->host_id !== $request->user()->id) {
            return response()->json(['error' => 'Only the host can assign participants.'], 403);
        }

        if ($game->isAssigned()) {
            return response()->json(['error' => 'Assignments already made.'], 422);
        }

        if (!$game->isExpired()) {
            return response()->json(['error' => 'Can only assign after the registration deadline.'], 422);
        }

        $participantIds = $game->participants()->pluck('users.id')->toArray();

        if (count($participantIds) < 2) {
            return response()->json(['error' => 'Need at least 2 participants.'], 422);
        }

        $pairs = $this->satolloDerangement($participantIds);

        foreach ($pairs as $pair) {
            Assignment::create([
                'game_id'     => $game->id,
                'giver_id'    => $pair['giver'],
                'receiver_id' => $pair['receiver'],
            ]);
        }

        $game->update(['assigned_at' => now()]);

        return response()->json(['message' => 'Assignments made!', 'game' => $game->fresh()]);
    }

    public function myAssignment(Request $request, int $id): JsonResponse
    {
        $game = Game::findOrFail($id);

        $assignment = Assignment::where('game_id', $game->id)
            ->where('giver_id', $request->user()->id)
            ->with('receiver')
            ->first();

        if (!$assignment) {
            return response()->json(['error' => 'No assignment found.'], 404);
        }

        return response()->json($assignment);
    }

    private function satolloDerangement(array $ids): array
    {
        $receivers = array_values($ids);
        $n         = count($receivers);

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
