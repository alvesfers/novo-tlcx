@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Movimentações de Estoque</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('almoxarifado-movimentos.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar Item</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome do item..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo</label>
                    <select
                        name="tipo"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos os tipos</option>
                        <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                        <option value="saida" {{ request('tipo') === 'saida' ? 'selected' : '' }}>Saída</option>
                        <option value="ajuste" {{ request('tipo') === 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search') || request('tipo'))
                    <a href="{{ route('almoxarifado-movimentos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Desktop/Tablet View -->
    <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Movimentações de Estoque
                </h3>
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Item</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Quantidade</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Data</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Responsável</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimentos as $movimento)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="px-6 py-3.5 text-sm text-gray-800 dark:text-gray-100">{{ $movimento->item->nome }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium"
                                @if($movimento->tipo_movimento->value === 'entrada') class="bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500"
                                @elseif($movimento->tipo_movimento->value === 'saida') class="bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500"
                                @else class="bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500" @endif>
                                {{ $movimento->tipo_movimento->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->quantidade }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->data_movimento->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->responsavel->name ?? 'Sistema' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"/>
                                </svg>
                                <p class="font-medium">Nenhuma movimentação encontrada</p>
                                <p class="text-sm text-gray-400">Nenhum registro de movimentação</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @forelse($movimentos as $movimento)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $movimento->item->nome }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ $movimento->data_movimento->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2"
                        @if($movimento->tipo_movimento->value === 'entrada') class="bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500"
                        @elseif($movimento->tipo_movimento->value === 'saida') class="bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500"
                        @else class="bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500" @endif>
                        {{ $movimento->tipo_movimento->label() }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300">Qtd: {{ $movimento->quantidade }} | Responsável: {{ $movimento->responsavel->name ?? 'Sistema' }}</p>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"/>
                    </svg>
                    <p class="font-medium">Nenhuma movimentação encontrada</p>
                    <p class="text-sm">Nenhum registro de movimentação</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($movimentos->hasPages())
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-white/[0.05]">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Mostrando {{ $movimentos->firstItem() }} a {{ $movimentos->lastItem() }} de {{ $movimentos->total() }} resultados
        </div>
        <div>
            {{ $movimentos->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>
@endsection
