<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventoInscricaoOpcao extends Model
{
    protected $table = 'evento_inscricao_opcoes';

    protected $fillable = [
        'tipo_id',
        'nome',
        'valor_base',
        'descricao',
        'inclui_camiseta',
        'ativo',
        'ordem',
    ];

    protected $casts = [
        'valor_base'      => 'decimal:2',
        'inclui_camiseta' => 'boolean',
        'ativo'           => 'boolean',
        'ordem'           => 'integer',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(EventoTipoInscricao::class, 'tipo_id');
    }

    public function camisetas(): HasMany
    {
        return $this->hasMany(EventoInscricaoOpcaoCamiseta::class, 'opcao_id');
    }

    public function camisetasAtivas(): HasMany
    {
        return $this->camisetas()->where('ativo', true);
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(EventoParticipante::class, 'inscricao_opcao_id');
    }

    public function valorTotal(?string $tipoCamiseta = null): float
    {
        $base = (float) $this->valor_base;
        if ($tipoCamiseta) {
            $cam = $this->camisetas()->where('tipo_camiseta', $tipoCamiseta)->first();
            $base += $cam ? (float) $cam->valor_adicional : 0;
        }
        return $base;
    }

    public function labelCompleto(): string
    {
        return $this->nome . ' — R$ ' . number_format($this->valor_base, 2, ',', '.');
    }
}
