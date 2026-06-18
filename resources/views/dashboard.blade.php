@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    <!-- Indicadores Principais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Entidades -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Entidades</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $resumo['indicadores']['total_entidades'] }}</p>
                </div>
                <div class="text-blue-600 opacity-20">
                    <x-heroicon-o-chart-bar class="w-16 h-16" />
                </div>
            </div>
        </div>

        <!-- Dirigentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Dirigentes</p>
                    <p class="text-3xl font-bold text-green-600">{{ $resumo['dirigentes']['total'] }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['dirigentes']['ativos'] }} ativos</p>
                </div>
                <div class="text-green-600 opacity-20">
                    <x-heroicon-o-users class="w-16 h-16" />
                </div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Eventos</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $resumo['indicadores']['total_eventos'] }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $resumo['indicadores']['eventos_proximos'] }} próximos</p>
                </div>
                <div class="text-purple-600 opacity-20">
                    <x-heroicon-o-calendar-days class="w-16 h-16" />
                </div>
            </div>
        </div>

        <!-- Saldo Financeiro -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Saldo</p>
                    <p class="text-3xl font-bold @if($resumo['financeiro']['saldo'] >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['financeiro']['saldo'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="@if($resumo['financeiro']['saldo'] >= 0) text-green-600 @else text-red-600 @endif opacity-20">
                    <x-heroicon-o-banknotes class="w-16 h-16" />
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo Financeiro -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Entradas e Saídas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Movimentações</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Entradas</span>
                    <span class="text-lg font-bold text-green-600">R$ {{ number_format($resumo['financeiro']['total_entradas'], 2, ',', '.') }}</span>
                </div>
                <div class="border-t pt-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Saídas</span>
                    <span class="text-lg font-bold text-red-600">R$ {{ number_format($resumo['financeiro']['total_saidas'], 2, ',', '.') }}</span>
                </div>
                <div class="border-t pt-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-semibold">Saldo Líquido</span>
                    <span class="text-lg font-bold @if($resumo['financeiro']['saldo'] >= 0) text-green-600 @else text-red-600 @endif">
                        R$ {{ number_format($resumo['financeiro']['saldo'], 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Eventos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Status dos Eventos</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Próximos</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">{{ $resumo['indicadores']['eventos_proximos'] ?? 0 }}</span>
                </div>
                <div class="border-t pt-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Encerrados</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full font-semibold">{{ $resumo['indicadores']['eventos_encerrados'] ?? 0 }}</span>
                </div>
                <div class="border-t pt-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pendentes</span>
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full font-semibold">{{ $resumo['eventos']['pendentes'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Eventos -->
    @if(count($resumo['proximos_eventos']) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Próximos Eventos</h2>
        <div class="space-y-3">
            @foreach($resumo['proximos_eventos'] as $evento)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $evento['nome'] }}</p>
                    <p class="text-sm text-gray-600">{{ $evento['data_inicio'] }} • {{ $evento['local'] }}</p>
                </div>
                <a href="{{ route('eventos.show', $evento['id']) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Ver</a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <p class="text-blue-800">Nenhum evento próximo nos próximos dias.</p>
    </div>
    @endif

    <!-- Últimas Movimentações -->
    @if(count($resumo['ultimas_movimentacoes']) > 0)
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
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <p class="text-blue-800">Nenhuma movimentação financeira registrada.</p>
    </div>
    @endif
</div>
@endsection
