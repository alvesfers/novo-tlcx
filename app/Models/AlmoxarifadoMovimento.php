<?php

namespace App\Models;

use App\Enums\TipoMovimentoEstoque;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlmoxarifadoMovimento extends Model
{
    use SoftDeletes;

    protected $table = 'almoxarifado_movimentos';

    protected $fillable = [
        'entidade_id',
        'almoxarifado_item_id',
        'evento_id',
        'tipo_movimento',
        'quantidade',
        'quantidade_anterior',
        'quantidade_posterior',
        'descricao',
        'responsavel_user_id',
        'data_movimento',
        'observacao',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'quantidade_anterior' => 'decimal:2',
        'quantidade_posterior' => 'decimal:2',
        'tipo_movimento' => TipoMovimentoEstoque::class,
        'data_movimento' => 'datetime',
    ];

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(AlmoxarifadoItem::class, 'almoxarifado_item_id');
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_user_id');
    }
}
