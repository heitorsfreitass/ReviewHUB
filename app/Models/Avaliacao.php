<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'produto_id',
        'user_id',
        'nota',
        'titulo',
        'conteudo',
        'preco_pago',
        'loja',
        'url_loja',
        'recomenda',
        'votos_uteis',
        'imagens',
    ];

    protected function casts(): array
    {
        return [
            'nota'       => 'integer',
            'preco_pago' => 'decimal:2',
            'recomenda'  => 'boolean',
            'votos_uteis' => 'integer',
            'imagens'    => 'array',
        ];
    }

    // Relacionamentos
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votosUtilidade()
    {
        return $this->hasMany(VotoUtilidade::class);
    }

    public function getEstrelasAttribute(): array
    {
        return array_map(fn($i) => $i <= $this->nota, range(1, 5));
    }

    public function getNotaTextoAttribute(): string
    {
        return match ($this->nota) {
            1 => 'Péssimo',
            2 => 'Ruim',
            3 => 'Regular',
            4 => 'Bom',
            5 => 'Excelente',
            default => '',
        };
    }

    public function getPrecoPagoFormatadoAttribute(): ?string
    {
        if (!$this->preco_pago) return null;
        return 'R$ ' . number_format($this->preco_pago, 2, ',', '.');
    }

    // recalcula estatísticas do produto
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function (Avaliacao $av) {
            $av->produto->recalcularEstatisticas();
        });

        static::deleted(function (Avaliacao $av) {
            $av->produto->recalcularEstatisticas();
        });
    }

    public function getImagensUrlsAttribute(): array
    {
        if (!$this->imagens) return [];
        return array_map(fn($path) => asset('storage/' . $path), $this->imagens);
    }
}
