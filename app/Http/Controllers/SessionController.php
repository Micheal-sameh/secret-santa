<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinSessionRequest;
use App\Models\Assignment;
use App\Models\Participant;
use App\Models\Session;
use Illuminate\Http\Request;

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
            return to_route('sessions.check-assignment', $session);
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

    public function destroyParticipant(Session $session, Participant $participant)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        // Ensure participant belongs to this session
        if ($participant->session_id !== $session->id) {
            abort(404);
        }

        $participant->delete();

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Participant removed successfully!');
    }

    public function secretSanta(Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load('participants');

        // Check if session is active
        if (! $session->isActive()) {
            $assignments = Assignment::where('session_id', $session->id)
                ->get()
                ->map(function ($assignment) use ($session) {
                    $giver = $session->participants->find($assignment->giver_participant_id);
                    $recipient = $session->participants->find($assignment->recipient_participant_id);

                    return [
                        'giver' => $giver,
                        'recipient' => $recipient,
                    ];
                })->toArray();

            return view('sessions.secret-santa', compact('session', 'assignments'));
        }
        $participants = $session->participants;

        if ($participants->count() < 2) {
            return back()->withErrors(['participants' => 'Need at least 2 participants for Secret Santa.']);
        }

        $assignments = $this->generateSecretSantaAssignments($participants, $session);
        if (empty($assignments)) {
            return back()->withErrors(['assignments' => 'Failed to generate valid assignments. Please try again.']);
        }
        $session->update(['status' => 2]); // Update session status to indicate assignments generated
        // Save assignments to database
        foreach ($assignments as $assignment) {
            Assignment::create([
                'session_id' => $session->id,
                'giver_participant_id' => $assignment['giver']['id'],
                'recipient_participant_id' => $assignment['recipient']['id'],
            ]);
        }

        return view('sessions.secret-santa', compact('session', 'assignments'));
    }

    public function checkAssignment(Session $session)
    {
        // Check if session is active
        // if (! $session->isActive()) {
        //     return to_route('sessions.check-assignment', ['session' => $session])->withErrors(['code' => 'This session is no longer active.']);
        // }

        return view('sessions.check-assignment', compact('session'));
    }

    public function showAssignment(Request $request, Session $session)
    {
        // Check if session is active
        // if (! $session->isActive()) {
        //     return back()->withErrors(['session' => 'This session is no longer active.']);
        // }

        $request->validate([
            'participant_name' => 'required|string|max:255',
        ]);

        // Find the participant by name
        $participant = $session->participants()->where('name', $request->participant_name)->first();

        if (! $participant) {
            return back()->withErrors(['participant_name' => 'Participant not found. Please check your name spelling.']);
        }

        // Find the assignment for this participant
        $assignment = Assignment::where('session_id', $session->id)
            ->where('giver_participant_id', $participant->id)
            ->with('recipient')
            ->first();

        if (! $assignment) {
            return back()->withErrors(['participant_name' => 'No assignment found for this participant. Assignments may not have been generated yet.']);
        }

        return view('sessions.show-assignment', compact('session', 'participant', 'assignment'));
    }

    private function generateSecretSantaAssignments(\Illuminate\Support\Collection $participants, $session): array
    {
        // 1. Extract IDs for Givers (unshuffled) and Recipients (to be shuffled)
        $giverIds = $participants->pluck('id')->toArray();
        $recipientIds = $giverIds;

        $assignments = [];
        $maxAttempts = count($giverIds) * 2; // Limit attempts to prevent infinite loops

        // We must ensure the number of givers and recipients is the same and > 1
        if (count($giverIds) < 2) {
            // Handle case for 0 or 1 participant
            return [];
        }

        // Use a loop that attempts the assignment until a valid derangement is found
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Shuffle the recipient list for each attempt
            shuffle($recipientIds);
            $isValid = true;
            $tempAssignments = [];

            // 2. Iterate through the givers and assign the shuffled recipient
            for ($i = 0; $i < count($giverIds); $i++) {
                $giverId = $giverIds[$i];
                $recipientId = $recipientIds[$i];

                // 3. Check for the "self-gifting" constraint
                if ($giverId == $recipientId) {
                    $isValid = false;
                    break; // Invalid assignment, try shuffling again
                }

                // Store the ID pair temporarily
                $tempAssignments[] = [
                    'giver_id' => $giverId,
                    'recipient_id' => $recipientId,
                ];
            }

            // 4. If a valid assignment (derangement) is found, finalize the assignment objects and return
            if ($isValid) {
                foreach ($tempAssignments as $pair) {
                    $giver = $participants->firstWhere('id', $pair['giver_id']);
                    $recipient = $participants->firstWhere('id', $pair['recipient_id']);

                    $assignments[] = [
                        'giver' => $giver,
                        'recipient' => $recipient,
                    ];
                }

                return $assignments;
            }
        }

        return [];
    }
}
