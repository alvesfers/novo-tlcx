@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Dashboard Núcleo</h1>
    <p class="text-gray-600 mb-8">{{ $resumo['entidade']['nome'] ?? 'Núcleo' }}</p>

    <!-- KPIs Principais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Dirigentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dirigentes</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $resumo['kpis']['total_dirigentes'] }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Eventos</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumo['kpis']['total_eventos'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>

        <!-- Saldo Financeiro -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Saldo</p>
                    <p class="text-2xl font-bold @if($resumo['kpis']['saldo_nucleo'] >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['kpis']['saldo_nucleo'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <!-- Próximas Atividades -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Próximas Atividades</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $resumo['kpis']['proximas_atividades'] }}</p>
                </div>
                <div class="text-4xl">⏱️</div>
            </div>
        </div>
    </div>

    <!-- KPIs dos Novos Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Almoxarifado -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Itens em Estoque</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $resumo['kpis']['total_itens_estoque'] ?? 0 }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['kpis']['itens_esgotados'] ?? 0 }} esgotados</p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <!-- Tarefas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Tarefas Totais</p>
                    <p class="text-2xl font-bold text-cyan-600">{{ $resumo['kpis']['total_tarefas'] ?? 0 }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['kpis']['tarefas_vencidas'] ?? 0 }} vencidas</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>

        <!-- Documentos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Documentos</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $resumo['kpis']['total_documentos'] ?? 0 }}</p>
                </div>
                <div class="text-4xl">📄</div>
            </div>
        </div>
    </div>

    <!-- Dirigentes Recentes -->
    @if(isset($resumo['listas']['dirigentes_recentes']) && count($resumo['listas']['dirigentes_recentes']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Dirigentes Recentes</h2>
        <div class="space-y-3">
            @foreach($resumo['listas']['dirigentes_recentes'] as $dirigente)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $dirigente['nome'] ?? 'Dirigente' }}</p>
                    <p class="text-sm text-gray-500">Adicionado {{ isset($dirigente['created_at']) ? \Carbon\Carbon::parse($dirigente['created_at'])->diffForHumans() : '-' }}</p>
                </div>
                @if(isset($dirigente['id']))
                <a href="{{ route('dirigentes.show', $dirigente['id']) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Ver</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Próximos Eventos -->
    @if(isset($resumo['listas']['proximos_eventos']) && count($resumo['listas']['proximos_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Próximos Eventos</h2>
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
        <p class="text-blue-800">Nenhum evento próximo agendado.</p>
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
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <p class="text-blue-800">Nenhuma movimentação financeira registrada.</p>
    </div>
    @endif

    <!-- Gráficos -->
    @if(isset($resumo['graficos_data']))
    <div class="grid grid-cols-1 gap-8 mt-8">
        <!-- Fluxo Financeiro Mensal -->
        @if(isset($resumo['graficos_data']['fluxo_mensal']))
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Fluxo Financeiro (6 meses)</h2>
            @include('components.chart', [
                'chartId' => 'fluxo_caixa_nucleo_' . uniqid(),
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
    </div>
    @endif
</div>
@endsection
