<?php

namespace App\Repositories;

use App\Models\Assignment;

class AssignmentRepository
{
    public function __construct(protected Assignment $model) {}

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function getBySession(int $sessionId)
    {
        return $this->model->where('session_id', $sessionId)->get();
    }

    public function getBySessionWithRelations(int $sessionId)
    {
        return $this->model->where('session_id', $sessionId)->with('recipient')->get();
    }

    public function findByGiver(int $sessionId, int $giverId)
    {
        return $this->model->where('session_id', $sessionId)
            ->where('giver_participant_id', $giverId)
            ->with('recipient')
            ->first();
    }

    public function delete(Assignment $assignment)
    {
        return $assignment->delete();
    }
}
