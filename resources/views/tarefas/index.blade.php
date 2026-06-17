@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Filtros -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('tarefas.index', ['filter' => 'todas']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'todas') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Todas
        </a>
        <a href="{{ route('tarefas.index', ['filter' => 'pendentes']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'pendentes') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Pendentes
        </a>
        <a href="{{ route('tarefas.index', ['filter' => 'em_andamento']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'em_andamento') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Em Andamento
        </a>
        <a href="{{ route('tarefas.index', ['filter' => 'concluidas']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'concluidas') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Concluídas
        </a>
        <a href="{{ route('tarefas.index', ['filter' => 'vencidas']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'vencidas') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Vencidas
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Tarefas
                </h3>
            </div>
            <button onclick="openModal('tarefaModal')"
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
                            <div class="flex items-center gap-3">
                                @if($tarefa->status->value !== 'concluida')
                                <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST" style="display: inline;">
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
                                <button onclick="openModal('tarefaModal')"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza?')"
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
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-sm">Nenhuma tarefa encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $tarefas->links() }}
    </div>
</div>

<x-modal-form
    id="tarefaModal"
    title="Nova Tarefa"
    resource="tarefas"
    size="lg"
>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
            <input type="text" name="titulo" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
            <textarea name="descricao" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    <option value="pendente">Pendente</option>
                    <option value="em_andamento">Em Andamento</option>
                    <option value="concluida">Concluída</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                <select name="prioridade" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    <option value="baixa">Baixa</option>
                    <option value="media" selected>Média</option>
                    <option value="alta">Alta</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                <input type="date" name="data_inicio" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Limite</label>
                <input type="date" name="data_limite" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
            </div>
        </div>
        <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
    </div>
</x-modal-form>
@endsection
