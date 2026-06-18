<?php

namespace App\Models;

use App\Enums\NivelHabilidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habilidade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entidade_id',
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    public function dirigentes(): BelongsToMany
    {
        return $this->belongsToMany(Dirigente::class, 'dirigente_habilidades')
            ->withPivot(['nivel', 'observacao'])
            ->using(DirigenteHabilidade::class);
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true)->whereNull('deleted_at');
    }
}
