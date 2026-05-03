<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $createdGames = Game::where('host_id', $user->id)
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();

        $joinedGames = $user->participatedGames()
            ->where('host_id', '!=', $user->id)
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();

        $assignedGames = Game::whereHas('assignments', function ($q) use ($user) {
            $q->where('giver_id', $user->id);
        })
            ->withCount('participants')
            ->orderBy('meeting_date')
            ->orderBy('end_date')
            ->get();

        return view('games.index', compact('createdGames', 'joinedGames', 'assignedGames'));
    }

    public function create(): View
    {
        return view('games.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'price_limit'  => ['nullable', 'numeric', 'min:0'],
            'end_date'     => ['required', 'date', 'after:today'],
            'meeting_date' => ['required', 'date', 'after:end_date'],
        ]);

        $game = Game::create([
            ...$data,
            'host_id'    => Auth::id(),
            'join_token' => Game::generateToken(),
        ]);

        // Host auto-joins
        $game->participants()->attach(Auth::id());

        return redirect()
            ->route('games.show', $game->join_token)
            ->with('success', 'Game created! Share the link below so others can join.');
    }

    public function show(string $token): View
    {
        $game = Game::where('join_token', $token)
            ->with(['host', 'participants'])
            ->firstOrFail();

        $user         = Auth::user();
        $isParticipant = $user && $game->participants->contains('id', $user->id);
        $isHost        = $user && $game->host_id === $user->id;
        $myAssignment  = null;

        if ($isParticipant && $game->isAssigned()) {
            $myAssignment = Assignment::where('game_id', $game->id)
                ->where('giver_id', $user->id)
                ->with('receiver')
                ->first();
        }

        return view('games.show', compact('game', 'isParticipant', 'isHost', 'myAssignment'));
    }

    public function join(Request $request, string $token): RedirectResponse
    {
        $game = Game::where('join_token', $token)->firstOrFail();

        if ($game->isExpired()) {
            return back()->with('error', 'The registration deadline for this game has passed.');
        }

        if ($game->isAssigned()) {
            return back()->with('error', 'Assignments have already been made. You cannot join now.');
        }

        $game->participants()->syncWithoutDetaching([Auth::id()]);

        return redirect()
            ->route('games.show', $token)
            ->with('success', "You've joined the game! 🎅");
    }

    public function assign(int $id): RedirectResponse
    {
        $game = Game::findOrFail($id);

        if ($game->host_id !== Auth::id()) {
            abort(403, 'Only the host can assign participants.');
        }

        if ($game->isAssigned()) {
            return back()->with('error', 'Assignments have already been made for this game.');
        }

        if (!$game->isExpired()) {
            return back()->with('error', 'You can only assign after the registration deadline has passed.');
        }

        $participantIds = $game->participants()->pluck('users.id')->toArray();

        if (count($participantIds) < 2) {
            return back()->with('error', 'You need at least 2 participants to run assignments.');
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

        return redirect()
            ->route('games.show', $game->join_token)
            ->with('success', 'Assignments are done! Everyone can now see who they\'re buying for. 🎁');
    }

    /**
     * Sattolo's cycle algorithm — guarantees a single-cycle derangement (no self-assignments).
     */
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
