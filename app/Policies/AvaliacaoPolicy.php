<?php

namespace App\Policies;

use App\Models\Avaliacao;
use App\Models\User;

class AvaliacaoPolicy
{
    public function update(User $user, Avaliacao $avaliacao): bool
    {
        return $user->id === $avaliacao->user_id;
    }

    public function delete(User $user, Avaliacao $avaliacao): bool
    {
        return $user->id === $avaliacao->user_id;
    }
}
