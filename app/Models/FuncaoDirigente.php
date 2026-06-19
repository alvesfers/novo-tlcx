<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FuncaoDirigente extends Model
{
    protected $table = 'funcoes_dirigentes';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function dirigentes(): BelongsToMany
    {
        return $this->belongsToMany(Dirigente::class, 'dirigente_funcoes', 'funcao_dirigente_id', 'dirigente_id');
    }
}
