<?php

namespace App\Models;

use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use App\Enums\TipoParticipanteEvento;
use App\Helpers\UuidHelper;
use App\Services\QRCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'qr_code',
        'tipo_evento_id',
        'entidade_criadora_id',
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'local',
        'escopo',
        'status',
        'ativo',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'escopo' => EscopoEvento::class,
        'status' => StatusEvento::class,
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = UuidHelper::generateUnique($model, 5);
            }
            if (empty($model->qr_code)) {
                $qrCodeService = new QRCodeService();
                $model->qr_code = $qrCodeService->generateForDirigente($model->uuid);
            }
        });
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class);
    }

    public function entidadeCriadora()
    {
        return $this->belongsTo(Entidade::class, 'entidade_criadora_id');
    }

    public function eventoEntidades()
    {
        return $this->hasMany(EventoEntidade::class);
    }

    public function entidades()
    {
        return $this->belongsToMany(
            Entidade::class,
            'evento_entidades',
            'evento_id',
            'entidade_id'
        )->withTimestamps();
    }

    public function participantes()
    {
        return $this->hasMany(EventoParticipante::class);
    }

    public function dirigentes()
    {
        return $this->belongsToMany(
            Dirigente::class,
            'evento_participantes',
            'evento_id',
            'dirigente_id'
        )
        ->where('tipo_participante', TipoParticipanteEvento::Dirigente->value)
        ->withTimestamps();
    }

    public function externos()
    {
        return $this->belongsToMany(
            ParticipanteExterno::class,
            'evento_participantes',
            'evento_id',
            'participante_externo_id'
        )
        ->where('tipo_participante', TipoParticipanteEvento::Externo->value)
        ->withTimestamps();
    }

    public function tiposCamiseta()
    {
        return $this->hasMany(EventoTipoCamiseta::class);
    }

    public function valores()
    {
        return $this->hasMany(EventoValor::class);
    }

    public function barzinhos()
    {
        return $this->hasMany(Barzinho::class, 'evento_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePublicados($query)
    {
        return $query->where('status', StatusEvento::Publicado->value);
    }

    public function isPublicado(): bool
    {
        return $this->status === StatusEvento::Publicado;
    }

    public function isRascunho(): bool
    {
        return $this->status === StatusEvento::Rascunho;
    }

    public function isEncerrado(): bool
    {
        return $this->status === StatusEvento::Encerrado;
    }

    public function isCancelado(): bool
    {
        return $this->status === StatusEvento::Cancelado;
    }
}
