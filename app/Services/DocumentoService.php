<?php

namespace App\Services;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoService
{
    protected array $extensoesPermitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'txt'];
    protected int $tamanhoMaximo = 10 * 1024 * 1024; // 10MB

    public function upload(array $data, UploadedFile $file): Documento
    {
        return DB::transaction(function () use ($data, $file) {
            $this->validarArquivo($file);

            $nomeOriginal = $file->getClientOriginalName();
            $nomeArmazenado = $this->gerarNomeArquivo($file);
            $caminho = 'documentos/' . $data['entidade_id'];

            if ($data['visibilidade'] === 'privado') {
                $caminho = 'private/' . $caminho;
                $path = Storage::disk('local')->putFileAs($caminho, $file, $nomeArmazenado);
            } else {
                $path = Storage::disk('public')->putFileAs($caminho, $file, $nomeArmazenado);
            }

            $documento = Documento::create(array_merge($data, [
                'uploaded_by_user_id' => auth()->id(),
                'arquivo_nome_original' => $nomeOriginal,
                'arquivo_nome_armazenado' => $nomeArmazenado,
                'arquivo_path' => $path,
                'arquivo_mime' => $file->getMimeType(),
                'arquivo_tamanho' => $file->getSize(),
            ]));

            $this->logAuditoria($documento, 'upload', $data);

            return $documento;
        });
    }

    public function atualizar(Documento $documento, array $data, ?UploadedFile $file = null): Documento
    {
        return DB::transaction(function () use ($documento, $data, $file) {
            $caminhoAntigo = $documento->arquivo_path;

            if ($file) {
                $this->validarArquivo($file);

                $nomeOriginal = $file->getClientOriginalName();
                $nomeArmazenado = $this->gerarNomeArquivo($file);
                $caminho = 'documentos/' . $documento->entidade_id;

                if ($data['visibilidade'] === 'privado') {
                    $caminho = 'private/' . $caminho;
                    $path = Storage::disk('local')->putFileAs($caminho, $file, $nomeArmazenado);
                } else {
                    $path = Storage::disk('public')->putFileAs($caminho, $file, $nomeArmazenado);
                }

                $data['arquivo_nome_original'] = $nomeOriginal;
                $data['arquivo_nome_armazenado'] = $nomeArmazenado;
                $data['arquivo_path'] = $path;
                $data['arquivo_mime'] = $file->getMimeType();
                $data['arquivo_tamanho'] = $file->getSize();

                // Remove arquivo antigo
                $this->removerArquivo($caminhoAntigo);
            }

            $documento->update($data);

            $this->logAuditoria($documento, 'atualizar', $data);

            return $documento;
        });
    }

    public function excluir(Documento $documento): bool
    {
        return DB::transaction(function () use ($documento) {
            $this->removerArquivo($documento->arquivo_path);
            $this->logAuditoria($documento, 'excluir', []);
            return $documento->delete();
        });
    }

    public function getDocumentosVisivelQuery($user)
    {
        if ($user->tipo_usuario === 'admin') {
            return Documento::ativos();
        }

        if (!$user->entidade) {
            return Documento::query()->whereRaw('1 = 0');
        }

        $entidade = $user->entidade;

        return Documento::query()
            ->ativos()
            ->where(function ($query) use ($entidade) {
                $query->where('visibilidade', 'publico')
                    ->orWhere(function ($q) use ($entidade) {
                        $q->where('visibilidade', 'privado')
                            ->where('entidade_id', $entidade->id);
                    });
            });
    }

    public function getDocumentosVisiveis(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getDocumentosVisivelQuery($user)->get();
    }

    public function podeVisualizarDocumento(User $user, Documento $documento): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if ($documento->visibilidade === 'publico' && $documento->ativo) {
            return true;
        }

        if ($documento->visibilidade === 'privado' && $user->entidade_id === $documento->entidade_id) {
            return true;
        }

        return false;
    }

    private function validarArquivo(UploadedFile $file): void
    {
        $extensao = strtolower($file->getClientOriginalExtension());

        if (!in_array($extensao, $this->extensoesPermitidas)) {
            throw new \Exception('Extensão de arquivo não permitida: ' . $extensao);
        }

        if ($file->getSize() > $this->tamanhoMaximo) {
            throw new \Exception('Arquivo excede o tamanho máximo de 10MB');
        }
    }

    private function gerarNomeArquivo(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    private function removerArquivo(string $path): void
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function logAuditoria(Documento $documento, string $acao, array $dados): void
    {
        if (class_exists(\App\Services\AuditLogService::class)) {
            $service = app(\App\Services\AuditLogService::class);
            if ($acao === 'upload') {
                $service->logCreate($documento);
            } elseif ($acao === 'atualizar') {
                $service->logUpdate($documento, array_merge($documento->getOriginal(), $dados));
            } elseif ($acao === 'excluir') {
                $service->logDelete($documento);
            }
        }
    }
}
