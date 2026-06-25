@extends('layouts.app')

@section('content')
<div x-data="itemManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Itens de Estoque</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('almoxarifado-itens.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome do item..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Categoria</label>
                    <select
                        name="categoria_id"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todas as categorias</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
                    <select
                        name="status"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos os status</option>
                        <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        <option value="esgotado" {{ request('status') === 'esgotado' ? 'selected' : '' }}>Esgotado</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search') || request('categoria_id') || request('status'))
                    <a href="{{ route('almoxarifado-itens.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
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
                    Itens de Estoque
                </h3>
            </div>
            <button onclick="openCreateItemModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Novo Item
            </button>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Nome</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Quantidade</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Mínimo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itens as $item)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="px-6 py-3.5 text-sm text-gray-800 dark:text-gray-100 font-medium">
                            {{ $item->nome }}
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $item->quantidade_atual }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $item->quantidade_minima ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium"
                                @if($item->status->value === 'ativo') class="bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500"
                                @elseif($item->status->value === 'esgotado') class="bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500"
                                @else class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400" @endif>
                                {{ $item->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <button onclick="openEditItemModal({{ $item->id }}, '{{ addslashes($item->nome) }}', {{ $item->almoxarifado_categoria_id }}, '{{ $item->unidade_medida }}', {{ $item->quantidade_minima }}, '{{ $item->status->value }}')"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('almoxarifado-itens.destroy', $item) }}" method="POST" class="inline"
                                      @submit.prevent="deleteItem({{ $item->id }}, $event)">
                                    @csrf @method('DELETE')
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
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4"/>
                                </svg>
                                <p class="font-medium">Nenhum item encontrado</p>
                                <p class="text-sm text-gray-400">Crie um novo item para começar</p>
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
        @if(count($itens) > 0)
            <div class="flex flex-col gap-2">
                <button onclick="openCreateItemModal()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Novo Item
                </button>
            </div>
        @endif

        @forelse($itens as $item)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $item->nome }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">Qtd: {{ $item->quantidade_atual }} | Mín: {{ $item->quantidade_minima ?? '-' }}</p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2"
                        @if($item->status->value === 'ativo') class="bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500"
                        @elseif($item->status->value === 'esgotado') class="bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500"
                        @else class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400" @endif>
                        {{ $item->status->label() }}
                    </span>
                </div>

                <div class="flex gap-2 mt-3">
                    <button onclick="openEditItemModal({{ $item->id }}, '{{ addslashes($item->nome) }}', {{ $item->almoxarifado_categoria_id }}, '{{ $item->unidade_medida }}', {{ $item->quantidade_minima }}, '{{ $item->status->value }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    <form action="{{ route('almoxarifado-itens.destroy', $item) }}" method="POST" class="flex-1"
                          @submit.prevent="deleteItem({{ $item->id }}, $event)">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Deletar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l8 4m0 0l8-4"/>
                    </svg>
                    <p class="font-medium">Nenhum item encontrado</p>
                    <p class="text-sm">Crie um novo item para começar</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<x-modal
    id="itemModal"
    title="Novo Item"
    submitText="Criar"
>
    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input
            type="text"
            name="nome"
            id="itemNome"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="nomeError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Categoria</label>
        <select
            name="almoxarifado_categoria_id"
            id="itemCategoria"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
            <option value="">Selecione uma categoria</option>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
            @endforeach
        </select>
        <span class="text-red-500 text-sm" id="almoxarifado_categoria_idError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Unidade de Medida</label>
        <select
            name="unidade_medida"
            id="itemUnidade"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
            <option value="unidade">Unidade</option>
            <option value="caixa">Caixa</option>
            <option value="pacote">Pacote</option>
            <option value="litro">Litro</option>
            <option value="metro">Metro</option>
            <option value="kg">Kg</option>
        </select>
        <span class="text-red-500 text-sm" id="unidade_medidaError"></span>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Quantidade Mínima</label>
            <input
                type="number"
                name="quantidade_minima"
                id="itemQtdMin"
                step="0.01"
                min="0"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="quantidade_minimaError"></span>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
            <select
                name="status"
                id="itemStatus"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
            <span class="text-red-500 text-sm" id="statusError"></span>
        </div>
    </div>

    <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
</x-modal>

<script>
    let itemEditId = null;

    function openCreateItemModal() {
        itemEditId = null;
        document.getElementById('itemModalTitle').textContent = 'Criar Novo Item';
        document.getElementById('itemSubmitBtn').textContent = 'Criar';
        document.getElementById('itemModalForm').reset();
        showModal('itemModal');
    }

    function openEditItemModal(id, nome, categoriaId, unidade, qtdMin, status) {
        itemEditId = id;
        const titleEl = document.getElementById('itemModalTitle');
        const submitBtn = document.getElementById('itemSubmitBtn');

        if (titleEl) titleEl.textContent = 'Editar Item';
        if (submitBtn) submitBtn.textContent = 'Atualizar';

        document.getElementById('itemNome').value = nome;
        document.getElementById('itemCategoria').value = categoriaId;
        document.getElementById('itemUnidade').value = unidade;
        document.getElementById('itemQtdMin').value = qtdMin;
        document.getElementById('itemStatus').value = status;
        showModal('itemModal');
    }

    async function submitItemForm(event) {
        event.preventDefault();

        const id = itemEditId;
        const url = id ? `/almoxarifado-itens/${id}` : '/almoxarifado-itens';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('itemModalForm'));
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.errors) {
                    Object.keys(errorData.errors).forEach(key => {
                        const errorEl = document.getElementById(key + 'Error');
                        if (errorEl) {
                            errorEl.textContent = errorData.errors[key][0];
                        }
                    });
                }
                return;
            }

            hideModal('itemModal');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: id ? 'Item atualizado com sucesso!' : 'Item criado com sucesso!',
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                window.location.reload();
            });
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao processar o formulário',
            });
        }
    }

    function itemManager() {
        return {
            deleteItem(itemId, event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar este item?',
                    icon: 'warning',
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
                            text: 'Item deletado com sucesso!',
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
@endsection
