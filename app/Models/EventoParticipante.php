<?php

namespace App\Models;

use App\Enums\TipoParticipanteEvento;
use Illuminate\Database\Eloquent\Model;

class EventoParticipante extends Model
{
    protected $fillable = [
        'evento_id',
        'tipo_participante',
        'dirigente_id',
        'participante_externo_id',
        'funcao_dirigente_id',
        'presenca',
        'checkin_em',
        'observacao',
    ];

    protected $casts = [
        'tipo_participante' => TipoParticipanteEvento::class,
        'presenca' => 'boolean',
        'checkin_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function dirigente()
    {
        return $this->belongsTo(Dirigente::class);
    }

    public function participanteExterno()
    {
        return $this->belongsTo(ParticipanteExterno::class);
    }

    public function funcaoDirigente()
    {
        return $this->belongsTo(FuncaoDirigente::class);
    }

    public function isDirigente(): bool
    {
        return $this->tipo_participante === TipoParticipanteEvento::Dirigente;
    }

    public function isExterno(): bool
    {
        return $this->tipo_participante === TipoParticipanteEvento::Externo;
    }

    public function marcarPresenca()
    {
        $this->presenca = true;
        $this->checkin_em = now();
        $this->save();
        return $this;
    }
}
