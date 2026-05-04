<?php

namespace App\Http\Controllers;

use App\DTOs\CreateGameData;
use App\Http\Requests\StoreGameRequest;
use App\Services\GameService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private GameService          $gameService,
        private SubscriptionService  $subscriptionService,
    ) {}

    public function index(): View
    {
        $games = $this->gameService->getUserGames(Auth::id());

        return view('games.index', $games);
    }

    public function create(): View
    {
        $freeLimit = $this->subscriptionService->onlineGameFreeLimit();
        $subscriptionsEnabled = $this->subscriptionService->subscriptionsEnabled();

        return view('games.create', compact('freeLimit', 'subscriptionsEnabled'));
    }

    public function store(StoreGameRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // Subscription check: we don't know participant count at creation time;
        // the limit applies when others JOIN. We check at assign time instead.
        // However, we still enforce for the feature where >limit means subscribe.
        // The actual participant limit check happens at join via game capacity.

        $game = $this->gameService->createGame(
            CreateGameData::fromRequest($request),
            $user->id
        );

        return redirect()
            ->route('games.show', $game->join_token)
            ->with('success', 'Game created! Share the link below so others can join.');
    }

    public function show(string $token): View
    {
        $game = $this->gameService->getGameWithDetails($token);

        $user          = Auth::user();
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
            $game = $this->gameService->getGameWithDetails($token);

            // Check subscription limit: does host have subscription for this size?
            $currentCount = $game->participants->count();
            $host = $game->host;
            if ($host && !$this->subscriptionService->userCanHostOnlineGame($host, $currentCount + 1)) {
                $limit = $this->subscriptionService->onlineGameFreeLimit();
                return back()->with('error', "This game has reached the free limit of {$limit} participants. The host needs a subscription to allow more.");
            }

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
