<?php

namespace App\Models;

use App\Enums\PrioridadeTarefa;
use App\Enums\StatusTarefa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entidade_id',
        'evento_id',
        'tarefa_categoria_id',
        'responsavel_user_id',
        'responsavel_dirigente_id',
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'data_inicio',
        'data_limite',
        'concluida_em',
        'criada_por_user_id',
        'observacao',
    ];

    protected $casts = [
        'status' => StatusTarefa::class,
        'prioridade' => PrioridadeTarefa::class,
        'data_inicio' => 'date',
        'data_limite' => 'date',
        'concluida_em' => 'datetime',
    ];

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(TarefaCategoria::class, 'tarefa_categoria_id');
    }

    public function responsavelUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_user_id');
    }

    public function responsavelDirigente(): BelongsTo
    {
        return $this->belongsTo(Dirigente::class, 'responsavel_dirigente_id');
    }

    public function criadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criada_por_user_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(TarefaComentario::class, 'tarefa_id');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', StatusTarefa::Pendente)->whereNull('deleted_at');
    }

    public function scopeEmAndamento($query)
    {
        return $query->where('status', StatusTarefa::EmAndamento)->whereNull('deleted_at');
    }

    public function scopeConcluidas($query)
    {
        return $query->where('status', StatusTarefa::Concluida)->whereNull('deleted_at');
    }

    public function scopeVencidas($query)
    {
        return $query->whereDate('data_limite', '<', now())
            ->where('status', '!=', StatusTarefa::Concluida)
            ->where('status', '!=', StatusTarefa::Cancelada)
            ->whereNull('deleted_at');
    }

    public function scopePorPrioridade($query, PrioridadeTarefa $prioridade)
    {
        return $query->where('prioridade', $prioridade)->whereNull('deleted_at');
    }

    public function scopePorEntidade($query, int $entidadeId)
    {
        return $query->where('entidade_id', $entidadeId)->whereNull('deleted_at');
    }

    public function getIsVencidaAttribute(): bool
    {
        if (!$this->data_limite) {
            return false;
        }
        if (in_array($this->status, [StatusTarefa::Concluida, StatusTarefa::Cancelada])) {
            return false;
        }
        return $this->data_limite < now()->toDateString();
    }

    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->data_limite) {
            return null;
        }
        return now()->parse($this->data_limite)->diffInDays(now(), false);
    }
}
