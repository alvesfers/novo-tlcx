<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventoTipoInscricao extends Model
{
    protected $table = 'evento_tipos_inscricao';

    protected $fillable = [
        'evento_id',
        'nome',
        'publico',
        'descricao',
        'ativo',
        'ordem',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ordem' => 'integer',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function opcoes(): HasMany
    {
        return $this->hasMany(EventoInscricaoOpcao::class, 'tipo_id')->orderBy('ordem');
    }

    public function opcoesAtivas(): HasMany
    {
        return $this->opcoes()->where('ativo', true);
    }

    public function publicoLabel(): string
    {
        return match($this->publico) {
            'dirigente_interno' => 'Dirigente Interno',
            'dirigente_externo' => 'Dirigente Externo',
            'externo'           => 'Participante Externo',
            default             => $this->publico,
        };
    }

    public function publicoBadgeClass(): string
    {
        return match($this->publico) {
            'dirigente_interno' => 'bg-blue-50 text-blue-700',
            'dirigente_externo' => 'bg-purple-50 text-purple-700',
            'externo'           => 'bg-amber-50 text-amber-700',
            default             => 'bg-gray-100 text-gray-600',
        };
    }
}
