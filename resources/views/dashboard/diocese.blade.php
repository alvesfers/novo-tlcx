@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Dashboard Diocese</h1>
    <p class="text-gray-600 mb-8">{{ $resumo['entidade']['nome'] ?? 'Diocese' }}</p>

    <!-- Estrutura da Diocese -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Núcleos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Núcleos</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $resumo['kpis']['total_nucleos'] }}</p>
                </div>
                <div class="text-4xl">🏢</div>
            </div>
        </div>

        <!-- Secretarias -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Secretarias</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumo['kpis']['total_secretarias'] }}</p>
                </div>
                <div class="text-4xl">📋</div>
            </div>
        </div>

        <!-- Dirigentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dirigentes</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $resumo['kpis']['total_dirigentes'] }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
    </div>

    <!-- Financeiro -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Resumo Financeiro -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Financeiro</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <span class="text-gray-600">Entradas</span>
                    <span class="font-bold text-green-600">R$ {{ number_format($resumo['kpis']['saldo_diocese'] > 0 ? abs($resumo['kpis']['saldo_diocese']) : 0, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                    <span class="text-gray-600">Saídas</span>
                    <span class="font-bold text-red-600">R$ 0,00</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded border-2 border-blue-200">
                    <span class="text-gray-700 font-semibold">Saldo Total</span>
                    <span class="font-bold text-lg @if($resumo['kpis']['saldo_diocese'] >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['kpis']['saldo_diocese'], 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Eventos</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <span class="text-gray-600">Total Eventos</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">{{ $resumo['kpis']['total_eventos'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Eventos -->
    @if(isset($resumo['listas']['proximos_eventos']) && count($resumo['listas']['proximos_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Próximos Eventos (7 dias)</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['proximos_eventos'] as $evento)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $evento['nome'] ?? 'Evento' }}</p>
                    <p class="text-sm text-gray-500">{{ isset($evento['data_inicio']) ? \Carbon\Carbon::parse($evento['data_inicio'])->format('d/m/Y') : '-' }}</p>
                </div>
                @if(isset($evento['id']))
                <a href="{{ route('eventos.show', $evento['id']) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Ver</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <p class="text-blue-800">Nenhum evento próximo nos próximos 7 dias.</p>
    </div>
    @endif

    <!-- Últimas Movimentações -->
    @if(isset($resumo['listas']['ultimas_movimentacoes']) && count($resumo['listas']['ultimas_movimentacoes']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Últimas Movimentações</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Descrição</th>
                        <th class="px-4 py-2 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-right font-semibold">Valor</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Núcleos com Maior Participação -->
    @if(isset($resumo['listas']['nucleos_maiores_participacoes']) && count($resumo['listas']['nucleos_maiores_participacoes']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Núcleos com Maior Participação</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['nucleos_maiores_participacoes'] as $nucleo)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="font-semibold text-gray-800">{{ $nucleo['nome'] ?? 'Núcleo' }}</span>
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full font-semibold text-sm">{{ $nucleo['participacoes'] ?? 0 }} dirigentes</span>
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
                'chartId' => 'fluxo_caixa_diocese_' . uniqid(),
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
            <h2 class="text-xl font-bold mb-4">Distribuição de Eventos</h2>
            @include('components.chart', [
                'chartId' => 'eventos_status_diocese_' . uniqid(),
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
    </div>
    @endif
</div>
@endsection
