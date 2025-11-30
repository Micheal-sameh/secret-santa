<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinSessionRequest;
use App\Models\Participant;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = auth()->user()->sessions()->with('participants')->get();

        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $session = Session::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'code' => Session::generateUniqueCode(),
            'status' => 1, // Active
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Session created successfully!');
    }

    public function show(Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load('participants');

        return view('sessions.show', compact('session'));
    }

    public function joinForm(Request $request)
    {
        $session = Session::where('code', $request->code)->first();
        if ($session == null) {
            return to_route('login')->withErrors(['code' => 'This session is no longer exsits']);
        }
        if (! $session->isActive()) {
            return to_route('login')->withErrors(['code' => 'This session is no longer active.']);
        }
        $code = $request->query('code');

        return view('sessions.join-form', compact('code'));
    }

    public function join(JoinSessionRequest $request)
    {
        $session = Session::where('code', $request->code)->first();

        // Check if session is active
        if (! $session->isActive()) {
            return back()->withErrors(['code' => 'This session is no longer active.']);
        }

        // Create participant record
        Participant::create([
            'session_id' => $session->id,
            'name' => $request->name,
        ]);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Successfully joined the session!');
    }

    public function secretSanta(Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if session is active
        if (! $session->isActive()) {
            return back()->withErrors(['session' => 'This session is no longer active.']);
        }

        // For demonstration, we'll use a mock list of participants
        // In a real app, you'd get participants from a relationship
        $participants = collect([
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
            ['id' => 3, 'name' => 'Charlie'],
            ['id' => 4, 'name' => 'Diana'],
        ]);

        if ($participants->count() < 2) {
            return back()->withErrors(['participants' => 'Need at least 2 participants for Secret Santa.']);
        }

        $assignments = $this->generateSecretSantaAssignments($participants);

        return view('sessions.secret-santa', compact('session', 'assignments'));
    }

    private function generateSecretSantaAssignments(Collection $participants): array
    {
        $participantIds = $participants->pluck('id')->toArray();
        $assignments = [];
        $availableRecipients = $participantIds;

        // Shuffle to randomize
        shuffle($participantIds);

        foreach ($participantIds as $giverId) {
            // Remove self from available recipients
            $availableRecipients = array_filter($availableRecipients, fn ($id) => $id !== $giverId);

            // If no recipients left (shouldn't happen with proper setup), reshuffle
            if (empty($availableRecipients)) {
                return $this->generateSecretSantaAssignments($participants);
            }

            // Pick a random recipient
            $recipientId = $availableRecipients[array_rand($availableRecipients)];

            // Remove the chosen recipient from available list
            $availableRecipients = array_filter($availableRecipients, fn ($id) => $id !== $recipientId);

            $giver = $participants->firstWhere('id', $giverId);
            $recipient = $participants->firstWhere('id', $recipientId);

            $assignments[] = [
                'giver' => $giver,
                'recipient' => $recipient,
            ];
        }

        return $assignments;
    }
}
