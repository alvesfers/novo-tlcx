<?php

namespace App\Models;

use App\Enums\TipoDocumento;
use App\Enums\VisibilidadeDocumento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entidade_id',
        'evento_id',
        'documento_categoria_id',
        'uploaded_by_user_id',
        'titulo',
        'descricao',
        'arquivo_nome_original',
        'arquivo_nome_armazenado',
        'arquivo_path',
        'arquivo_mime',
        'arquivo_tamanho',
        'tipo_documento',
        'visibilidade',
        'ativo',
    ];

    protected $casts = [
        'tipo_documento' => TipoDocumento::class,
        'visibilidade' => VisibilidadeDocumento::class,
        'ativo' => 'boolean',
        'arquivo_tamanho' => 'integer',
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
        return $this->belongsTo(DocumentoCategoria::class, 'documento_categoria_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function scopePublicos($query)
    {
        return $query->where('visibilidade', VisibilidadeDocumento::Publico)
            ->where('ativo', true)
            ->whereNull('deleted_at');
    }

    public function scopePrivados($query)
    {
        return $query->where('visibilidade', VisibilidadeDocumento::Privado)
            ->where('ativo', true)
            ->whereNull('deleted_at');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)->whereNull('deleted_at');
    }

    public function scopePorEntidade($query, int $entidadeId)
    {
        return $query->where('entidade_id', $entidadeId)->whereNull('deleted_at');
    }

    public function scopePorTipo($query, TipoDocumento $tipo)
    {
        return $query->where('tipo_documento', $tipo)->whereNull('deleted_at');
    }
}
