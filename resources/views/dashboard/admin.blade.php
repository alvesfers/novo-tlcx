@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Dashboard Administrativo</h1>
    <p class="text-gray-600 mb-8">Visão consolidada de todo o sistema</p>

    <!-- KPIs Globais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Dioceses -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dioceses</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $resumo['kpis']['total_dioceses'] }}</p>
                </div>
                <div class="text-4xl">⛪</div>
            </div>
        </div>

        <!-- Núcleos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Núcleos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumo['kpis']['total_nucleos'] }}</p>
                </div>
                <div class="text-4xl">🏢</div>
            </div>
        </div>

        <!-- Secretarias -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Secretarias</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $resumo['kpis']['total_secretarias'] }}</p>
                </div>
                <div class="text-4xl">📋</div>
            </div>
        </div>

        <!-- Saldo Global -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Saldo Global</p>
                    <p class="text-3xl font-bold @if($resumo['kpis']['saldo_global'] >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['kpis']['saldo_global'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>
    </div>

    <!-- Segunda Linha de KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Dirigentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dirigentes</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $resumo['kpis']['total_dirigentes'] }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Eventos</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $resumo['kpis']['total_eventos'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>

        <!-- Usuários -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Usuários</p>
                    <p class="text-2xl font-bold text-pink-600">{{ $resumo['kpis']['total_usuarios'] }}</p>
                </div>
                <div class="text-4xl">🔐</div>
            </div>
        </div>
    </div>

    <!-- Últimos Eventos -->
    @if(isset($resumo['listas']['ultimos_eventos']) && count($resumo['listas']['ultimos_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Últimos Eventos Criados</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['ultimos_eventos'] as $evento)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $evento['nome'] ?? 'Evento' }}</p>
                    <p class="text-sm text-gray-500">{{ isset($evento['created_at']) ? \Carbon\Carbon::parse($evento['created_at'])->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Últimas Movimentações Financeiras -->
    @if(isset($resumo['listas']['ultimas_movimentacoes']) && count($resumo['listas']['ultimas_movimentacoes']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Últimas Movimentações Financeiras</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Descrição</th>
                        <th class="px-4 py-2 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-right font-semibold">Valor</th>
                        <th class="px-4 py-2 text-left font-semibold">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumo['listas']['ultimas_movimentacoes'] as $mov)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $mov['descricao'] ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold @if(($mov['tipo'] ?? '') === 'entrada') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ ($mov['tipo'] ?? '') === 'entrada' ? 'Entrada' : 'Saída' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right @if(($mov['tipo'] ?? '') === 'entrada') text-green-600 @else text-red-600 @endif font-semibold">
                            R$ {{ number_format($mov['valor'] ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm">{{ isset($mov['data_movimento']) ? \Carbon\Carbon::parse($mov['data_movimento'])->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Entidades com Maior Saldo -->
    @if(isset($resumo['listas']['entidades_maiores_saldos']) && count($resumo['listas']['entidades_maiores_saldos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Entidades com Maior Saldo</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['entidades_maiores_saldos'] as $entidade)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="font-semibold text-gray-800">{{ $entidade['nome'] ?? 'Entidade' }}</span>
                <span class="font-bold @if($entidade['saldo'] >= 0) text-green-600 @else text-red-600 @endif">
                    R$ {{ number_format($entidade['saldo'] ?? 0, 2, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Entidades com Mais Eventos -->
    @if(isset($resumo['listas']['entidades_mais_eventos']) && count($resumo['listas']['entidades_mais_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Entidades com Mais Eventos</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['entidades_mais_eventos'] as $entidade)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="font-semibold text-gray-800">{{ $entidade['nome'] ?? 'Entidade' }}</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold text-sm">{{ $entidade['eventos'] ?? 0 }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Gráficos -->
    @if(isset($resumo['graficos_data']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Fluxo Financeiro Mensal -->
        @if(isset($resumo['graficos_data']['fluxo_mensal']))
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Fluxo Financeiro (6 meses)</h2>
            @include('components.chart', [
                'chartId' => 'fluxo_caixa_' . uniqid(),
                'title' => 'Entradas vs Saídas',
                'config' => [
                    'type' => 'line',
                    'labels' => array_column($resumo['graficos_data']['fluxo_mensal'], 'mes'),
                    'datasets' => [
                        [
                            'label' => 'Entradas',
                            'data' => array_column($resumo['graficos_data']['fluxo_mensal'], 'entradas'),
                            'borderColor' => '#10b981',
                            'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                            'tension' => 0.4,
                        ],
                        [
                            'label' => 'Saídas',
                            'data' => array_column($resumo['graficos_data']['fluxo_mensal'], 'saidas'),
                            'borderColor' => '#ef4444',
                            'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                            'tension' => 0.4,
                        ],
                    ],
                ]
            ])
        </div>
        @endif

        <!-- Eventos por Status -->
        @if(isset($resumo['graficos_data']['eventos_por_status']))
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Distribuição de Eventos por Status</h2>
            @include('components.chart', [
                'chartId' => 'eventos_status_' . uniqid(),
                'title' => 'Status',
                'config' => [
                    'type' => 'doughnut',
                    'labels' => ['Publicado', 'Rascunho', 'Encerrado', 'Cancelado'],
                    'datasets' => [
                        [
                            'data' => [
                                $resumo['graficos_data']['eventos_por_status']['publicado'] ?? 0,
                                $resumo['graficos_data']['eventos_por_status']['rascunho'] ?? 0,
                                $resumo['graficos_data']['eventos_por_status']['encerrado'] ?? 0,
                                $resumo['graficos_data']['eventos_por_status']['cancelado'] ?? 0,
                            ],
                            'backgroundColor' => ['#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                        ],
                    ],
                ]
            ])
        </div>
        @endif

        <!-- Dirigentes por Cargo -->
        @if(isset($resumo['graficos_data']['dirigentes_por_cargo']))
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Dirigentes por Cargo</h2>
            @include('components.chart', [
                'chartId' => 'dirigentes_cargo_' . uniqid(),
                'title' => 'Distribuição',
                'config' => [
                    'type' => 'bar',
                    'labels' => $resumo['graficos_data']['dirigentes_por_cargo']['cargos'] ?? [],
                    'datasets' => [
                        [
                            'label' => 'Quantidade',
                            'data' => $resumo['graficos_data']['dirigentes_por_cargo']['totais'] ?? [],
                            'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
                        ],
                    ],
                ]
            ])
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
