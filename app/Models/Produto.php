<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * CONCEITO: Model com relacionamentos, accessors, scopes e eventos
 *
 * @property int $id
 * @property string $nome
 * @property string $slug
 * @property string $imagem
 * @property float $media_nota
 * @property int $total_avaliacoes
 */
class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'slug', 'marca', 'descricao',
        'imagem', 'categoria_id', 'user_id',
        'total_avaliacoes', 'media_nota',
    ];

    protected function casts(): array
    {
        return [
            'media_nota'       => 'float',
            'total_avaliacoes' => 'integer',
        ];
    }

    // =========================================================
    // Relacionamentos
    // =========================================================

    /** belongsTo = "pertence a" (a FK está nesta tabela) */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    // =========================================================
    // CONCEITO: Local Scopes
    // Scopes encapsulam filtros reutilizáveis.
    // Uso: Produto::maisAvaliados()->get()
    // =========================================================

    public function scopeMaisAvaliados($query)
    {
        return $query->orderByDesc('total_avaliacoes');
    }

    public function scopeMelhorNotados($query)
    {
        return $query->where('total_avaliacoes', '>', 0)
                     ->orderByDesc('media_nota');
    }

    public function scopeRecentes($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeDaCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    public function scopeBuscar($query, string $termo)
    {
        return $query->where(function ($q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
              ->orWhere('marca', 'like', "%{$termo}%")
              ->orWhere('descricao', 'like', "%{$termo}%");
        });
    }

    // =========================================================
    // CONCEITO: Accessors & Mutators
    // Accessors: leitura computada (get...Attribute)
    // Mutators: transformação ao gravar (set...Attribute)
    // =========================================================

    public function getImagemUrlAttribute(): string
    {
        return asset('storage/' . $this->imagem);
    }

    public function getNotaArredondadaAttribute(): float
    {
        return round($this->media_nota * 2) / 2; // arredonda para .5 mais próximo
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // =========================================================
    // CONCEITO: Model Events / Boot
    // Hooks que disparam em eventos do ciclo de vida do Model.
    // =========================================================

    protected static function boot(): void
    {
        parent::boot();

        // Gera o slug automaticamente ao criar
        static::creating(function (Produto $produto) {
            if (empty($produto->slug)) {
                $produto->slug = static::gerarSlugUnico($produto->nome);
            }
        });

        // Atualiza slug se o nome mudar
        static::updating(function (Produto $produto) {
            if ($produto->isDirty('nome')) {
                $produto->slug = static::gerarSlugUnico($produto->nome, $produto->id);
            }
        });
    }

    private static function gerarSlugUnico(string $nome, ?int $ignorarId = null): string
    {
        $slug = Str::slug($nome);
        $original = $slug;
        $contador = 1;

        while (
            static::where('slug', $slug)
                  ->when($ignorarId, fn($q) => $q->where('id', '!=', $ignorarId))
                  ->exists()
        ) {
            $slug = "{$original}-{$contador}";
            $contador++;
        }

        return $slug;
    }

    /** Recalcula média e total a partir das avaliações reais */
    public function recalcularEstatisticas(): void
    {
        $stats = $this->avaliacoes()
            ->selectRaw('COUNT(*) as total, AVG(nota) as media')
            ->first();

        $this->update([
            'total_avaliacoes' => $stats->total ?? 0,
            'media_nota'       => round($stats->media ?? 0, 2),
        ]);
    }
}
