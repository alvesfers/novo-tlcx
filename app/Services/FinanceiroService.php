<?php

namespace App\Services;

use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use Illuminate\Support\Facades\DB;

class FinanceiroService
{
    public function criarMovimento(array $data): FinanceiroMovimento
    {
        return DB::transaction(function () use ($data) {
            $categoria = FinanceiroCategoria::findOrFail($data['financeiro_categoria_id']);

            if ($categoria->tipo->value !== $data['tipo']) {
                throw new \InvalidArgumentException(
                    'O tipo do movimento não corresponde ao tipo da categoria'
                );
            }

            if ($categoria->entidade_id !== $data['entidade_id']) {
                throw new \InvalidArgumentException(
                    'A categoria não pertence à entidade especificada'
                );
            }

            if (!$categoria->ativo) {
                throw new \InvalidArgumentException(
                    'A categoria está desativada'
                );
            }

            return FinanceiroMovimento::create($data);
        });
    }

    public function atualizarMovimento(FinanceiroMovimento $movimento, array $data): FinanceiroMovimento
    {
        return DB::transaction(function () use ($movimento, $data) {
            if (isset($data['financeiro_categoria_id']) && $data['financeiro_categoria_id'] !== $movimento->financeiro_categoria_id) {
                $categoria = FinanceiroCategoria::findOrFail($data['financeiro_categoria_id']);

                if ($categoria->tipo->value !== ($data['tipo'] ?? $movimento->tipo)) {
                    throw new \InvalidArgumentException(
                        'O tipo do movimento não corresponde ao tipo da categoria'
                    );
                }

                if ($categoria->entidade_id !== $movimento->entidade_id) {
                    throw new \InvalidArgumentException(
                        'A categoria não pertence à entidade do movimento'
                    );
                }
            }

            $movimento->update($data);
            return $movimento;
        });
    }

    public function deletarMovimento(FinanceiroMovimento $movimento): bool
    {
        return $movimento->delete();
    }

    public function calcularSaldo($entidadeId, $dataAte = null)
    {
        $query = FinanceiroMovimento::porEntidade($entidadeId);

        if ($dataAte) {
            $query->where('data_movimento', '<=', $dataAte);
        }

        $entradas = $query->clone()->entradas()->sum('valor');
        $saidas = $query->clone()->saidas()->sum('valor');

        return $entradas - $saidas;
    }

    public function calcularSaldoPeriodo($entidadeId, $dataInicio, $dataFim)
    {
        $entradas = FinanceiroMovimento::porEntidade($entidadeId)
            ->entradas()
            ->porPeriodo($dataInicio, $dataFim)
            ->sum('valor');

        $saidas = FinanceiroMovimento::porEntidade($entidadeId)
            ->saidas()
            ->porPeriodo($dataInicio, $dataFim)
            ->sum('valor');

        return [
            'entradas' => $entradas,
            'saidas' => $saidas,
            'saldo' => $entradas - $saidas,
        ];
    }
}
