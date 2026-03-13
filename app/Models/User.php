<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * CONCEITO: Eloquent Model
 * -------------------------
 * Cada Model representa uma tabela. O Eloquent usa convenções:
 * - User       → tabela "users"
 * - Produto    → tabela "produtos"
 * - Avaliacao  → tabela "avaliacoes"
 *
 * $fillable protege contra mass-assignment (vulnerabilidade onde
 * um atacante enviaria campos extras como "admin=1").
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',    // auto-hash ao salvar
        ];
    }

    // =========================================================
    // CONCEITO: Relacionamentos Eloquent
    // hasMany = "tem muitos" (chave estrangeira na outra tabela)
    // =========================================================

    /** Um usuário cadastrou muitos produtos */
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    /** Um usuário escreveu muitas avaliações */
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    /** Avaliações que o usuário marcou como úteis */
    public function votosUteis()
    {
        return $this->hasMany(VotoUtilidade::class);
    }

    // =========================================================
    // Accessors — propriedades computadas
    // =========================================================

    /** URL do avatar ou placeholder com iniciais */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Gera URL de avatar com iniciais (serviço externo)
        $iniciais = collect(explode(' ', $this->name))
            ->map(fn($p) => strtoupper($p[0]))
            ->take(2)
            ->join('');

        return "https://ui-avatars.com/api/?name={$iniciais}&background=6366f1&color=fff&size=128";
    }

    /** Total de avaliações úteis recebidas em todas as reviews */
    public function getTotalVotosRecebidesAttribute(): int
    {
        return $this->avaliacoes()->sum('votos_uteis');
    }
}
