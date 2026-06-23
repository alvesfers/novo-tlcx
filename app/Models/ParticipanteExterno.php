<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use App\Services\QRCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipanteExterno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'qr_code',
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
