<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarzinhoProduto extends Model
{
    use SoftDeletes;

    protected $table = 'barzinho_produtos';

    protected $fillable = [
        'barzinho_id',
        'nome',
        'descricao',
        'preco_custo',
        'preco_venda',
        'quantidade',
        'ativo',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function barzinho(): BelongsTo
    {
        return $this->belongsTo(Barzinho::class, 'barzinho_id');
    }

    public function consignacoes(): HasMany
    {
        return $this->hasMany(BarzinhoProdutoConsignado::class, 'barzinho_produto_id');
    }

    public function consignados(): HasMany
    {
        return $this->hasMany(BarzinhoProdutoConsignado::class, 'barzinho_produto_id');
    }
}
