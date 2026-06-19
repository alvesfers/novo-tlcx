<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirigenteFuncao extends Model
{
    protected $table = 'dirigente_funcoes';

    protected $fillable = [
        'dirigente_id',
        'funcao_dirigente_id',
    ];

    public $timestamps = true;

    public function dirigente(): BelongsTo
    {
        return $this->belongsTo(Dirigente::class, 'dirigente_id');
    }

    public function funcao(): BelongsTo
    {
        return $this->belongsTo(FuncaoDirigente::class, 'funcao_dirigente_id');
    }
}
