<?php

namespace App\Policies;

use App\Models\Produto;
use App\Models\User;

/**
 * CONCEITO: Policies
 * ------------------
 * Centralizam as regras de autorização para um Model.
 * Chamadas via $this->authorize('update', $produto) no Controller
 * ou @can('update', $produto) no Blade.
 *
 * O Laravel resolve automaticamente qual Policy usar
 * baseado no tipo do segundo argumento.
 */
class ProdutoPolicy
{
    /** Quem pode editar: o próprio criador */
    public function update(User $user, Produto $produto): bool
    {
        return $user->id === $produto->user_id;
    }

    /** Quem pode apagar: o próprio criador */
    public function delete(User $user, Produto $produto): bool
    {
        return $user->id === $produto->user_id;
    }
}
