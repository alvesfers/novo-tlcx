<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlmoxarifadoTransferencia extends Model
{
    use SoftDeletes;

    protected $table = 'almoxarifado_transferencias';

    protected $fillable = [
        'entidade_origem_id',
        'entidade_destino_id',
        'almoxarifado_item_id',
        'quantidade',
        'status',
        'solicitado_por_user_id',
        'aprovado_por_user_id',
        'data_solicitacao',
        'data_conclusao',
        'observacao',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'data_solicitacao' => 'datetime',
        'data_conclusao' => 'datetime',
    ];

    public function entidadeOrigem(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_origem_id');
    }

    public function entidadeDestino(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_destino_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(AlmoxarifadoItem::class, 'almoxarifado_item_id');
    }

    public function solicitadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitado_por_user_id');
    }

    public function aprovadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovado_por_user_id');
    }
}
