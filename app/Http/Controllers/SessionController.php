<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinSessionRequest;
use App\Models\Participant;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SessionController extends Controller
{
    public function __construct(protected SessionService $sessionService) {}

    public function index()
    {
        $sessions = $this->sessionService->getUserSessions(auth()->id());

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

        $session = $this->sessionService->createSession([
            'user_id' => auth()->id(),
            'name' => $request->name,
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
        $session = $this->sessionService->getSessionByCode($request->code);
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
        $session = $this->sessionService->getSessionByCode($request->code);

        // Check if session is active
        if (! $session->isActive()) {
            return back()->withErrors(['code' => 'This session is no longer active.']);
        }

        // Create participant record
        $this->sessionService->joinSession([
            'session_id' => $session->id,
            'name' => $request->name,
        ]);

        return (auth()->check() && auth()->id() == $session->user_id) ? redirect()->route('sessions.show', $session)
            ->with('success', 'Successfully joined the session!')
            : to_route('sessions.check-assignment', $session)->with('success', 'Successfully joined the session!');
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

        $this->sessionService->removeParticipant($participant);

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
            $assignments = $this->sessionService->getAssignments($session);

            return view('sessions.secret-santa', compact('session', 'assignments'));
        }
        $participants = $session->participants;

        if ($participants->count() < 2) {
            return back()->withErrors(['participants' => 'Need at least 2 participants for Secret Santa.']);
        }

        $assignments = $this->sessionService->generateAssignments($session, $participants);
        if (empty($assignments)) {
            return back()->withErrors(['assignments' => 'Failed to generate valid assignments. Please try again.']);
        }

        return view('sessions.secret-santa', compact('session', 'assignments'));
    }

    public function exportSecretSantaPdf(Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load('participants');

        // Check if session is active
        if ($session->isActive()) {
            abort(403, 'Assignments not yet generated.');
        }

        $assignments = $this->sessionService->getAssignments($session);

        // Generate PDF
        $mpdf = new Mpdf;
        $mpdf->SetTitle('Secret Santa Assignments - '.$session->name);

        $html = view('sessions.secret-santa-pdf', compact('session', 'assignments'))->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('secret-santa-'.$session->name.'.pdf', 'D');
    }

    public function generateQrCode(Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $qrCode = QrCode::size(200)->generate($session->shareable_link);

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
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

        // Find the assignment for this participant
        $assignment = $this->sessionService->getAssignmentForParticipant($session, $request->participant_name);

        if (! $assignment) {
            return back()->withErrors(['participant_name' => 'No assignment found for this participant. Assignments may not have been generated yet.']);
        }

        $participant = $assignment->giver;

        return view('sessions.show-assignment', compact('session', 'participant', 'assignment'));
    }
}
