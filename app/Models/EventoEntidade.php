<?php

namespace App\Models;

use App\Enums\TipoParticipacaoEvento;
use Illuminate\Database\Eloquent\Model;

class EventoEntidade extends Model
{
    protected $table = 'evento_entidades';

    protected $fillable = ['evento_id', 'entidade_id', 'tipo_participacao'];

    protected $casts = [
        'tipo_participacao' => TipoParticipacaoEvento::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function isOrganizadora(): bool
    {
        return $this->tipo_participacao === TipoParticipacaoEvento::Organizadora;
    }

    public function isParticipante(): bool
    {
        return $this->tipo_participacao === TipoParticipacaoEvento::Participante;
    }

    public function isApoio(): bool
    {
        return $this->tipo_participacao === TipoParticipacaoEvento::Apoio;
    }
}
