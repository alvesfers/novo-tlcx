@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    @if(isset($resumo['tipo_usuario']))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <p class="text-blue-800">Dashboard carregado com sucesso</p>
    </div>
    @else
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
        <p class="text-gray-800">Bem-vindo ao Sistema TLC Admin</p>
    </div>
    @endif

    <!-- Indicadores Principais -->
    @if(isset($resumo['indicadores']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Entidades -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Entidades</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $resumo['indicadores']['total_entidades'] ?? 0 }}</p>
                </div>
                <div class="text-blue-100 text-4xl">📊</div>
            </div>
        </div>

        <!-- Dirigentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dirigentes</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumo['dirigentes']['total'] ?? 0 }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['dirigentes']['ativos'] ?? 0 }} ativos</p>
                </div>
                <div class="text-green-100 text-4xl">👥</div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Eventos</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $resumo['indicadores']['total_eventos'] ?? 0 }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['indicadores']['eventos_proximos'] ?? 0 }} próximos</p>
                </div>
                <div class="text-purple-100 text-4xl">📅</div>
            </div>
        </div>

        <!-- Saldo Financeiro -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Saldo</p>
                    <p class="text-3xl font-bold @if(($resumo['financeiro']['saldo'] ?? 0) >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['financeiro']['saldo'] ?? 0, 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-yellow-100 text-4xl">💰</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Próximos Eventos -->
    @if(isset($resumo['proximos_eventos']) && count($resumo['proximos_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Próximos Eventos</h2>
        <div class="space-y-3">
            @foreach($resumo['proximos_eventos'] as $evento)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $evento['nome'] }}</p>
                    <p class="text-sm text-gray-600">{{ $evento['data_inicio'] }} • {{ $evento['local'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Últimas Movimentações -->
    @if(isset($resumo['ultimas_movimentacoes']) && count($resumo['ultimas_movimentacoes']) > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Últimas Movimentações Financeiras</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Descrição</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Data</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumo['ultimas_movimentacoes'] as $mov)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $mov['descricao'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-sm font-semibold @if($mov['tipo'] === 'entrada') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $mov['tipo'] === 'entrada' ? 'Entrada' : 'Saída' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $mov['data'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold @if($mov['tipo'] === 'entrada') text-green-600 @else text-red-600 @endif">
                            @if($mov['tipo'] === 'entrada') + @else - @endif R$ {{ number_format($mov['valor'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
