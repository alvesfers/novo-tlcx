@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
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
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                @if($movimento->tipo_movimento->value === 'entrada') class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                @elseif($movimento->tipo_movimento->value === 'saida') class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                                @else class="bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200" @endif>
                                {{ $movimento->tipo_movimento->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->quantidade }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->data_movimento->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $movimento->responsavel->name ?? 'Sistema' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-sm">Nenhuma movimentação encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $movimentos->links() }}
    </div>
</div>
@endsection
