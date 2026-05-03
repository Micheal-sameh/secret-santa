<?php

namespace App\Http\Controllers;

use App\Models\InPersonGame;
use App\Models\InPersonParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InPersonGameController extends Controller
{
    public function create(): View
    {
        return view('inperson.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'price_limit'     => ['nullable', 'numeric', 'min:0'],
            'participants'    => ['required', 'array', 'min:2'],
            'participants.*'  => ['required', 'string', 'max:100', 'distinct'],
        ]);

        $game = InPersonGame::create([
            'name'         => $request->name,
            'price_limit'  => $request->price_limit,
            'device_token' => InPersonGame::generateToken(),
            'assigned'     => false,
        ]);

        $names = array_values(array_filter(array_map('trim', $request->participants)));

        // Create participants
        foreach ($names as $order => $name) {
            InPersonParticipant::create([
                'game_id'      => $game->id,
                'name'         => $name,
                'reveal_order' => $order + 1,
            ]);
        }

        // Run assignment immediately
        $participants = $game->participants()->get();
        $ids          = $participants->pluck('id')->toArray();
        $assignedTos  = $this->satolloDerangement($participants->pluck('name')->toArray());

        foreach ($participants as $idx => $participant) {
            $participant->update(['assigned_to' => $assignedTos[$idx]]);
        }

        $game->update(['assigned' => true]);

        return redirect()
            ->route('inperson.show', $game->device_token)
            ->with('success', 'Game created! Pass the device around for everyone to see their assignment.');
    }

    public function show(string $token): View
    {
        $game = InPersonGame::where('device_token', $token)
            ->with('participants')
            ->firstOrFail();

        return view('inperson.show', compact('game'));
    }

    public function reveal(string $token, int $participantId): View
    {
        $game        = InPersonGame::where('device_token', $token)->firstOrFail();
        $participant = InPersonParticipant::where('game_id', $game->id)
            ->where('id', $participantId)
            ->firstOrFail();

        $participant->update(['revealed' => true]);

        return view('inperson.reveal', compact('game', 'participant'));
    }

    /**
     * Sattolo's cycle algorithm on an array of names — guarantees no self-assignments.
     */
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
