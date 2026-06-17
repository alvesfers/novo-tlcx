<?php

namespace App\Models;

use App\Enums\StatusItemEstoque;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlmoxarifadoItem extends Model
{
    use SoftDeletes;

    protected $table = 'almoxarifado_itens';

    protected $fillable = [
        'entidade_id',
        'almoxarifado_categoria_id',
        'nome',
        'descricao',
        'unidade_medida',
        'quantidade_atual',
        'quantidade_minima',
        'localizacao',
        'status',
        'observacao',
    ];

    protected $casts = [
        'quantidade_atual' => 'decimal:2',
        'quantidade_minima' => 'decimal:2',
        'status' => StatusItemEstoque::class,
    ];

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(AlmoxarifadoCategoria::class, 'almoxarifado_categoria_id');
    }

    public function movimentos(): HasMany
    {
        return $this->hasMany(AlmoxarifadoMovimento::class, 'almoxarifado_item_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', StatusItemEstoque::Ativo)->whereNull('deleted_at');
    }

    public function scopeEsgotados($query)
    {
        return $query->where('status', StatusItemEstoque::Esgotado)->whereNull('deleted_at');
    }

    public function scopeAbaixoMinimo($query)
    {
        return $query->whereRaw('quantidade_atual < quantidade_minima')
            ->whereNotNull('quantidade_minima')
            ->where('status', StatusItemEstoque::Ativo)
            ->whereNull('deleted_at');
    }

    public function getEstoqueBaixoAttribute(): bool
    {
        if (!$this->quantidade_minima) {
            return false;
        }
        return $this->quantidade_atual < $this->quantidade_minima;
    }
}
