<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InPersonGameController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'price_limit'    => ['nullable', 'numeric', 'min:0'],
            'participants'   => ['required', 'array', 'min:2'],
            'participants.*' => ['required', 'string', 'max:100', 'distinct'],
        ]);

        $game = InPersonGame::create([
            'name'         => $request->name,
            'price_limit'  => $request->price_limit,
            'device_token' => InPersonGame::generateToken(),
        ]);

        $names = array_values(array_filter(array_map('trim', $request->participants)));

        foreach ($names as $order => $name) {
            InPersonParticipant::create([
                'game_id'      => $game->id,
                'name'         => $name,
                'reveal_order' => $order + 1,
            ]);
        }

        $assigned = $this->satolloDerangement($names);
        foreach ($game->participants as $idx => $participant) {
            $participant->update(['assigned_to' => $assigned[$idx]]);
        }

        $game->update(['assigned' => true]);

        return response()->json($game->load('participants'), 201);
    }

    public function show(string $token): JsonResponse
    {
        $game = InPersonGame::where('device_token', $token)
            ->with('participants')
            ->firstOrFail();

        return response()->json($game);
    }

    public function participants(string $token): JsonResponse
    {
        $game = InPersonGame::where('device_token', $token)->firstOrFail();

        return response()->json($game->participants()->select('id', 'name', 'revealed', 'reveal_order')->get());
    }

    public function reveal(string $token, int $participantId): JsonResponse
    {
        $game        = InPersonGame::where('device_token', $token)->firstOrFail();
        $participant = InPersonParticipant::where('game_id', $game->id)
            ->where('id', $participantId)
            ->firstOrFail();

        $participant->update(['revealed' => true]);

        return response()->json([
            'name'        => $participant->name,
            'assigned_to' => $participant->assigned_to,
            'price_limit' => $game->price_limit,
        ]);
    }

    private function satolloDerangement(array $names): array
    {
        $receivers = array_values($names);
        $n         = count($receivers);

        for ($i = $n - 1; $i > 0; $i--) {
            $j = random_int(0, $i - 1);
            [$receivers[$i], $receivers[$j]] = [$receivers[$j], $receivers[$i]];
        }

        return $receivers;
    }
}
