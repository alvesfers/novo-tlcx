@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Vendas do Barzinho</h1>
                <p class="text-gray-600 dark:text-gray-400">Histórico de vendas de {{ $barzinho->nome }}</p>
            </div>
            <a href="{{ route('barzinhos.show', $barzinho) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-500/15 dark:border-green-500 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] overflow-hidden">
        <div class="overflow-x-auto">
            @if ($vendas && $vendas->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Data</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Participante</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Pagamento</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendas as $venda)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $venda->participante->nome ?? 'Desconhecido' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-200">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $venda->forma_pagamento ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium @if($venda->status === 'confirmada') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @elseif($venda->status === 'cancelada') bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500 @endif">
                                    {{ $venda->status ?? 'Pendente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('barzinhos.vendas.show', [$barzinho, $venda]) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma venda registrada</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
