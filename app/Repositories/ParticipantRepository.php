<?php

namespace App\Repositories;

use App\Models\Participant;

class ParticipantRepository
{
    public function __construct(protected Participant $model) {}

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findBySessionAndName(int $sessionId, string $name)
    {
        return $this->model->where('session_id', $sessionId)->where('name', $name)->first();
    }

    public function delete(Participant $participant)
    {
        return $participant->delete();
    }

    public function getBySession(int $sessionId)
    {
        return $this->model->where('session_id', $sessionId)->get();
    }
}
