<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameApiRequest;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(
        private GameService $gameService
    ) {}
    public function index(Request $request): JsonResponse
    {
        $games = $this->gameService->getUserGames($request->user()->id);

        return response()->json($games);
    }

    public function store(StoreGameApiRequest $request): JsonResponse
    {
        $game = $this->gameService->createGame($request->validated(), $request->user()->id);

        return response()->json($game, 201);
    }

    public function show(string $token): JsonResponse
    {
        $game = $this->gameService->getGameWithDetails($token);

        return response()->json($game);
    }

    public function join(Request $request, string $token): JsonResponse
    {
        try {
            $game = $this->gameService->joinGame($token, $request->user()->id);

            return response()->json([
                'message' => 'Joined successfully.',
                'game' => $game
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        try {
            $game = $this->gameService->assignParticipants($id, $request->user()->id);

            return response()->json([
                'message' => 'Assignments made!',
                'game' => $game
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function myAssignment(Request $request, int $id): JsonResponse
    {
        $assignment = $this->gameService->getUserAssignment($id, $request->user()->id);

        if (!$assignment) {
            return response()->json(['error' => 'No assignment found.'], 404);
        }

        return response()->json($assignment);
    }
}
