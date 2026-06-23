<?php

namespace App\Models;

use App\Enums\TipoBarzinho;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barzinho extends Model
{
    use SoftDeletes;

    protected $table = 'barzinhos';

    protected $fillable = [
        'evento_id',
        'nome',
        'descricao',
        'tipo_venda',
        'barzinho_config',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'tipo_venda' => TipoBarzinho::class,
        'barzinho_config' => 'json',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(BarzinhoProduto::class, 'barzinho_id');
    }

    public function combos(): HasMany
    {
        return $this->hasMany(BarzinhoCombo::class, 'barzinho_id');
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(BarzinhoVenda::class, 'barzinho_id');
    }
}
