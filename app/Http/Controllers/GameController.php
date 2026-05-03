<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Models\Assignment;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private GameService $gameService
    ) {}

    public function index(): View
    {
        $games = $this->gameService->getUserGames(Auth::id());

        return view('games.index', $games);
    }

    public function create(): View
    {
        return view('games.create');
    }

    public function store(StoreGameRequest $request): RedirectResponse
    {
        $game = $this->gameService->createGame($request->validated(), Auth::id());

        return redirect()
            ->route('games.show', $game->join_token)
            ->with('success', 'Game created! Share the link below so others can join.');
    }

    public function show(string $token): View
    {
        $game = $this->gameService->getGameWithDetails($token);

        $user         = Auth::user();
        $isParticipant = $user && $game->participants->contains('id', $user->id);
        $isHost        = $user && $game->host_id === $user->id;
        $myAssignment  = null;

        if ($isParticipant && $game->isAssigned()) {
            $myAssignment = $this->gameService->getUserAssignment($game->id, $user->id);
        }

        return view('games.show', compact('game', 'isParticipant', 'isHost', 'myAssignment'));
    }

    public function join(Request $request, string $token): RedirectResponse
    {
        try {
            $this->gameService->joinGame($token, Auth::id());

            return redirect()
                ->route('games.show', $token)
                ->with('success', "You've joined the game! 🎅");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function assign(int $id): RedirectResponse
    {
        try {
            $this->gameService->assignParticipants($id, Auth::id());

            $game = $this->gameService->getGameWithDetails(
                \App\Models\Game::find($id)->join_token
            );

            return redirect()
                ->route('games.show', $game->join_token)
                ->with('success', 'Assignments are done! Everyone can now see who they\'re buying for. 🎁');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function participants(string $token): JsonResponse
    {
        $participants = $this->gameService->getGameParticipants($token);

        return response()->json($participants);
    }
}
