<?php

namespace App\Http\Controllers;

use App\DTOs\CreateInPersonGameData;
use App\Http\Requests\StoreInPersonGameRequest;
use App\Services\InPersonGameService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InPersonGameController extends Controller
{
    public function __construct(
        private InPersonGameService  $inPersonGameService,
        private SubscriptionService  $subscriptionService,
    ) {}

    public function create(): View
    {
        $freeLimit             = $this->subscriptionService->inPersonGameFreeLimit();
        $loginRequiredAfter    = $this->subscriptionService->inPersonLoginRequiredAfter();
        $subscriptionsEnabled  = $this->subscriptionService->subscriptionsEnabled();

        return view('inperson.create', compact('freeLimit', 'loginRequiredAfter', 'subscriptionsEnabled'));
    }

    public function store(StoreInPersonGameRequest $request): RedirectResponse
    {
        $dto  = CreateInPersonGameData::fromRequest($request);
        $user = Auth::user();

        if (!$this->subscriptionService->canCreateInPersonGame($user, count($dto->participantNames))) {
            $limit = $this->subscriptionService->inPersonGameFreeLimit();
            return back()
                ->withInput()
                ->with('error', "In-person games with more than {$limit} participants require a subscription.");
        }

        $game = $this->inPersonGameService->createGame($dto);

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
            $game        = $this->inPersonGameService->getGameWithParticipants($token);

            return view('inperson.reveal', compact('game', 'participant'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
