<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
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

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    public function votosUteis()
    {
        return $this->hasMany(VotoUtilidade::class);
    }

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
