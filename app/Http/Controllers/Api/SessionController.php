<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateSessionRequest;
use App\Http\Requests\JoinSessionRequest;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\SessionResource;
use App\Models\Participant;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\Request;

class SessionController extends BaseController
{
    public function __construct(protected SessionService $sessionService) {}

    public function index()
    {
        $sessions = $this->sessionService->getUserSessions(auth()->id());

        return $this->apiResponse(SessionResource::collection($sessions), 'Sessions retrieved successfully');
    }

    public function store(CreateSessionRequest $request)
    {
        $session = $this->sessionService->createSession([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'expires_at' => $request->expires_at,
        ]);

        return $this->apiResponse(new SessionResource($session), 'Session created successfully', 201);
    }

    public function show(Request $request, Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== $request->user()->id) {
            return $this->apiErrorResponse('Unauthorized', 403);
        }

        $session->load('participants');

        return $this->apiResponse(new SessionResource($session));
    }

    public function join(JoinSessionRequest $request)
    {
        $session = $this->sessionService->getSessionByCode($request->code);

        if (! $session) {
            return $this->apiErrorResponse('Session not found', 404);
        }

        // Check if session is active
        if (! $session->isActive()) {
            return $this->apiErrorResponse('Session is no longer active', 400);
        }

        // Create participant record
        $participant = $this->sessionService->joinSession([
            'session_id' => $session->id,
            'name' => $request->name,
        ]);

        return $this->apiResponse(new ParticipantResource($participant), 'Joined session successfully');
    }

    public function destroyParticipant(Request $request, Session $session, Participant $participant)
    {
        // Ensure user owns this session
        if ($session->user_id !== $request->user()->id) {
            return $this->apiErrorResponse('Unauthorized', 403);
        }

        // Ensure participant belongs to this session
        if ($participant->session_id !== $session->id) {
            return $this->apiErrorResponse('Participant not found in this session', 404);
        }

        $this->sessionService->removeParticipant($participant);

        return $this->apiResponse(null, 'Participant removed successfully');
    }

    public function secretSanta(Request $request, Session $session)
    {
        // Ensure user owns this session
        if ($session->user_id !== $request->user()->id) {
            return $this->apiErrorResponse('Unauthorized', 403);
        }

        // Check if session is active
        if (! $session->isActive()) {
            $assignments = $this->sessionService->getAssignmentsApi($session);

            return $this->apiResponse(AssignmentResource::collection($assignments), 'Assignments retrieved',
                additional_data: [
                    'session' => new SessionResource($session),
                ]);
        }
        $session->load('participants');
        $participants = $session->participants;

        if ($participants->count() < 2) {
            return $this->apiErrorResponse('Need at least 2 participants for Secret Santa', 400);
        }

        $assignments = $this->sessionService->generateAssignments($session, $participants);
        if (empty($assignments)) {
            return $this->apiErrorResponse('Failed to generate valid assignments', 500);
        }

        return $this->apiResponse(AssignmentResource::collection($assignments), 'Assignments generated successfully',
            additional_data: [
                'session' => new SessionResource($session),
            ]);
    }

    public function showAssignment(Request $request, Session $session)
    {
        $request->validate([
            'participant_name' => 'required|string|max:255',
        ]);

        // Find the assignment for this participant
        $assignment = $this->sessionService->getAssignmentForParticipant($session, $request->participant_name);

        if (! $assignment) {
            return $this->apiErrorResponse('No assignment found for this participant', 404);
        }

        return $this->apiResponse(new SessionResource($session), 'Assignment retrieved');
    }
}
