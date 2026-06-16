<?php

namespace App\Policies;

use App\Models\Candidat;
use App\Models\User;

class CandidatPolicy
{
    public function view(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->user_id;
    }

    public function delete(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->user_id;
    }
}
