<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipanteExterno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'documento',
        'genero',
        'data_nascimento',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function eventoParticipantes()
    {
        return $this->hasMany(EventoParticipante::class);
    }

    public function eventos()
    {
        return $this->belongsToMany(
            Evento::class,
            'evento_participantes',
            'participante_externo_id',
            'evento_id'
        )->withTimestamps();
    }
}
