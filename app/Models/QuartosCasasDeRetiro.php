<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuartosCasasDeRetiro extends Model
{
    use SoftDeletes;

    protected $table = 'quartos_casas_de_retiro';
    protected $primaryKey = 'id_quarto';

    protected $fillable = [
        'id_casa',
        'numero_quarto',
        'vagas',
        'cama',
        'beliche',
        'banheiros',
        'chuveiros',
        'acessibilidade',
    ];

    protected $casts = [
        'acessibilidade' => 'boolean',
    ];

    public function casa()
    {
        return $this->belongsTo(CasasDeRetiro::class, 'id_casa', 'id_casa');
    }
}
