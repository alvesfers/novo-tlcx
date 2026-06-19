<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarzinhoCombo extends Model
{
    protected $table = 'barzinho_combos';

    protected $fillable = [
        'barzinho_id',
        'nome',
        'descricao',
        'preco_venda',
        'ativo',
    ];

    protected $casts = [
        'preco_venda' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function barzinho(): BelongsTo
    {
        return $this->belongsTo(Barzinho::class, 'barzinho_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(BarzinhoCombItem::class, 'combo_id');
    }
}
