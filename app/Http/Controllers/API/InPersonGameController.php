<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInPersonGameApiRequest;
use App\Services\InPersonGameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InPersonGameController extends Controller
{
    public function __construct(
        private InPersonGameService $inPersonGameService
    ) {}
    public function store(StoreInPersonGameApiRequest $request): JsonResponse
    {
        $game = $this->inPersonGameService->createGame($request->validated());

        return response()->json($game, 201);
    }

    public function show(string $token): JsonResponse
    {
        $game = $this->inPersonGameService->getGameWithParticipants($token);

        return response()->json($game);
    }

    public function participants(string $token): JsonResponse
    {
        $participants = $this->inPersonGameService->getParticipants($token);

        return response()->json($participants);
    }

    public function reveal(string $token, int $participantId): JsonResponse
    {
        try {
            $participant = $this->inPersonGameService->revealParticipant($token, $participantId);

            return response()->json([
                'name' => $participant->name,
                'assigned_to' => $participant->assigned_to,
                'price_limit' => $participant->game->price_limit,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
