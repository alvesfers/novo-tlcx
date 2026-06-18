<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlasRetiro extends Model
{
    use SoftDeletes;

    protected $table = 'alas_casas_retiro';
    protected $primaryKey = 'id_ala';

    protected $fillable = [
        'id_casa',
        'nome_ala',
        'descricao',
    ];

    public function casa()
    {
        return $this->belongsTo(CasasDeRetiro::class, 'id_casa', 'id_casa');
    }

    public function quartos()
    {
        return $this->hasMany(QuartosCasasDeRetiro::class, 'id_ala', 'id_ala');
    }
}
