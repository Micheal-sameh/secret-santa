<?php

namespace App\Repositories;

use App\Models\Session;

class SessionRepository
{
    public function __construct(protected Session $model) {}

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->withCount('participants')->with('participants')->get();
    }

    public function update(Session $session, array $data)
    {
        $session->update($data);

        return $session;
    }

    public function delete(Session $session)
    {
        return $session->delete();
    }
}
