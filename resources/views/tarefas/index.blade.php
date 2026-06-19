@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tarefas</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('tarefas.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Título da tarefa..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
                    <select
                        name="filter"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos</option>
                        <option value="pendentes" {{ request('filter') === 'pendentes' ? 'selected' : '' }}>Pendentes</option>
                        <option value="em_andamento" {{ request('filter') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="concluidas" {{ request('filter') === 'concluidas' ? 'selected' : '' }}>Concluídas</option>
                        <option value="vencidas" {{ request('filter') === 'vencidas' ? 'selected' : '' }}>Vencidas</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search') || request('filter'))
                    <a href="{{ route('tarefas.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Desktop/Tablet View (≥768px) -->
    <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Tarefas
                </h3>
            </div>
            <button onclick="openCreateTarefaModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Nova Tarefa
            </button>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Título</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Prioridade</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Data Limite</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarefas as $tarefa)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="px-6 py-3.5 text-sm text-gray-800 dark:text-gray-100 font-medium">
                            {{ $tarefa->titulo }}
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                @if($tarefa->status->value === 'pendente') bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500
                                @elseif($tarefa->status->value === 'em_andamento') bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500
                                @elseif($tarefa->status->value === 'concluida') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                @else bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @endif">
                                {{ $tarefa->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                @if($tarefa->prioridade->value === 'urgente') bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                                @elseif($tarefa->prioridade->value === 'alta') bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500
                                @elseif($tarefa->prioridade->value === 'media') bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500
                                @else bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @endif">
                                {{ $tarefa->prioridade->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">
                            @if($tarefa->data_limite)
                                {{ $tarefa->data_limite->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                @if($tarefa->status->value !== 'concluida')
                                <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST" class="inline" @submit.prevent="completarTarefa($event, {{ $tarefa->id }})">
                                    @csrf
                                    <button type="submit"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-500/10"
                                       title="Concluir">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                <button onclick="openEditTarefaModal({{ $tarefa->id }}, '{{ addslashes($tarefa->titulo) }}', '{{ $tarefa->status->value }}', '{{ $tarefa->prioridade->value }}')"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="inline" @submit.prevent="deletarTarefa($event, {{ $tarefa->id }})">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
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
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-medium">Nenhuma tarefa encontrada</p>
                                <button onclick="openCreateTarefaModal()" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-green-700">
                                    <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Nova Tarefa
                                </button>
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
        @if(count($tarefas) > 0)
            <div class="flex flex-col gap-2">
                <button onclick="openCreateTarefaModal()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Nova Tarefa
                </button>
            </div>
        @endif

        @forelse($tarefas as $tarefa)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $tarefa->titulo }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">
                            @if($tarefa->data_limite)
                                {{ $tarefa->data_limite->format('d/m/Y') }}
                            @else
                                Sem data limite
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-2 flex-col ml-2">
                        <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium
                            @if($tarefa->status->value === 'pendente') bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500
                            @elseif($tarefa->status->value === 'em_andamento') bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500
                            @elseif($tarefa->status->value === 'concluida') bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                            @else bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500 @endif">
                            {{ $tarefa->status->label() }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium
                            @if($tarefa->prioridade->value === 'urgente') bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                            @elseif($tarefa->prioridade->value === 'alta') bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500
                            @elseif($tarefa->prioridade->value === 'media') bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500
                            @else bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500 @endif">
                            {{ $tarefa->prioridade->label() }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2 mt-3">
                    @if($tarefa->status->value !== 'concluida')
                    <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST" class="flex-1" @submit.prevent="completarTarefa($event, {{ $tarefa->id }})">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-green-200 bg-white px-3 py-2 text-theme-xs font-medium text-green-600 hover:bg-green-50 dark:border-green-900 dark:bg-green-500/10 dark:text-green-400 dark:hover:bg-green-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Concluir
                        </button>
                    </form>
                    @endif

                    <button onclick="openEditTarefaModal({{ $tarefa->id }}, '{{ addslashes($tarefa->titulo) }}', '{{ $tarefa->status->value }}', '{{ $tarefa->prioridade->value }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>

                    <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="flex-1" @submit.prevent="deletarTarefa($event, {{ $tarefa->id }})">
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
            <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium mb-4">Nenhuma tarefa encontrada</p>
                    <button onclick="openCreateTarefaModal()" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-green-700">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nova Tarefa
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tarefas->links() }}
    </div>
</div>

<!-- Modal de Criação/Edição -->
<x-crud-modal
    id="tarefaModal"
    title="Criar Nova Tarefa"
    formId="tarefaModalForm"
    submitText="Criar"
>
    <input type="hidden" name="id" id="tarefaId" value="">

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Título</label>
        <input
            type="text"
            name="titulo"
            id="tarefaTitulo"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="tituloError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Descrição</label>
        <textarea
            name="descricao"
            id="tarefaDescricao"
            rows="3"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        ></textarea>
        <span class="text-red-500 text-sm" id="descricaoError"></span>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
            <select
                name="status"
                id="tarefaStatus"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="pendente">Pendente</option>
                <option value="em_andamento">Em Andamento</option>
                <option value="concluida">Concluída</option>
            </select>
            <span class="text-red-500 text-sm" id="statusError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Prioridade</label>
            <select
                name="prioridade"
                id="tarefaPrioridade"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="baixa">Baixa</option>
                <option value="media" selected>Média</option>
                <option value="alta">Alta</option>
                <option value="urgente">Urgente</option>
            </select>
            <span class="text-red-500 text-sm" id="prioridadeError"></span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data Início</label>
            <input
                type="date"
                name="data_inicio"
                id="tarefaDataInicio"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="data_inicioError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data Limite</label>
            <input
                type="date"
                name="data_limite"
                id="tarefaDataLimite"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="data_limiteError"></span>
        </div>
    </div>

    <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
</x-crud-modal>

<script>
    // Abre modal para criar tarefa
    function openCreateTarefaModal() {
        document.getElementById('tarefaModalTitle').textContent = 'Criar Nova Tarefa';
        document.getElementById('tarefaSubmitBtn').textContent = 'Criar';
        document.getElementById('tarefaModalForm').reset();
        document.getElementById('tarefaId').value = '';
        document.getElementById('tarefaModal').classList.remove('hidden');
    }

    // Abre modal para editar tarefa
    function openEditTarefaModal(id, titulo, status, prioridade) {
        document.getElementById('tarefaModalTitle').textContent = 'Editar Tarefa';
        document.getElementById('tarefaSubmitBtn').textContent = 'Atualizar';
        document.getElementById('tarefaId').value = id;
        document.getElementById('tarefaTitulo').value = titulo;
        document.getElementById('tarefaStatus').value = status;
        document.getElementById('tarefaPrioridade').value = prioridade;
        document.getElementById('tarefaModal').classList.remove('hidden');
    }

    // Submete o formulário
    async function submitTarefaForm(event) {
        event.preventDefault();

        const id = document.getElementById('tarefaId').value;
        const url = id ? `/tarefas/${id}` : '/tarefas';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('tarefaModalForm'));
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

            document.getElementById('tarefaModal').classList.add('hidden');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: id ? 'Tarefa atualizada com sucesso!' : 'Tarefa criada com sucesso!',
                showConfirmButton: false,
                timer: 1500,
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '99999';
                }
            }).then(() => {
                window.location.reload();
            });
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao processar o formulário',
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '99999';
                }
            });
        }
    }

    // Completar tarefa com SweetAlert
    function completarTarefa(event, tarefaId) {
        event.preventDefault();

        Swal.fire({
            title: 'Confirmar conclusão',
            text: 'Tem certeza que deseja marcar esta tarefa como concluída?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Concluir',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }

    // Deletar tarefa com SweetAlert
    function deletarTarefa(event, tarefaId) {
        event.preventDefault();

        Swal.fire({
            title: 'Confirmar exclusão',
            text: 'Tem certeza que deseja deletar esta tarefa? Esta ação não pode ser desfeita.',
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
                event.target.closest('form').submit();
            }
        });
    }
</script>
@endsection
