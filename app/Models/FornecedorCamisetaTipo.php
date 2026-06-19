<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FornecedorCamisetaTipo extends Model
{
    protected $table = 'fornecedor_camiseta_tipos';

    protected $fillable = [
        'fornecedor_id',
        'tipo_camiseta',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(FornecedorCamiseta::class, 'fornecedor_id');
    }

    public function tamanhos(): HasMany
    {
        return $this->hasMany(FornecedorCamisetaTamanho::class, 'fornecedor_camiseta_tipo_id');
    }
}
