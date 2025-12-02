<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\Session;
use App\Repositories\AssignmentRepository;
use App\Repositories\ParticipantRepository;
use App\Repositories\SessionRepository;
use Illuminate\Support\Collection;

class SessionService
{
    public function __construct(
        protected SessionRepository $sessionRepository,
        protected ParticipantRepository $participantRepository,
        protected AssignmentRepository $assignmentRepository
    ) {}

    public function getUserSessions(int $userId)
    {
        return $this->sessionRepository->getByUserId($userId);
    }

    public function createSession(array $data)
    {
        $data['code'] = Session::generateUniqueCode();
        $data['status'] = 1; // Active

        return $this->sessionRepository->create($data);
    }

    public function getSessionById(int $id)
    {
        return $this->sessionRepository->findById($id);
    }

    public function getSessionByCode(string $code)
    {
        return $this->sessionRepository->findByCode($code);
    }

    public function updateSession(Session $session, array $data)
    {
        return $this->sessionRepository->update($session, $data);
    }

    public function joinSession(array $data)
    {
        return $this->participantRepository->create($data);
    }

    public function removeParticipant(Participant $participant)
    {
        return $this->participantRepository->delete($participant);
    }

    public function generateAssignments(Session $session, Collection $participants)
    {
        $assignments = $this->generateSecretSantaAssignments($participants, $session);
        if (empty($assignments)) {
            return false;
        }

        $this->sessionRepository->update($session, ['status' => 2]);

        foreach ($assignments as $assignment) {
            $this->assignmentRepository->create([
                'session_id' => $session->id,
                'giver_participant_id' => $assignment['giver']['id'],
                'recipient_participant_id' => $assignment['recipient']['id'],
            ]);
        }

        return $assignments;
    }

    public function getAssignments(Session $session)
    {
        $assignments = $this->assignmentRepository->getBySession($session->id);

        return $assignments->map(function ($assignment) use ($session) {
            $giver = $session->participants->find($assignment->giver_participant_id);
            $recipient = $session->participants->find($assignment->recipient_participant_id);

            return [
                'giver' => $giver,
                'recipient' => $recipient,
            ];
        })->toArray();
    }

    public function getAssignmentForParticipant(Session $session, string $participantName)
    {
        $participant = $this->participantRepository->findBySessionAndName($session->id, $participantName);
        if (! $participant) {
            return null;
        }

        return $this->assignmentRepository->findByGiver($session->id, $participant->id);
    }

    private function generateSecretSantaAssignments(Collection $participants, Session $session): array
    {
        $giverIds = $participants->pluck('id')->toArray();
        $recipientIds = $giverIds;

        $assignments = [];
        $maxAttempts = count($giverIds) * 2;

        if (count($giverIds) < 2) {
            return [];
        }

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            shuffle($recipientIds);
            $isValid = true;
            $tempAssignments = [];

            for ($i = 0; $i < count($giverIds); $i++) {
                $giverId = $giverIds[$i];
                $recipientId = $recipientIds[$i];

                if ($giverId == $recipientId) {
                    $isValid = false;
                    break;
                }

                $tempAssignments[] = [
                    'giver_id' => $giverId,
                    'recipient_id' => $recipientId,
                ];
            }

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
