<?php

namespace App\Services;

use App\Models\Entidade;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\FinanceiroMovimento;
use Carbon\Carbon;

class ChartDataService
{
    public function getFluxoCaixaMensal(?Entidade $entidade = null): array
    {
        $dados = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $mes_label = $mes->translatedFormat('M/y');

            $query_entrada = FinanceiroMovimento::where('tipo', 'entrada')
                ->whereYear('data_movimento', $mes->year)
                ->whereMonth('data_movimento', $mes->month);

            $query_saida = FinanceiroMovimento::where('tipo', 'saida')
                ->whereYear('data_movimento', $mes->year)
                ->whereMonth('data_movimento', $mes->month);

            if ($entidade) {
                $filhos = $this->getEntidadesHierarquia($entidade);
                $query_entrada->whereIn('entidade_id', $filhos);
                $query_saida->whereIn('entidade_id', $filhos);
            }

            $dados[] = [
                'mes' => $mes_label,
                'entradas' => $query_entrada->sum('valor'),
                'saidas' => $query_saida->sum('valor'),
            ];
        }

        return $dados;
    }

    public function getDistribuicaoEventosPorStatus(?Entidade $entidade = null): array
    {
        $query = Evento::query();

        if ($entidade) {
            $filhos = $this->getEntidadesHierarquia($entidade);
            $query->whereIn('entidade_criadora_id', $filhos);
        }

        return [
            'labels' => ['Publicado', 'Rascunho', 'Encerrado', 'Cancelado'],
            'data' => [
                $query->clone()->where('status', 'publicado')->count(),
                $query->clone()->where('status', 'rascunho')->count(),
                $query->clone()->where('status', 'encerrado')->count(),
                $query->clone()->where('status', 'cancelado')->count(),
            ],
            'colors' => ['#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
        ];
    }

    public function getPresencaEventos(?Entidade $entidade = null): array
    {
        $query = Evento::where('data_inicio', '>=', now()->subMonths(3))
            ->where('status', 'publicado');

        if ($entidade) {
            $filhos = $this->getEntidadesHierarquia($entidade);
            $query->whereIn('entidade_criadora_id', $filhos);
        }

        $eventos = $query->with('participantes')->get();

        $labels = [];
        $presencas = [];

        foreach ($eventos->take(8) as $evento) {
            $labels[] = substr($evento->nome, 0, 15) . '...';
            $total = $evento->participantes->count();
            $presentes = $evento->participantes->filter(fn($p) => $p->checkin_em != null)->count();
            $presencas[] = $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
        }

        return [
            'labels' => $labels,
            'data' => $presencas,
        ];
    }

    public function getDistribuicaoDirigentesPorCargo(?Entidade $entidade = null): array
    {
        $query = Dirigente::selectRaw('cargo, COUNT(*) as total')
            ->groupBy('cargo');

        if ($entidade) {
            $filhos = $this->getEntidadesHierarquia($entidade);
            $query->whereHas('vinculos', fn($q) => $q->whereIn('entidade_id', $filhos));
        }

        $dados = $query->pluck('total', 'cargo');

        return [
            'labels' => $dados->keys()->map(fn($c) => ucfirst($c))->toArray(),
            'data' => $dados->values()->toArray(),
            'colors' => ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
        ];
    }

    private function getEntidadesHierarquia(?Entidade $entidade): array
    {
        if (!$entidade) {
            return Entidade::pluck('id')->toArray();
        }

        if ($entidade->tipo_entidade === 'diocese') {
            $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id')->toArray();
            $filhos[] = $entidade->id;
            return $filhos;
        }

        return [$entidade->id];
    }

    public function getChartjsConfig(string $tipo, ?Entidade $entidade = null): array
    {
        return match($tipo) {
            'fluxo_caixa' => $this->configFluxoCaixa($entidade),
            'eventos_status' => $this->configEventosStatus($entidade),
            'presenca_eventos' => $this->configPresencaEventos($entidade),
            'dirigentes_cargo' => $this->configDirigentesCargoConfig($entidade),
            default => [],
        };
    }

    private function configFluxoCaixa(?Entidade $entidade): array
    {
        $dados = $this->getFluxoCaixaMensal($entidade);

        return [
            'type' => 'line',
            'labels' => array_column($dados, 'mes'),
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => array_column($dados, 'entradas'),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Saídas',
                    'data' => array_column($dados, 'saidas'),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    private function configEventosStatus(?Entidade $entidade): array
    {
        $dados = $this->getDistribuicaoEventosPorStatus($entidade);

        return [
            'type' => 'doughnut',
            'labels' => $dados['labels'],
            'datasets' => [
                [
                    'data' => $dados['data'],
                    'backgroundColor' => $dados['colors'],
                ],
            ],
        ];
    }

    private function configPresencaEventos(?Entidade $entidade): array
    {
        $dados = $this->getPresencaEventos($entidade);

        return [
            'type' => 'bar',
            'labels' => $dados['labels'],
            'datasets' => [
                [
                    'label' => 'Taxa de Presença (%)',
                    'data' => $dados['data'],
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];
    }

    private function configDirigentesCargoConfig(?Entidade $entidade): array
    {
        $dados = $this->getDistribuicaoDirigentesPorCargo($entidade);

        return [
            'type' => 'bar',
            'labels' => $dados['labels'],
            'datasets' => [
                [
                    'label' => 'Dirigentes',
                    'data' => $dados['data'],
                    'backgroundColor' => $dados['colors'],
                ],
            ],
        ];
    }
}
