<?php

namespace App\Models;

use App\Enums\TipoMovimentoFinanceiro;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceiroCategoria extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'financeiro_categorias';

    protected $fillable = [
        'entidade_id',
        'nome',
        'tipo',
        'ativo',
    ];

    protected $casts = [
        'tipo' => TipoMovimentoFinanceiro::class,
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function movimentos()
    {
        return $this->hasMany(FinanceiroMovimento::class);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo', TipoMovimentoFinanceiro::Entrada);
    }

    public function scopeSaidas($query)
    {
        return $query->where('tipo', TipoMovimentoFinanceiro::Saida);
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
}
