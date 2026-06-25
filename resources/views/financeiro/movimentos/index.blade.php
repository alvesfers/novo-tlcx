@extends('layouts.app')

@section('content')
<div x-data="movimentosManager()" class="container mx-auto px-4 py-8" id="movimentosContainer">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Movimentações Financeiras</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('financeiro-movimentos.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label for="data_inicio" class="block text-sm font-medium mb-2 dark:text-gray-200">Data Inicial</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="data_fim" class="block text-sm font-medium mb-2 dark:text-gray-200">Data Final</label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="tipo" class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo</label>
                    <select id="tipo" name="tipo" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todos</option>
                        <option value="entrada" @selected(request('tipo') === 'entrada')>Entrada</option>
                        <option value="saida" @selected(request('tipo') === 'saida')>Saída</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="categoria_id" class="block text-sm font-medium mb-2 dark:text-gray-200">Categoria</label>
                    <select id="categoria_id" name="categoria_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todas</option>
                        @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}" @selected(request('categoria_id') === (string)$cat->id)>{{ $cat->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('data_inicio') || request('data_fim') || request('tipo') || request('categoria_id'))
                    <a href="{{ route('financeiro-movimentos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Movimentações Financeiras
                </h3>
            </div>
            <button onclick="openModal('movimentoModal', false)"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Novo Movimento
            </button>
        </div>

        <!-- Desktop/Tablet Table View -->
        <div class="max-w-full overflow-x-auto hidden md:block">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Descrição</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Valor</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Data</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimentos as $movimento)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $movimento->descricao }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @if($movimento->tipo->value === 'entrada')
                                        bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else
                                        bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                                    @endif">
                                    {{ $movimento->tipo->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">R$ {{ number_format($movimento->valor, 2, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $movimento->data_movimento->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <button onclick="openModal('movimentoModal', true, {
                                        id: {{ $movimento->id }},
                                        descricao: '{{ addslashes($movimento->descricao) }}',
                                        tipo: '{{ $movimento->tipo->value }}',
                                        data_movimento: '{{ $movimento->data_movimento->format('Y-m-d') }}',
                                        financeiro_categoria_id: {{ $movimento->financeiro_categoria_id }},
                                        valor: {{ $movimento->valor }},
                                        forma_pagamento: '{{ $movimento->forma_pagamento->value }}',
                                        comprovante_url: '{{ addslashes($movimento->comprovante_url ?? '') }}',
                                        observacao: '{{ addslashes($movimento->observacao ?? '') }}'
                                    })"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('financeiro-movimentos.destroy', $movimento) }}" method="POST" class="inline"
                                          @submit.prevent="deleteItem({{ $movimento->id }}, $event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                                title="Deletar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Nenhuma movimentação encontrada
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 px-2">
            @forelse($movimentos as $movimento)
                <div class="bg-white dark:bg-white/[0.03] rounded-lg border border-gray-200 dark:border-white/[0.05] p-4">
                    <div class="mb-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $movimento->descricao }}</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tipo</span>
                            <span class="inline-block px-2 py-1 rounded-full text-theme-xs font-medium
                                @if($movimento->tipo->value === 'entrada')
                                    bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                @else
                                    bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                                @endif">
                                {{ $movimento->tipo->label() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Valor</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">R$ {{ number_format($movimento->valor, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Data</span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $movimento->data_movimento->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-white/[0.05]">
                        <button onclick="openModal('movimentoModal', true, {
                            id: {{ $movimento->id }},
                            descricao: '{{ addslashes($movimento->descricao) }}',
                            tipo: '{{ $movimento->tipo->value }}',
                            data_movimento: '{{ $movimento->data_movimento->format('Y-m-d') }}',
                            financeiro_categoria_id: {{ $movimento->financeiro_categoria_id }},
                            valor: {{ $movimento->valor }},
                            forma_pagamento: '{{ $movimento->forma_pagamento->value }}',
                            comprovante_url: '{{ addslashes($movimento->comprovante_url ?? '') }}',
                            observacao: '{{ addslashes($movimento->observacao ?? '') }}'
                        })"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg text-amber-600 bg-amber-50 px-3 py-2 text-sm font-medium hover:bg-amber-100 dark:bg-amber-500/10 dark:hover:bg-amber-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                        <form action="{{ route('financeiro-movimentos.destroy', $movimento) }}" method="POST" class="flex-1"
                              @submit.prevent="deleteItem({{ $movimento->id }}, $event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg text-red-600 bg-red-50 px-3 py-2 text-sm font-medium hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Deletar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhuma movimentação encontrada</p>
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

    <!-- Modal -->
    <x-modal
        id="movimentoModal"
        title="Criar Novo Movimento"
        resource="financeiro-movimentos"
        size="md"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data *</label>
            <input
                type="date"
                name="data_movimento"
                id="movimentoModaldata_movimento"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="movimentoModaldata_movimentoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo *</label>
            <select
                name="tipo"
                id="movimentoModaltipo"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione...</option>
                <option value="entrada">Entrada (Receita)</option>
                <option value="saida">Saída (Despesa)</option>
            </select>
            <span class="text-red-500 text-sm" id="movimentoModaltipoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Categoria *</label>
            <select
                name="financeiro_categoria_id"
                id="movimentoModalfinanceiro_categoria_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione...</option>
                @foreach ($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm" id="movimentoModalfinanceiro_categoria_idError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Descrição *</label>
            <input
                type="text"
                name="descricao"
                id="movimentoModaldescricao"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="movimentoModaldescricaoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Valor *</label>
            <input
                type="number"
                name="valor"
                id="movimentoModalvalor"
                step="0.01"
                min="0"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="movimentoModalvalorError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Forma de Pagamento *</label>
            <select
                name="forma_pagamento"
                id="movimentoModalforma_pagamento"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione...</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="pix">PIX</option>
                <option value="transferencia">Transferência</option>
                <option value="cartao">Cartão</option>
                <option value="cheque">Cheque</option>
                <option value="outro">Outro</option>
            </select>
            <span class="text-red-500 text-sm" id="movimentoModalforma_pagamentoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">URL do Comprovante</label>
            <input
                type="url"
                name="comprovante_url"
                id="movimentoModalcomprovante_url"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="https://..."
            >
            <span class="text-red-500 text-sm" id="movimentoModalcomprovante_urlError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Observação</label>
            <textarea
                name="observacao"
                id="movimentoModalobservacao"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            ></textarea>
            <span class="text-red-500 text-sm" id="movimentoModalobservacaoError"></span>
        </div>
    </x-modal>

    <script>
        function movimentosManager() {
            return {
                deleteItem(movimentoId, event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja deletar este movimento?',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Deletar',
                        cancelButtonText: 'Cancelar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            document.querySelector('.swal2-container').style.zIndex = '99999';
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Movimento deletado com sucesso!',
                                showConfirmButton: false,
                                timer: 1500,
                            }).then(() => {
                                event.target.closest('form').submit();
                            });
                        }
                    });
                }
            };
        }
    </script>
</div>
@endsection
