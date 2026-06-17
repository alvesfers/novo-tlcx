<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlmoxarifadoCategoria extends Model
{
    use SoftDeletes;

    protected $table = 'almoxarifado_categorias';

    protected $fillable = [
        'entidade_id',
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(AlmoxarifadoItem::class, 'almoxarifado_categoria_id');
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true)->whereNull('deleted_at');
    }
}
