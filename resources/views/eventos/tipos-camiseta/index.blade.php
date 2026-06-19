@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tipos de Camiseta</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerenciar camisetas para {{ $evento->nome }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('evento-tipos-camiseta.create', $evento) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Tipo
                </a>
                <a href="{{ route('eventos.show', $evento) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-500/15 dark:border-green-500 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Content -->
    @if ($tiposCamiseta && $tiposCamiseta->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tiposCamiseta as $tipo)
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] overflow-hidden hover:shadow-lg transition">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $tipo->fornecedor->nome }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fornecedor de Camisetas</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium @if($tipo->ativo) bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 @endif">
                            {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>

                <!-- Types List -->
                <div class="p-6">
                    @if ($tipo->fornecedor->tipos->count() > 0)
                        <div class="space-y-2 mb-6">
                            @foreach ($tipo->fornecedor->tipos->where('ativo', true) as $tipoF)
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $tipoF->tipo_camiseta }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $tipoF->tamanhos->count() }} tamanho(s)
                                </p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-500/10 rounded-lg mb-6 border border-yellow-200 dark:border-yellow-500/30">
                            <p class="text-sm text-yellow-800 dark:text-yellow-400">Nenhum tipo de camiseta cadastrado para este fornecedor</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900/50 flex gap-2">
                    <a href="{{ route('evento-tipos-camiseta.edit', [$evento, $tipo]) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg text-blue-600 hover:bg-blue-50 px-3 py-2 text-sm font-medium dark:hover:bg-blue-500/10 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('evento-tipos-camiseta.destroy', [$evento, $tipo]) }}" method="POST" class="inline" @submit.prevent="deleteItem($event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg text-red-600 hover:bg-red-50 px-3 py-2 text-sm font-medium dark:hover:bg-red-500/10 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Remover
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white p-12 text-center dark:border-white/[0.05] dark:bg-white/[0.03]">
            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8-4m0 0l8 4m-8 4v10m-3-3h6"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhum tipo de camiseta vinculado</p>
            <a href="{{ route('evento-tipos-camiseta.create', $evento) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar Primeiro Tipo
            </a>
        </div>
    @endif
</div>

<script>
    function deleteItem(event) {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Confirmar exclusão',
            text: 'Tem certeza que deseja remover este tipo de camiseta?',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Remover',
            cancelButtonText: 'Cancelar',
            zIndex: 99999
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }
</script>
@endsection
