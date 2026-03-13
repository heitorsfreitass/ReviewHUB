<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'icone', 'slug'];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function getRouteKeyName(): string
    {
        // slug em vez de ID
        // /categorias/eletronicos em vez de /categorias/3
        return 'slug';
    }
}
