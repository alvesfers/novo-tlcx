<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FornecedorCamisetaTamanho extends Model
{
    protected $table = 'fornecedor_camiseta_tamanhos';

    protected $fillable = [
        'fornecedor_camiseta_tipo_id',
        'tamanho',
        'medidas',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'medidas' => 'json',
        'ativo' => 'boolean',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(FornecedorCamisetaTipo::class, 'fornecedor_camiseta_tipo_id');
    }
}
