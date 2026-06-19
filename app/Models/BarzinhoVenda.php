<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarzinhoVenda extends Model
{
    protected $table = 'barzinho_vendas';

    protected $fillable = [
        'barzinho_id',
        'evento_participante_id',
        'forma_pagamento_id',
        'tipo_pagamento',
        'descricao',
        'subtotal',
        'desconto',
        'taxa_pagamento',
        'total',
        'status_pagamento',
        'data_pagamento',
        'observacao',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'desconto' => 'decimal:2',
        'taxa_pagamento' => 'decimal:2',
        'total' => 'decimal:2',
        'data_pagamento' => 'datetime',
    ];

    public function barzinho(): BelongsTo
    {
        return $this->belongsTo(Barzinho::class, 'barzinho_id');
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(EventoParticipante::class, 'evento_participante_id');
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(BarzinhoVendaItem::class, 'venda_id');
    }
}
