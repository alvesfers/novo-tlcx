@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Detalhes da Venda</h1>
                <p class="text-gray-600 dark:text-gray-400">Informações completas da venda #{{ $venda->id }}</p>
            </div>
            <a href="{{ route('barzinhos.vendas.index', $barzinho) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Participante</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $venda->participante->nome ?? 'Desconhecido' }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Data da Venda</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $venda->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">R$ {{ number_format($venda->total, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03] mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações da Venda</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Forma de Pagamento</p>
                <p class="text-gray-900 dark:text-white font-medium">{{ $venda->forma_pagamento ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium @if($venda->status === 'confirmada') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @elseif($venda->status === 'cancelada') bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500 @endif">
                    {{ $venda->status ?? 'Pendente' }}
                </span>
            </div>
        </div>
    </div>

    @if ($venda->itens && $venda->itens()->count() > 0)
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Itens da Venda</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Produto</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Quantidade</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Preço Unit.</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venda->itens() as $item)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $item->produto->nome }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->quantidade }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-200">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
