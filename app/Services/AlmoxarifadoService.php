<?php

namespace App\Services;

use App\Enums\StatusItemEstoque;
use App\Enums\TipoMovimentoEstoque;
use App\Models\AlmoxarifadoItem;
use App\Models\AlmoxarifadoMovimento;
use App\Models\Entidade;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AlmoxarifadoService
{
    public function criarItem(array $data): AlmoxarifadoItem
    {
        return DB::transaction(function () use ($data) {
            return AlmoxarifadoItem::create($data);
        });
    }

    public function atualizarItem(AlmoxarifadoItem $item, array $data): AlmoxarifadoItem
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);
            return $item;
        });
    }

    public function registrarEntrada(AlmoxarifadoItem $item, float $quantidade, array $data): AlmoxarifadoMovimento
    {
        return DB::transaction(function () use ($item, $quantidade, $data) {
            $quantidadeAnterior = $item->quantidade_atual;
            $quantidadePosterior = $quantidadeAnterior + $quantidade;

            $movimento = AlmoxarifadoMovimento::create(array_merge($data, [
                'entidade_id' => $item->entidade_id,
                'almoxarifado_item_id' => $item->id,
                'tipo_movimento' => TipoMovimentoEstoque::Entrada,
                'quantidade' => $quantidade,
                'quantidade_anterior' => $quantidadeAnterior,
                'quantidade_posterior' => $quantidadePosterior,
                'data_movimento' => $data['data_movimento'] ?? now(),
                'responsavel_user_id' => auth()->id(),
            ]));

            $item->update(['quantidade_atual' => $quantidadePosterior]);

            if ($quantidadePosterior > 0 && $item->status === StatusItemEstoque::Esgotado) {
                $item->update(['status' => StatusItemEstoque::Ativo]);
            }

            $this->logAuditoria($item, 'entrada', $data);

            return $movimento;
        });
    }

    public function registrarSaida(AlmoxarifadoItem $item, float $quantidade, array $data): AlmoxarifadoMovimento
    {
        return DB::transaction(function () use ($item, $quantidade, $data) {
            if ($item->quantidade_atual < $quantidade) {
                throw new \Exception('Quantidade de saída não pode ser maior que o estoque atual.');
            }

            $quantidadeAnterior = $item->quantidade_atual;
            $quantidadePosterior = $quantidadeAnterior - $quantidade;

            $movimento = AlmoxarifadoMovimento::create(array_merge($data, [
                'entidade_id' => $item->entidade_id,
                'almoxarifado_item_id' => $item->id,
                'tipo_movimento' => TipoMovimentoEstoque::Saida,
                'quantidade' => $quantidade,
                'quantidade_anterior' => $quantidadeAnterior,
                'quantidade_posterior' => $quantidadePosterior,
                'data_movimento' => $data['data_movimento'] ?? now(),
                'responsavel_user_id' => auth()->id(),
            ]));

            $item->update(['quantidade_atual' => $quantidadePosterior]);

            if ($quantidadePosterior == 0) {
                $item->update(['status' => StatusItemEstoque::Esgotado]);
            } elseif ($item->quantidade_minima && $quantidadePosterior < $item->quantidade_minima) {
                // Mantém ativo mas pode alertar
            }

            $this->logAuditoria($item, 'saida', $data);

            return $movimento;
        });
    }

    public function registrarAjuste(AlmoxarifadoItem $item, float $novaQuantidade, array $data): AlmoxarifadoMovimento
    {
        return DB::transaction(function () use ($item, $novaQuantidade, $data) {
            $quantidadeAnterior = $item->quantidade_atual;
            $quantidade = abs($novaQuantidade - $quantidadeAnterior);

            $movimento = AlmoxarifadoMovimento::create(array_merge($data, [
                'entidade_id' => $item->entidade_id,
                'almoxarifado_item_id' => $item->id,
                'tipo_movimento' => TipoMovimentoEstoque::Ajuste,
                'quantidade' => $quantidade,
                'quantidade_anterior' => $quantidadeAnterior,
                'quantidade_posterior' => $novaQuantidade,
                'data_movimento' => $data['data_movimento'] ?? now(),
                'responsavel_user_id' => auth()->id(),
            ]));

            $item->update(['quantidade_atual' => $novaQuantidade]);

            if ($novaQuantidade == 0) {
                $item->update(['status' => StatusItemEstoque::Esgotado]);
            } elseif ($novaQuantidade > 0) {
                $item->update(['status' => StatusItemEstoque::Ativo]);
            }

            $this->logAuditoria($item, 'ajuste', $data);

            return $movimento;
        });
    }

    public function calcularSaldoItem(AlmoxarifadoItem $item): float
    {
        return $item->quantidade_atual;
    }

    public function itensAbaixoMinimo(Entidade $entidade): \Illuminate\Database\Eloquent\Collection
    {
        return AlmoxarifadoItem::query()
            ->where('entidade_id', $entidade->id)
            ->abaixoMinimo()
            ->get();
    }

    public function resumoEstoque(User $user): array
    {
        $entidade = $user->entidade;

        if (!$entidade) {
            return [];
        }

        $itensAbaixoMinimo = $this->itensAbaixoMinimo($entidade);
        $itensEsgotados = AlmoxarifadoItem::where('entidade_id', $entidade->id)
            ->where('status', StatusItemEstoque::Esgotado)
            ->count();

        $totalItens = AlmoxarifadoItem::where('entidade_id', $entidade->id)
            ->count();

        return [
            'total_itens' => $totalItens,
            'itens_esgotados' => $itensEsgotados,
            'itens_abaixo_minimo' => $itensAbaixoMinimo->count(),
            'itens_baixo_minimo' => $itensAbaixoMinimo,
        ];
    }

    private function logAuditoria(AlmoxarifadoItem $item, string $tipo, array $data): void
    {
        if (class_exists(\App\Services\AuditLogService::class)) {
            $service = app(\App\Services\AuditLogService::class);
            $service->logAction($tipo, $item, null, $data);
        }
    }
}
