<?php

namespace App\Models;

use App\Enums\TipoVinculo;
use App\Helpers\UuidHelper;
use App\Traits\HandlesFoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dirigente extends Model
{
    use SoftDeletes, HandlesFoto;

    protected $fillable = [
        'uuid',
        'qr_code',
        'nome',
        'telefone',
        'email',
        'genero',
        'data_nascimento',
        'foto_url',
        'foto_arquivo',
        'id_tlc',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = UuidHelper::generateUnique($model, 5);
            }
        });
    }

    public function vinculos(): HasMany
    {
        return $this->hasMany(DirigenteEntidade::class, 'dirigente_id');
    }

    public function entidades(): BelongsToMany
    {
        return $this->belongsToMany(Entidade::class, 'dirigente_entidades', 'dirigente_id', 'entidade_id')
            ->using(DirigenteEntidade::class)
            ->withPivot(['tipo_vinculo', 'cargo', 'papel', 'data_inicio', 'data_fim', 'ativo', 'created_at', 'updated_at', 'deleted_at']);
    }

    public function eventoParticipantes(): HasMany
    {
        return $this->hasMany(EventoParticipante::class, 'dirigente_id');
    }

    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'evento_participantes', 'dirigente_id', 'evento_id')
            ->withTimestamps();
    }

    public function habilidades(): BelongsToMany
    {
        return $this->belongsToMany(Habilidade::class, 'dirigente_habilidades')
            ->withPivot(['nivel', 'observacao'])
            ->using(DirigenteHabilidade::class);
    }

    public function tlc()
    {
        return $this->belongsTo(Evento::class, 'id_tlc');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)->whereNull('deleted_at');
    }

    public function getVinculoPrincipal(): ?DirigenteEntidade
    {
        return $this->vinculos()
            ->where('tipo_vinculo', TipoVinculo::Principal)
            ->where('ativo', true)
            ->first();
    }

    public function getNucleoPrincipal(): ?Entidade
    {
        $vínculo = $this->getVinculoPrincipal();
        return $vínculo ? $vínculo->entidade : null;
    }

    public function pertenceAEntidade($entidadeId): bool
    {
        return $this->vinculos()
            ->where('entidade_id', $entidadeId)
            ->where('ativo', true)
            ->exists();
    }

    public function pertenceADiocese($dioceseId): bool
    {
        $nucleoPrincipal = $this->getNucleoPrincipal();

        if (!$nucleoPrincipal) {
            return false;
        }

        // Se o núcleo principal pertence à diocese
        if ($nucleoPrincipal->id === $dioceseId) {
            return true;
        }

        // Se o núcleo principal tem a diocese como pai
        if ($nucleoPrincipal->entidade_pai_id === $dioceseId) {
            return true;
        }

        // Verificar se há vínculo direto de coordenação com a diocese
        return $this->vinculos()
            ->where('entidade_id', $dioceseId)
            ->where('tipo_vinculo', TipoVinculo::Coordenacao)
            ->where('ativo', true)
            ->exists();
    }
}
