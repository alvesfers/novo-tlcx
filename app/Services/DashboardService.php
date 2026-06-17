<?php

namespace App\Services;

use App\Models\User;
use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\Evento;
use App\Models\FinanceiroMovimento;
use App\Enums\TipoMovimentoFinanceiro;

class DashboardService
{
    public function getResumo(User $user): array
    {
        $entidade = $user->entidade;

        // Se admin sem entidade, pega a primeira diocese
        if (!$entidade && $user->isAdmin()) {
            $entidade = Entidade::where('tipo_entidade', 'diocese')->first() ?? Entidade::first();
        }

        // Estrutura padrão vazia para quando não há entidade
        $empty_indicadores = [
            'total_entidades' => 0,
            'total_dirigentes' => 0,
            'total_eventos' => 0,
            'eventos_proximos' => 0,
            'eventos_encerrados' => 0,
        ];

        $empty_financeiro = [
            'total_entradas' => 0,
            'total_saidas' => 0,
            'saldo' => 0,
            'movimentacoes_recentes' => 0,
        ];

        $empty_eventos = [
            'proximos' => 0,
            'encerrados' => 0,
            'pendentes' => 0,
        ];

        $empty_dirigentes = [
            'total' => 0,
            'ativos' => 0,
            'inativos' => 0,
        ];

        return [
            'tipo_usuario' => $user->tipo_usuario,
            'entidade' => $entidade,
            'indicadores' => $entidade ? $this->getIndicadoresEntidade($entidade) : $empty_indicadores,
            'financeiro' => $entidade ? $this->getIndicadoresFinanceiros($entidade) : $empty_financeiro,
            'eventos' => $entidade ? $this->getIndicadoresEventos($entidade) : $empty_eventos,
            'dirigentes' => $entidade ? $this->getIndicadoresDirigentes($entidade) : $empty_dirigentes,
            'proximos_eventos' => $entidade ? $this->getProximosEventos($user) : [],
            'ultimas_movimentacoes' => $entidade ? $this->getUltimasMovimentacoes($user) : [],
        ];
    }

    public function getIndicadoresEntidade(Entidade $entidade): array
    {
        $entidades_visiveis = $this->getEntidadesVisiveis($entidade);
        $dirigentes_visiveis = $this->getDirigentesVisiveis($entidade);
        $eventos_visiveis = $this->getEventosVisiveis($entidade);

        return [
            'total_entidades' => count($entidades_visiveis),
            'total_dirigentes' => $dirigentes_visiveis->count(),
            'total_eventos' => $eventos_visiveis->count(),
            'eventos_proximos' => $this->getEventosProximos($entidade)->count(),
            'eventos_encerrados' => $this->getEventosEncerrados($entidade)->count(),
        ];
    }

    public function getIndicadoresFinanceiros(Entidade $entidade): array
    {
        $total_entradas = FinanceiroMovimento::where('entidade_id', $entidade->id)
            ->where('tipo', 'entrada')
            ->sum('valor');

        $total_saidas = FinanceiroMovimento::where('entidade_id', $entidade->id)
            ->where('tipo', 'saida')
            ->sum('valor');

        $saldo = $total_entradas - $total_saidas;

        $total_movimentos = FinanceiroMovimento::where('entidade_id', $entidade->id)->count();

        return [
            'total_entradas' => $total_entradas,
            'total_saidas' => $total_saidas,
            'saldo' => $saldo,
            'movimentacoes_recentes' => $total_movimentos,
        ];
    }

    public function getIndicadoresEventos(Entidade $entidade): array
    {
        $proximos = $this->getEventosProximos($entidade);
        $encerrados = $this->getEventosEncerrados($entidade);

        return [
            'proximos' => $proximos->count(),
            'encerrados' => $encerrados->count(),
            'pendentes' => $this->getEventosPendentes($entidade)->count(),
        ];
    }

    public function getIndicadoresDirigentes(Entidade $entidade): array
    {
        $dirigentes = $this->getDirigentesVisiveis($entidade);

        return [
            'total' => $dirigentes->count(),
            'ativos' => $dirigentes->filter(function ($d) {
                return $d->ativo;
            })->count(),
            'inativos' => $dirigentes->filter(function ($d) {
                return !$d->ativo;
            })->count(),
        ];
    }

    public function getProximosEventos(User $user): array
    {
        if (!$user->entidade) {
            return [];
        }

        $entidades_visiveis = $this->getEntidadesVisiveis($user->entidade)->pluck('id');

        return Evento::whereIn('entidade_criadora_id', $entidades_visiveis)
            ->where('status', 'publicado')
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio')
            ->take(5)
            ->get()
            ->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'nome' => $evento->nome,
                    'data_inicio' => $evento->data_inicio->format('d/m/Y'),
                    'local' => $evento->local,
                ];
            })
            ->toArray();
    }

    public function getUltimasMovimentacoes(User $user): array
    {
        if (!$user->entidade) {
            return [];
        }

        $entidades_visiveis = $this->getEntidadesVisiveis($user->entidade)->pluck('id');

        return FinanceiroMovimento::whereIn('entidade_id', $entidades_visiveis)
            ->orderBy('data_movimento', 'desc')
            ->take(5)
            ->get()
            ->map(function ($mov) {
                return [
                    'id' => $mov->id,
                    'descricao' => $mov->descricao,
                    'valor' => $mov->valor,
                    'tipo' => $mov->tipo->value,
                    'data' => $mov->data_movimento->format('d/m/Y'),
                ];
            })
            ->toArray();
    }

    private function getEntidadesVisiveis(Entidade $entidade)
    {
        if ($entidade->tipo_entidade === 'diocese') {
            return Entidade::where('id', $entidade->id)
                ->orWhere('entidade_pai_id', $entidade->id)
                ->get();
        }

        return Entidade::whereIn('id', [$entidade->id])->get();
    }

    private function getDirigentesVisiveis(Entidade $entidade)
    {
        $entidades = $this->getEntidadesVisiveis($entidade)->pluck('id');

        return Dirigente::whereHas('vinculos', function ($query) use ($entidades) {
            $query->whereIn('entidade_id', $entidades);
        })->get();
    }

    private function getEventosVisiveis(Entidade $entidade)
    {
        $entidades = $this->getEntidadesVisiveis($entidade)->pluck('id');

        return Evento::whereIn('entidade_criadora_id', $entidades)->get();
    }

    private function getEventosProximos(Entidade $entidade)
    {
        $entidades = $this->getEntidadesVisiveis($entidade)->pluck('id');

        return Evento::whereIn('entidade_criadora_id', $entidades)
            ->where('data_inicio', '>=', now())
            ->get();
    }

    private function getEventosEncerrados(Entidade $entidade)
    {
        $entidades = $this->getEntidadesVisiveis($entidade)->pluck('id');

        return Evento::whereIn('entidade_criadora_id', $entidades)
            ->where('status', 'encerrado')
            ->get();
    }

    private function getEventosPendentes(Entidade $entidade)
    {
        $entidades = $this->getEntidadesVisiveis($entidade)->pluck('id');

        return Evento::whereIn('entidade_criadora_id', $entidades)
            ->where('status', 'rascunho')
            ->get();
    }
}
