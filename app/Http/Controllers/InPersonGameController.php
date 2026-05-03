<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInPersonGameRequest;
use App\Services\InPersonGameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InPersonGameController extends Controller
{
    public function __construct(
        private InPersonGameService $inPersonGameService
    ) {}

    public function create(): View
    {
        return view('inperson.create');
    }

    public function store(StoreInPersonGameRequest $request): RedirectResponse
    {
        $game = $this->inPersonGameService->createGame($request->validated());

        return redirect()
            ->route('inperson.show', $game->device_token)
            ->with('success', 'Game created! Pass the device around for everyone to see their assignment.');
    }

    public function show(string $token): View
    {
        $game = $this->inPersonGameService->getGameWithParticipants($token);

        return view('inperson.show', compact('game'));
    }

    public function reveal(string $token, int $participantId): View
    {
        try {
            $participant = $this->inPersonGameService->revealParticipant($token, $participantId);
            $game = $this->inPersonGameService->getGameWithParticipants($token);

            return view('inperson.reveal', compact('game', 'participant'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
