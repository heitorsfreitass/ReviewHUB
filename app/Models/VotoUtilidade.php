<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotoUtilidade extends Model
{
    protected $table = 'votos_utilidade';

    protected $fillable = ['avaliacao_id', 'user_id'];

    public function avaliacao()
    {
        return $this->belongsTo(Avaliacao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
