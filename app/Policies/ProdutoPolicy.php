<?php

namespace App\Policies;

use App\Models\Produto;
use App\Models\User;

/**
 * O Laravel resolve automaticamente qual Policy usar
 * baseado no tipo do segundo argumento.
 */
class ProdutoPolicy
{
    /** só o criador pode editar */
    public function update(User $user, Produto $produto): bool
    {
        return $user->id === $produto->user_id;
    }

    /** só o criador pode apagar */
    public function delete(User $user, Produto $produto): bool
    {
        return $user->id === $produto->user_id;
    }
}
