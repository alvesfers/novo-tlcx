<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoTipoCamiseta extends Model
{
    protected $table = 'evento_tipos_camiseta';

    protected $fillable = [
        'evento_id',
        'fornecedor_id',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(FornecedorCamiseta::class, 'fornecedor_id');
    }
}
