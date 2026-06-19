<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FornecedorCamiseta extends Model
{
    use SoftDeletes;

    protected $table = 'fornecedores_camisetas';

    protected $fillable = [
        'nome',
        'descricao',
        'contato',
        'email',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function tipos(): HasMany
    {
        return $this->hasMany(FornecedorCamisetaTipo::class, 'fornecedor_id');
    }
}
