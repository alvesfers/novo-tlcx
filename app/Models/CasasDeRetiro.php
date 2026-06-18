<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CasasDeRetiro extends Model
{
    use SoftDeletes;

    protected $table = 'casas_de_retiro';
    protected $primaryKey = 'id_casa';

    protected $fillable = [
        'nome_casa',
        'endereco',
        'valor_estimado',
        'acessibilidade',
        'ativa',
        'capacidade',
    ];

    protected $casts = [
        'acessibilidade' => 'boolean',
        'ativa' => 'boolean',
        'valor_estimado' => 'decimal:2',
    ];

    public function quartos()
    {
        return $this->hasMany(QuartosCasasDeRetiro::class, 'id_casa', 'id_casa');
    }

    public function alas()
    {
        return $this->hasMany(AlasRetiro::class, 'id_casa', 'id_casa');
    }
}
