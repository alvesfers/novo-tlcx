<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    protected $table = 'formas_pagamento';

    protected $fillable = [
        'entidade_id',
        'nome',
        'tipo',
        'taxa_credito',
        'taxa_debito',
        'taxa_pix',
        'ativa',
        'observacao',
    ];

    protected $casts = [
        'taxa_credito' => 'decimal:2',
        'taxa_debito' => 'decimal:2',
        'taxa_pix' => 'decimal:2',
        'ativa' => 'boolean',
    ];

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(BarzinhoVenda::class, 'forma_pagamento_id');
    }
}
