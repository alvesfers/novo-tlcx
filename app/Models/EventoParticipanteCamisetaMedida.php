<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoParticipanteCamisetaMedida extends Model
{
    protected $table = 'evento_participante_camiseta_medidas';

    protected $fillable = [
        'evento_participante_id',
        'fornecedor_camiseta_tamanho_id',
        'medidas_snapshot',
    ];

    protected $casts = [
        'medidas_snapshot' => 'json',
    ];

    public $timestamps = true;

    public function eventoParticipante(): BelongsTo
    {
        return $this->belongsTo(EventoParticipante::class, 'evento_participante_id');
    }

    public function tamanho(): BelongsTo
    {
        return $this->belongsTo(FornecedorCamisetaTamanho::class, 'fornecedor_camiseta_tamanho_id');
    }
}
