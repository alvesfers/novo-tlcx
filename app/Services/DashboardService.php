<?php

namespace App\Services;

use App\Models\User;
use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\Evento;
use App\Models\FinanceiroMovimento;
use App\Enums\TipoMovimentoFinanceiro;
use Carbon\Carbon;

class DashboardService
{
    public function getResumo(User $user): array
    {
        $entidade = $user->entidade;

        // Se admin sem entidade, pega a primeira diocese
        if (!$entidade && $user->isAdmin()) {
            $entidade = Entidade::where('tipo_entidade', 'diocese')->first() ?? Entidade::first();
        }

        // Retorna dados específicos por tipo de usuário (usando enums)
        $tipoUsuario = $user->tipo_usuario;

        return match(true) {
            $tipoUsuario->value === 'admin' => $this->getDashboardAdmin(),
            $tipoUsuario->value === 'diocese' => $this->getDashboardDiocese($entidade),
            $tipoUsuario->value === 'nucleo' => $this->getDashboardNucleo($entidade),
            $tipoUsuario->value === 'secretaria' => $this->getDashboardSecretaria($entidade),
            default => $this->getDashboardGenerico($user, $entidade),
        };
    }

    private function getDashboardAdmin(): array
    {
        return [
            'tipo_usuario' => 'admin',
            'kpis' => $this->getKpisAdmin(),
            'listas' => $this->getListasAdmin(),
            'graficos_data' => [
                'fluxo_mensal' => $this->getFluxoFinanceiroMensal(null),
                'eventos_por_status' => $this->getEventosPorStatus(null),
                'dirigentes_por_cargo' => $this->getDirigentesPorCargo(null),
            ],
        ];
    }

    private function getDashboardDiocese(Entidade $entidade): array
    {
        return [
            'tipo_usuario' => 'diocese',
            'entidade' => $entidade,
            'kpis' => $this->getKpisDiocese($entidade),
            'listas' => $this->getListasDiocese($entidade),
            'graficos_data' => [
                'fluxo_mensal' => $this->getFluxoFinanceiroMensal($entidade),
                'eventos_por_status' => $this->getEventosPorStatus($entidade),
                'dirigentes_por_cargo' => $this->getDirigentesPorCargo($entidade),
            ],
        ];
    }

    private function getDashboardNucleo(Entidade $entidade): array
    {
        return [
            'tipo_usuario' => 'nucleo',
            'entidade' => $entidade,
            'kpis' => $this->getKpisNucleo($entidade),
            'listas' => $this->getListasNucleo($entidade),
            'graficos_data' => [
                'fluxo_mensal' => $this->getFluxoFinanceiroMensal($entidade),
                'eventos_por_status' => $this->getEventosPorStatus($entidade),
            ],
        ];
    }

    private function getDashboardSecretaria(Entidade $entidade): array
    {
        return [
            'tipo_usuario' => 'secretaria',
            'entidade' => $entidade,
            'kpis' => $this->getKpisSecretaria($entidade),
            'listas' => $this->getListasSecretaria($entidade),
            'graficos_data' => [
                'fluxo_mensal' => $this->getFluxoFinanceiroMensal($entidade),
                'eventos_por_status' => $this->getEventosPorStatus($entidade),
            ],
        ];
    }

    private function getDashboardGenerico(User $user, ?Entidade $entidade): array
    {
        if (!$entidade) {
            return [
                'tipo_usuario' => $user->tipo_usuario,
                'entidade' => null,
                'kpis' => [],
                'listas' => [],
            ];
        }

        return [
            'tipo_usuario' => $user->tipo_usuario,
            'entidade' => $entidade,
            'indicadores' => $this->getIndicadoresEntidade($entidade),
            'financeiro' => $this->getIndicadoresFinanceiros($entidade),
            'eventos' => $this->getIndicadoresEventos($entidade),
            'dirigentes' => $this->getIndicadoresDirigentes($entidade),
            'proximos_eventos' => $this->getProximosEventos($user),
            'ultimas_movimentacoes' => $this->getUltimasMovimentacoes($user),
        ];
    }

    // KPIs ADMIN
    private function getKpisAdmin(): array
    {
        return [
            'total_dioceses' => Entidade::where('tipo_entidade', 'diocese')->count(),
            'total_nucleos' => Entidade::where('tipo_entidade', 'nucleo')->count(),
            'total_secretarias' => Entidade::where('tipo_entidade', 'secretaria')->count(),
            'total_dirigentes' => Dirigente::count(),
            'total_usuarios' => User::count(),
            'total_eventos' => Evento::count(),
            'saldo_global' => FinanceiroMovimento::where('tipo', 'entrada')->sum('valor') - FinanceiroMovimento::where('tipo', 'saida')->sum('valor'),
        ];
    }

    // KPIs DIOCESE
    private function getKpisDiocese(Entidade $entidade): array
    {
        $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id');
        $filhos->push($entidade->id);

        return [
            'total_nucleos' => Entidade::where('entidade_pai_id', $entidade->id)
                ->where('tipo_entidade', 'nucleo')->count(),
            'total_secretarias' => Entidade::where('entidade_pai_id', $entidade->id)
                ->where('tipo_entidade', 'secretaria')->count(),
            'total_dirigentes' => Dirigente::whereHas('vinculos', fn($q) => $q->whereIn('entidade_id', $filhos))->count(),
            'total_eventos' => Evento::whereIn('entidade_criadora_id', $filhos)->count(),
            'saldo_diocese' => FinanceiroMovimento::whereIn('entidade_id', $filhos)
                ->where('tipo', 'entrada')->sum('valor') -
                FinanceiroMovimento::whereIn('entidade_id', $filhos)
                ->where('tipo', 'saida')->sum('valor'),
        ];
    }

    // KPIs NÚCLEO
    private function getKpisNucleo(Entidade $entidade): array
    {
        $dirigentes = Dirigente::whereHas('vinculos', fn($q) => $q->where('entidade_id', $entidade->id));

        return [
            'total_dirigentes' => $dirigentes->count(),
            'total_eventos' => Evento::where('entidade_criadora_id', $entidade->id)->count(),
            'saldo_nucleo' => FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->where('tipo', 'entrada')->sum('valor') -
                FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->where('tipo', 'saida')->sum('valor'),
            'proximas_atividades' => Evento::where('entidade_criadora_id', $entidade->id)
                ->where('data_inicio', '>=', now())
                ->count(),
        ];
    }

    // KPIs SECRETARIA
    private function getKpisSecretaria(Entidade $entidade): array
    {
        $dirigentes = Dirigente::whereHas('vinculos', fn($q) => $q->where('entidade_id', $entidade->id));

        return [
            'total_dirigentes' => $dirigentes->count(),
            'total_eventos' => Evento::where('entidade_criadora_id', $entidade->id)->count(),
            'saldo_secretaria' => FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->where('tipo', 'entrada')->sum('valor') -
                FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->where('tipo', 'saida')->sum('valor'),
            'proximas_atividades' => Evento::where('entidade_criadora_id', $entidade->id)
                ->where('data_inicio', '>=', now())
                ->count(),
        ];
    }

    // LISTAS ADMIN
    private function getListasAdmin(): array
    {
        return [
            'ultimos_eventos' => Evento::orderBy('created_at', 'desc')->take(5)->get(['id', 'nome', 'created_at'])->toArray(),
            'ultimas_movimentacoes' => FinanceiroMovimento::orderBy('data_movimento', 'desc')->take(5)->get(['id', 'descricao', 'valor', 'tipo', 'data_movimento'])->toArray(),
            'entidades_maiores_saldos' => $this->getEntidadesComMaiorSaldo(5),
            'entidades_mais_eventos' => $this->getEntidadesComMaisEventos(5),
        ];
    }

    // LISTAS DIOCESE
    private function getListasDiocese(Entidade $entidade): array
    {
        $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id');
        $filhos->push($entidade->id);

        return [
            'proximos_eventos' => Evento::whereIn('entidade_criadora_id', $filhos)
                ->where('data_inicio', '>=', now())
                ->orderBy('data_inicio')
                ->take(5)->get(['id', 'nome', 'data_inicio', 'local'])->toArray(),
            'ultimas_movimentacoes' => FinanceiroMovimento::whereIn('entidade_id', $filhos)
                ->orderBy('data_movimento', 'desc')
                ->take(5)->get(['id', 'descricao', 'valor', 'tipo', 'data_movimento'])->toArray(),
            'nucleos_maiores_participacoes' => $this->getNucleosComMaiorParticipacao($entidade, 5),
        ];
    }

    // LISTAS NÚCLEO
    private function getListasNucleo(Entidade $entidade): array
    {
        return [
            'dirigentes_recentes' => Dirigente::whereHas('vinculos', fn($q) => $q->where('entidade_id', $entidade->id))
                ->orderBy('created_at', 'desc')
                ->take(5)->get(['id', 'nome', 'created_at'])->toArray(),
            'proximos_eventos' => Evento::where('entidade_criadora_id', $entidade->id)
                ->where('data_inicio', '>=', now())
                ->orderBy('data_inicio')
                ->take(5)->get(['id', 'nome', 'data_inicio'])->toArray(),
            'ultimas_movimentacoes' => FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->orderBy('data_movimento', 'desc')
                ->take(5)->get(['id', 'descricao', 'valor', 'tipo'])->toArray(),
        ];
    }

    // LISTAS SECRETARIA
    private function getListasSecretaria(Entidade $entidade): array
    {
        return [
            'dirigentes_recentes' => Dirigente::whereHas('vinculos', fn($q) => $q->where('entidade_id', $entidade->id))
                ->orderBy('created_at', 'desc')
                ->take(5)->get(['id', 'nome', 'created_at'])->toArray(),
            'proximos_eventos' => Evento::where('entidade_criadora_id', $entidade->id)
                ->where('data_inicio', '>=', now())
                ->orderBy('data_inicio')
                ->take(5)->get(['id', 'nome', 'data_inicio'])->toArray(),
            'ultimas_movimentacoes' => FinanceiroMovimento::where('entidade_id', $entidade->id)
                ->orderBy('data_movimento', 'desc')
                ->take(5)->get(['id', 'descricao', 'valor', 'tipo'])->toArray(),
        ];
    }

    // DADOS PARA GRÁFICOS
    public function getFluxoFinanceiroMensal(?Entidade $entidade): array
    {
        $meses = [];
        $entradas = [];
        $saidas = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $label = $mes->translatedFormat('M');
            $meses[] = $label;

            $query_entrada = FinanceiroMovimento::where('tipo', 'entrada')
                ->whereYear('data_movimento', $mes->year)
                ->whereMonth('data_movimento', $mes->month);

            $query_saida = FinanceiroMovimento::where('tipo', 'saida')
                ->whereYear('data_movimento', $mes->year)
                ->whereMonth('data_movimento', $mes->month);

            if ($entidade) {
                $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id');
                $filhos->push($entidade->id);
                $query_entrada->whereIn('entidade_id', $filhos);
                $query_saida->whereIn('entidade_id', $filhos);
            }

            $entradas[] = $query_entrada->sum('valor');
            $saidas[] = $query_saida->sum('valor');
        }

        return [
            'labels' => $meses,
            'entradas' => $entradas,
            'saidas' => $saidas,
        ];
    }

    public function getEventosPorStatus(?Entidade $entidade): array
    {
        $query = Evento::query();

        if ($entidade) {
            $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id');
            $filhos->push($entidade->id);
            $query->whereIn('entidade_criadora_id', $filhos);
        }

        return [
            'publicado' => $query->where('status', 'publicado')->count(),
            'rascunho' => $query->where('status', 'rascunho')->count(),
            'encerrado' => $query->where('status', 'encerrado')->count(),
            'cancelado' => $query->where('status', 'cancelado')->count(),
        ];
    }

    public function getDirigentesPorCargo(?Entidade $entidade): array
    {
        $query = Dirigente::selectRaw('dirigente_entidades.cargo, COUNT(DISTINCT dirigentes.id) as total')
            ->join('dirigente_entidades', 'dirigentes.id', '=', 'dirigente_entidades.dirigente_id')
            ->groupBy('dirigente_entidades.cargo');

        if ($entidade) {
            $filhos = Entidade::where('entidade_pai_id', $entidade->id)->pluck('id');
            $filhos->push($entidade->id);
            $query->whereIn('dirigente_entidades.entidade_id', $filhos);
        }

        $result = $query->get()->pluck('total', 'cargo')->toArray();

        return [
            'cargos' => array_keys($result),
            'totais' => array_values($result),
        ];
    }

    private function getEntidadesComMaiorSaldo(int $limit = 5): array
    {
        $entidades = Entidade::all();
        $saldos = [];

        foreach ($entidades as $ent) {
            $saldo = FinanceiroMovimento::where('entidade_id', $ent->id)
                ->where('tipo', 'entrada')->sum('valor') -
                FinanceiroMovimento::where('entidade_id', $ent->id)
                ->where('tipo', 'saida')->sum('valor');
            $saldos[$ent->id] = ['nome' => $ent->nome, 'saldo' => $saldo];
        }

        uasort($saldos, fn($a, $b) => $b['saldo'] <=> $a['saldo']);

        return array_slice($saldos, 0, $limit);
    }

    private function getEntidadesComMaisEventos(int $limit = 5): array
    {
        return Entidade::withCount('eventosCriados')
            ->orderBy('eventos_criados_count', 'desc')
            ->take($limit)
            ->get(['id', 'nome'])
            ->map(fn($e) => ['id' => $e->id, 'nome' => $e->nome, 'eventos' => $e->eventos_criados_count])
            ->toArray();
    }

    private function getNucleosComMaiorParticipacao(Entidade $diocese, int $limit = 5): array
    {
        return Entidade::where('entidade_pai_id', $diocese->id)
            ->where('tipo_entidade', 'nucleo')
            ->get()
            ->map(function ($nucleo) {
                $participacoes = Dirigente::whereHas('vinculos', fn($q) => $q->where('entidade_id', $nucleo->id))->count();
                return ['id' => $nucleo->id, 'nome' => $nucleo->nome, 'participacoes' => $participacoes];
            })
            ->sortByDesc('participacoes')
            ->take($limit)
            ->values()
            ->toArray();
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
