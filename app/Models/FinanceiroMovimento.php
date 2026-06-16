<?php

namespace App\Models;

use App\Enums\FormaPagamento;
use App\Enums\TipoMovimentoFinanceiro;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceiroMovimento extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'financeiro_movimentos';

    protected $fillable = [
        'entidade_id',
        'financeiro_categoria_id',
        'evento_id',
        'tipo',
        'descricao',
        'valor',
        'data_movimento',
        'forma_pagamento',
        'comprovante_url',
        'observacao',
    ];

    protected $casts = [
        'tipo' => TipoMovimentoFinanceiro::class,
        'forma_pagamento' => FormaPagamento::class,
        'valor' => 'decimal:2',
        'data_movimento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function categoria()
    {
        return $this->belongsTo(FinanceiroCategoria::class, 'financeiro_categoria_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo', TipoMovimentoFinanceiro::Entrada);
    }

    public function scopeSaidas($query)
    {
        return $query->where('tipo', TipoMovimentoFinanceiro::Saida);
    }

    public function scopePorPeriodo($query, $inicio, $fim)
    {
        return $query->whereBetween('data_movimento', [$inicio, $fim]);
    }

    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('financeiro_categoria_id', $categoriaId);
    }

    public function scopePorEntidade($query, $entidadeId)
    {
        return $query->where('entidade_id', $entidadeId);
    }
}
