<?php

namespace App\Models;

use App\Enums\NivelHabilidade;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DirigenteHabilidade extends Pivot
{
    protected $table = 'dirigente_habilidades';

    protected $fillable = [
        'dirigente_id',
        'habilidade_id',
        'nivel',
        'observacao',
    ];

    protected $casts = [
        // 'nivel' é armazenado como string, não cast automático
    ];
}
