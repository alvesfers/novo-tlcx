<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarzinhoProdutoConsignado extends Model
{
    protected $table = 'barzinho_produtos_consignados';

    protected $fillable = [
        'barzinho_produto_id',
        'almoxarifado_item_id',
        'tipo_comissao',
        'comissao_valor',
        'comissao_vai_para_entidade_id',
        'preco_custo_original',
        'data_consignacao',
        'ativa',
    ];

    protected $casts = [
        'comissao_valor' => 'decimal:2',
        'preco_custo_original' => 'decimal:2',
        'data_consignacao' => 'datetime',
        'ativa' => 'boolean',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(BarzinhoProduto::class, 'barzinho_produto_id');
    }

    public function almoxarifadoItem(): BelongsTo
    {
        return $this->belongsTo(AlmoxarifadoItem::class, 'almoxarifado_item_id');
    }

    public function entidadeComissao(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'comissao_vai_para_entidade_id');
    }
}
