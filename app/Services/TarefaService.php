<?php

namespace App\Services;

use App\Enums\StatusTarefa;
use App\Models\Dirigente;
use App\Models\Tarefa;
use App\Models\TarefaComentario;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TarefaService
{
    public function criar(array $data): Tarefa
    {
        return DB::transaction(function () use ($data) {
            $data['criada_por_user_id'] = auth()->id();
            return Tarefa::create($data);
        });
    }

    public function atualizar(Tarefa $tarefa, array $data): Tarefa
    {
        return DB::transaction(function () use ($tarefa, $data) {
            $tarefa->update($data);
            return $tarefa;
        });
    }

    public function alterarStatus(Tarefa $tarefa, StatusTarefa $status): Tarefa
    {
        return DB::transaction(function () use ($tarefa, $status) {
            $data = ['status' => $status];

            if ($status === StatusTarefa::Concluida) {
                $data['concluida_em'] = now();
            } elseif ($status !== StatusTarefa::Cancelada && $tarefa->concluida_em) {
                $data['concluida_em'] = null;
            }

            $tarefa->update($data);

            $this->logAuditoria($tarefa, 'alterar_status', $data);

            return $tarefa;
        });
    }

    public function concluir(Tarefa $tarefa): Tarefa
    {
        return $this->alterarStatus($tarefa, StatusTarefa::Concluida);
    }

    public function cancelar(Tarefa $tarefa): Tarefa
    {
        return $this->alterarStatus($tarefa, StatusTarefa::Cancelada);
    }

    public function atribuirResponsavel(Tarefa $tarefa, ?User $user = null, ?Dirigente $dirigente = null): Tarefa
    {
        return DB::transaction(function () use ($tarefa, $user, $dirigente) {
            $tarefa->update([
                'responsavel_user_id' => $user?->id,
                'responsavel_dirigente_id' => $dirigente?->id,
            ]);

            $this->logAuditoria($tarefa, 'atribuir_responsavel', [
                'user_id' => $user?->id,
                'dirigente_id' => $dirigente?->id,
            ]);

            return $tarefa;
        });
    }

    public function adicionarComentario(Tarefa $tarefa, string $comentario): TarefaComentario
    {
        return DB::transaction(function () use ($tarefa, $comentario) {
            $comentarioModel = TarefaComentario::create([
                'tarefa_id' => $tarefa->id,
                'user_id' => auth()->id(),
                'comentario' => $comentario,
            ]);

            $this->logAuditoria($tarefa, 'adicionar_comentario', [
                'comentario' => $comentario,
            ]);

            return $comentarioModel;
        });
    }

    private function logAuditoria(Tarefa $tarefa, string $acao, array $dados): void
    {
        if (class_exists(\App\Services\AuditLogService::class)) {
            $service = app(\App\Services\AuditLogService::class);
            if ($acao === 'alterar_status') {
                $service->logUpdate($tarefa, array_merge($tarefa->getOriginal(), $dados));
            } elseif ($acao === 'adicionar_comentario') {
                $service->logAction($acao, $tarefa, null, $dados);
            } else {
                $service->logCreate($tarefa);
            }
        }
    }
}
