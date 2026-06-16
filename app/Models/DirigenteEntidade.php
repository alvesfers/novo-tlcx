<?php

namespace App\Models;

use App\Enums\CargoDirigente;
use App\Enums\TipoVinculo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirigenteEntidade extends Pivot
{
    use SoftDeletes;

    public $incrementing = true;
    protected $table = 'dirigente_entidades';

    protected $fillable = [
        'dirigente_id',
        'entidade_id',
        'tipo_vinculo',
        'cargo',
        'papel',
        'data_inicio',
        'data_fim',
        'ativo',
    ];

    protected $casts = [
        'tipo_vinculo' => TipoVinculo::class,
        'cargo' => CargoDirigente::class,
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];

    public function dirigente(): BelongsTo
    {
        return $this->belongsTo(Dirigente::class, 'dirigente_id');
    }

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    public function isPrincipal(): bool
    {
        return $this->tipo_vinculo === TipoVinculo::Principal;
    }

    public function isCoordenacao(): bool
    {
        return $this->tipo_vinculo === TipoVinculo::Coordenacao;
    }

    public function isAdicional(): bool
    {
        return $this->tipo_vinculo === TipoVinculo::Adicional;
    }
}
