@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Resumo Financeiro</h1>
        <a href="{{ route('financeiro-movimentos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('financeiro.resumo') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label for="data_inicio" class="block text-sm font-medium mb-2 dark:text-gray-200">Data Inicial</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="data_fim" class="block text-sm font-medium mb-2 dark:text-gray-200">Data Final</label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('data_inicio') || request('data_fim'))
                    <a href="{{ route('financeiro.resumo') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Cards Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Saldo Atual Card -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="px-6 py-6">
                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Saldo Atual</h2>
                <p class="text-4xl font-bold @if($saldoAtual >= 0) text-green-600 @else text-red-600 @endif">
                    R$ {{ number_format($saldoAtual, 2, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    @if($saldoAtual >= 0)
                        <span class="text-green-600">↑ Saldo positivo</span>
                    @else
                        <span class="text-red-600">↓ Saldo negativo</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Mês Atual Card -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="px-6 py-6">
                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-4">Mês Atual</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-white/[0.05]">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Entradas:</span>
                        <span class="font-semibold text-green-600">R$ {{ number_format($periodoAtual['entradas'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-white/[0.05]">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Saídas:</span>
                        <span class="font-semibold text-red-600">R$ {{ number_format($periodoAtual['saidas'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">Saldo:</span>
                        <span class="font-bold @if($periodoAtual['saldo'] >= 0) text-blue-600 @else text-red-600 @endif">
                            R$ {{ number_format($periodoAtual['saldo'], 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Movimentações por Categoria (Mês Atual)
            </h3>
        </div>

        <!-- Desktop/Tablet Table View -->
        <div class="max-w-full overflow-x-auto hidden md:block">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Categoria</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $cat)
                        @if($cat['total'] > 0)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-6 py-3.5 text-sm text-gray-800 dark:text-gray-100">{{ $cat['nome'] }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium @if($cat['tipo'] === 'entrada') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @else bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @endif">
                                    {{ $cat['tipo'] === 'entrada' ? 'Entrada' : 'Saída' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-sm font-semibold text-end @if($cat['tipo'] === 'entrada') text-green-700 dark:text-green-500 @else text-red-700 dark:text-red-500 @endif">
                                R$ {{ number_format($cat['total'], 2, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Nenhuma movimentação no período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-3 px-4 py-4">
            @forelse ($categorias as $cat)
                @if($cat['total'] > 0)
                <div class="bg-gray-50 dark:bg-white/[0.02] rounded-lg border border-gray-200 dark:border-white/[0.05] p-4">
                    <div class="mb-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cat['nome'] }}</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tipo</span>
                            <span class="inline-block px-2 py-1 rounded-full text-theme-xs font-medium @if($cat['tipo'] === 'entrada') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @else bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @endif">
                                {{ $cat['tipo'] === 'entrada' ? 'Entrada' : 'Saída' }}
                            </span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-white/[0.05]">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Total</span>
                            <span class="text-sm font-bold @if($cat['tipo'] === 'entrada') text-green-700 dark:text-green-500 @else text-red-700 dark:text-red-500 @endif">
                                R$ {{ number_format($cat['total'], 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma movimentação no período</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
