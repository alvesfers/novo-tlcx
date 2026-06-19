<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarzinhoVendaItem extends Model
{
    protected $table = 'barzinho_venda_itens';

    protected $fillable = [
        'venda_id',
        'tipo_item',
        'produto_id',
        'combo_id',
        'quantidade',
        'preco_unitario',
        'subtotal',
        'consignado',
        'barzinho_produto_consignado_id',
        'comissao_tipo',
        'comissao_valor',
        'comissao_vai_para_entidade_id',
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'consignado' => 'boolean',
        'comissao_valor' => 'decimal:2',
    ];

    public function venda(): BelongsTo
    {
        return $this->belongsTo(BarzinhoVenda::class, 'venda_id');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(BarzinhoProduto::class, 'produto_id');
    }

    public function combo(): BelongsTo
    {
        return $this->belongsTo(BarzinhoCombo::class, 'combo_id');
    }

    public function consignacao(): BelongsTo
    {
        return $this->belongsTo(BarzinhoProdutoConsignado::class, 'barzinho_produto_consignado_id');
    }

    public function entidadeComissao(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'comissao_vai_para_entidade_id');
    }
}
