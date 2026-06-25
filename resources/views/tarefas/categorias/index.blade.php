@extends('layouts.app')

@section('content')
<div x-data="categoriaManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Categorias de Tarefas</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('tarefa-categorias.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome da categoria..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search'))
                    <a href="{{ route('tarefa-categorias.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Desktop/Tablet Grid View -->
    <div class="hidden md:flex md:justify-end mb-4">
        <button onclick="openCreateCategoriaModal()"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Nova Categoria
        </button>
    </div>

    <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @forelse($categorias as $categoria)
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] hover:shadow-lg transition-shadow">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]" style="border-left: 4px solid {{ $categoria->cor ?? '#3b82f6' }}">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $categoria->nome }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ Str::limit($categoria->descricao, 80) }}</p>
            </div>
            <div class="px-6 py-3 flex gap-2 border-t border-gray-100 dark:border-white/[0.05]">
                <button onclick="openEditCategoriaModal({{ $categoria->id }}, '{{ addslashes($categoria->nome) }}', '{{ addslashes($categoria->descricao ?? '') }}', '{{ $categoria->cor }}')"
                   class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </button>
                <form action="{{ route('tarefa-categorias.destroy', $categoria) }}" method="POST" class="flex-1"
                      @submit.prevent="deleteItem({{ $categoria->id }}, $event)">
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
        <div class="md:col-span-3 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center">
                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium">Nenhuma categoria encontrada</p>
                <p class="text-sm text-gray-400">Crie uma nova categoria para começar</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        <div class="flex flex-col gap-2 mb-4">
            <button onclick="openCreateCategoriaModal()"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nova Categoria
            </button>
        </div>

        @forelse($categorias as $categoria)
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]" style="border-left: 4px solid {{ $categoria->cor ?? '#3b82f6' }}">
            <div class="mb-3">
                <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $categoria->nome }}</h4>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($categoria->descricao, 80) }}</p>
            </div>

            <div class="flex gap-2 mt-3">
                <button onclick="openEditCategoriaModal({{ $categoria->id }}, '{{ addslashes($categoria->nome) }}', '{{ addslashes($categoria->descricao ?? '') }}', '{{ $categoria->cor }}')"
                   class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </button>
                <form action="{{ route('tarefa-categorias.destroy', $categoria) }}" method="POST" class="flex-1"
                      @submit.prevent="deleteItem({{ $categoria->id }}, $event)">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium">Nenhuma categoria encontrada</p>
                <p class="text-sm">Crie uma nova categoria para começar</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<x-modal
    id="categoriaModal"
    title="Nova Categoria"
    submitText="Criar"
>
    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input
            type="text"
            name="nome"
            id="categoriaNome"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="nomeError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Descrição</label>
        <textarea
            name="descricao"
            id="categoriaDescricao"
            rows="3"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        ></textarea>
        <span class="text-red-500 text-sm" id="descricaoError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Cor</label>
        <input
            type="color"
            name="cor"
            id="categoriaCor"
            value="#3b82f6"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 h-10"
        >
        <span class="text-red-500 text-sm" id="corError"></span>
    </div>

    <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
</x-modal>

<script>
    let categoriaEditId = null;

    function openCreateCategoriaModal() {
        categoriaEditId = null;
        document.getElementById('categoriaModalTitle').textContent = 'Criar Nova Categoria';
        document.getElementById('categoriaSubmitBtn').textContent = 'Criar';
        document.getElementById('categoriaModalForm').reset();
        document.getElementById('categoriaCor').value = '#3b82f6';
        showModal('categoriaModal');
    }

    function openEditCategoriaModal(id, nome, descricao, cor) {
        categoriaEditId = id;
        const titleEl = document.getElementById('categoriaModalTitle');
        const submitBtn = document.getElementById('categoriaSubmitBtn');

        if (titleEl) titleEl.textContent = 'Editar Categoria';
        if (submitBtn) submitBtn.textContent = 'Atualizar';

        document.getElementById('categoriaNome').value = nome;
        document.getElementById('categoriaDescricao').value = descricao;
        document.getElementById('categoriaCor').value = cor;
        showModal('categoriaModal');
    }

    async function submitCategoriaForm(event) {
        event.preventDefault();

        const id = categoriaEditId;
        const url = id ? `/tarefa-categorias/${id}` : '/tarefa-categorias';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('categoriaModalForm'));
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

            hideModal('categoriaModal');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: id ? 'Categoria atualizada com sucesso!' : 'Categoria criada com sucesso!',
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

    function categoriaManager() {
        return {
            deleteItem(categoriaId, event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar esta categoria?',
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
                            text: 'Categoria deletada com sucesso!',
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
