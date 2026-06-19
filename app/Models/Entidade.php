<?php

namespace App\Models;

use App\Enums\TipoEntidade;
use App\Enums\TipoSecretaria;
use App\Traits\HandlesFoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Entidade extends Model
{
    use SoftDeletes, HasFactory, HandlesFoto;

    protected $table = 'entidades';

    protected $fillable = [
        'user_id',
        'entidade_pai_id',
        'tipo_entidade',
        'nome',
        'email',
        'tipo_secretaria',
        'paroquia',
        'endereco_paroquia',
        'padre',
        'foto_url',
        'foto_arquivo',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'tipo_entidade' => TipoEntidade::class,
        'tipo_secretaria' => TipoSecretaria::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ===== RELACIONAMENTOS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entidadePai(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_pai_id');
    }

    public function entidadesFilhas(): HasMany
    {
        return $this->hasMany(Entidade::class, 'entidade_pai_id');
    }

    public function dirigenteVinculos(): HasMany
    {
        return $this->hasMany(DirigenteEntidade::class, 'entidade_id');
    }

    public function dirigentes(): BelongsToMany
    {
        return $this->belongsToMany(Dirigente::class, 'dirigente_entidades', 'entidade_id', 'dirigente_id')
            ->using(DirigenteEntidade::class)
            ->withPivot(['tipo_vinculo', 'cargo', 'papel', 'data_inicio', 'data_fim', 'ativo', 'created_at', 'updated_at', 'deleted_at']);
    }

    public function eventosCriados(): HasMany
    {
        return $this->hasMany(Evento::class, 'entidade_criadora_id');
    }

    public function eventoParticipacoes(): HasMany
    {
        return $this->hasMany(EventoEntidade::class, 'entidade_id');
    }

    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'evento_entidades', 'entidade_id', 'evento_id')
            ->withTimestamps();
    }

    public function habilidades(): HasMany
    {
        return $this->hasMany(Habilidade::class, 'entidade_id');
    }

    // ===== SCOPES =====

    public function scopeAtivas(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    public function scopeDioceses(Builder $query): Builder
    {
        return $query->where('tipo_entidade', 'diocese');
    }

    public function scopeNucleos(Builder $query): Builder
    {
        return $query->where('tipo_entidade', 'nucleo');
    }

    public function scopeSecretarias(Builder $query): Builder
    {
        return $query->where('tipo_entidade', 'secretaria');
    }

    // ===== MÉTODOS AUXILIARES =====

    public function isDiocese(): bool
    {
        return $this->tipo_entidade === TipoEntidade::Diocese;
    }

    public function isNucleo(): bool
    {
        return $this->tipo_entidade === TipoEntidade::Nucleo;
    }

    public function isSecretaria(): bool
    {
        return $this->tipo_entidade === TipoEntidade::Secretaria;
    }

    public function getHierarquiaCompleta(): string
    {
        $caminho = $this->nome;
        $entidade = $this;

        while ($entidade->entidade_pai_id) {
            $entidade = $entidade->entidadePai;
            if ($entidade) {
                $caminho = $entidade->nome . ' > ' . $caminho;
            }
        }

        return $caminho;
    }

    public function getDiocese(): ?Entidade
    {
        if ($this->isDiocese()) {
            return $this;
        }

        $entidade = $this;
        while ($entidade->entidade_pai_id) {
            $entidade = $entidade->entidadePai;
            if ($entidade && $entidade->isDiocese()) {
                return $entidade;
            }
        }

        return null;
    }

    public function isFilhaDe($entidadeId): bool
    {
        $entidade = $this;

        while ($entidade->entidade_pai_id) {
            if ($entidade->entidade_pai_id === $entidadeId) {
                return true;
            }
            $entidade = $entidade->entidadePai;
            if (!$entidade) {
                return false;
            }
        }

        return false;
    }
}
