<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoInscricaoOpcaoCamiseta extends Model
{
    protected $table = 'evento_inscricao_opcao_camisetas';

    protected $fillable = [
        'opcao_id',
        'tipo_camiseta',
        'valor_adicional',
        'ativo',
    ];

    protected $casts = [
        'valor_adicional' => 'decimal:2',
        'ativo'           => 'boolean',
    ];

    public function opcao(): BelongsTo
    {
        return $this->belongsTo(EventoInscricaoOpcao::class, 'opcao_id');
    }

    public function tipoLabel(): string
    {
        return match($this->tipo_camiseta) {
            'normal'    => 'Normal',
            'plus'      => 'Plus Size',
            'babylook'  => 'Babylook',
            'oversized' => 'Oversized',
            'infantil'  => 'Infantil',
            default     => $this->tipo_camiseta,
        };
    }
}
