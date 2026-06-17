@extends('layouts.app')

@section('content')
<div x-data="eventosManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Eventos</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Eventos
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @if(count($eventos) > 0)
                    <button @click="deleteSelected()"
                            class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Deletar Selecionados
                    </button>
                @endif

                <button onclick="openModal('eventoModal', false)"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Novo Evento
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">
                            <div class="flex items-center gap-3">
                                <div @click="handleSelectAll()"
                                    class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                    :class="selectAll ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                    <svg :class="selectAll ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Nome</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Data Início</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $evento->id }}">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div @click="handleRowSelect({{ $evento->id }})"
                                        class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                        :class="selectedRows.includes({{ $evento->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                        <svg :class="selectedRows.includes({{ $evento->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $evento->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $evento->tipoEvento->nome ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $evento->data_inicio?->format('d/m/Y H:i') ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @switch($evento->status)
                                        @case('rascunho')
                                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                            @break
                                        @case('publicado')
                                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                            @break
                                        @case('encerrado')
                                            bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500
                                            @break
                                        @case('cancelado')
                                            bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                                            @break
                                        @default
                                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endswitch">
                                    {{ ucfirst($evento->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('eventos.show', $evento) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openModal('eventoModal', true, {
                                        id: {{ $evento->id }},
                                        tipo_evento_id: {{ $evento->tipo_evento_id }},
                                        entidade_criadora_id: {{ $evento->entidade_criadora_id }},
                                        nome: '{{ addslashes($evento->nome) }}',
                                        descricao: '{{ addslashes($evento->descricao ?? '') }}',
                                        data_inicio: '{{ $evento->data_inicio?->format('Y-m-d\TH:i') ?? '' }}',
                                        data_fim: '{{ $evento->data_fim?->format('Y-m-d\TH:i') ?? '' }}',
                                        local: '{{ addslashes($evento->local ?? '') }}',
                                        escopo: '{{ $evento->escopo }}',
                                        status: '{{ $evento->status }}',
                                        ativo: {{ $evento->ativo ? 'true' : 'false' }}
                                    })"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline"
                                          @submit.prevent="deleteItem({{ $evento->id }}, $event)">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Nenhum evento encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($eventos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.05]">
                {{ $eventos->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <x-modal-form
        id="eventoModal"
        title="Criar Novo Evento"
        resource="eventos"
        size="xl"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Evento</label>
            <select
                name="tipo_evento_id"
                id="eventoModaltipo_evento_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione um tipo...</option>
                @foreach ($tiposEvento as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm" id="eventoModaltipo_evento_idError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Entidade Criadora</label>
            <select
                name="entidade_criadora_id"
                id="eventoModalentidade_criadora_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
                data-create-only
            >
                <option value="">Selecione uma entidade...</option>
                @foreach ($entidades as $entidade)
                    <option value="{{ $entidade->id }}">{{ $entidade->nome }} ({{ $entidade->tipo_entidade }})</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm" id="eventoModalentidade_criadora_idError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
            <input
                type="text"
                name="nome"
                id="eventoModalnome"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="eventoModalnomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Descrição</label>
            <textarea
                name="descricao"
                id="eventoModaldescricao"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            ></textarea>
            <span class="text-red-500 text-sm" id="eventoModaldescricaoError"></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data/Hora Início</label>
                <input
                    type="datetime-local"
                    name="data_inicio"
                    id="eventoModaldata_inicio"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required
                >
                <span class="text-red-500 text-sm" id="eventoModaldata_inicioError"></span>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data/Hora Fim</label>
                <input
                    type="datetime-local"
                    name="data_fim"
                    id="eventoModaldata_fim"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                <span class="text-red-500 text-sm" id="eventoModaldata_fimError"></span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Local</label>
            <input
                type="text"
                name="local"
                id="eventoModallocal"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="eventoModallocalError"></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Escopo</label>
                <select
                    name="escopo"
                    id="eventoModalescopo"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required
                >
                    <option value="">Selecione...</option>
                    <option value="coordenadores">Coordenadores</option>
                    <option value="dirigentes">Dirigentes</option>
                    <option value="ambos">Ambos (Coordenadores e Dirigentes)</option>
                    <option value="externos">Externos</option>
                    <option value="publico">Público</option>
                </select>
                <span class="text-red-500 text-sm" id="eventoModalescopoError"></span>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
                <select
                    name="status"
                    id="eventoModalstatus"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    required
                >
                    <option value="rascunho">Rascunho</option>
                    <option value="publicado">Publicado</option>
                    <option value="encerrado">Encerrado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                <span class="text-red-500 text-sm" id="eventoModalstatusError"></span>
            </div>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
            <label class="flex items-center dark:text-gray-200">
                <input type="checkbox" name="ativo" id="eventoModalativo" value="1" class="rounded dark:bg-gray-700">
                <span class="ml-2">Evento ativo no sistema</span>
            </label>
        </div>
    </x-modal-form>

    <script>
        function eventosManager() {
            return {
                selectedRows: [],
                selectAll: false,

                handleSelectAll() {
                    this.selectAll = !this.selectAll;
                    if (this.selectAll) {
                        document.querySelectorAll('[data-row-id]').forEach(el => {
                            const id = parseInt(el.dataset.rowId);
                            if (!this.selectedRows.includes(id)) this.selectedRows.push(id);
                        });
                    } else {
                        this.selectedRows = [];
                    }
                },

                handleRowSelect(id) {
                    if (this.selectedRows.includes(id)) {
                        this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                    } else {
                        this.selectedRows.push(id);
                    }
                },

                deleteSelected() {
                    if (this.selectedRows.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: 'Selecione pelo menos um item',
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: `Tem certeza que deseja deletar ${this.selectedRows.length} item(ns) selecionado(s)?`,
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Deletar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('ids', JSON.stringify(this.selectedRows));
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                            fetch('/eventos/delete-multiple', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                }
                            }).then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      Swal.fire({
                                          icon: 'success',
                                          title: 'Sucesso!',
                                          text: 'Eventos deletados com sucesso!',
                                      }).then(() => window.location.reload());
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Erro',
                                          text: 'Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'),
                                      });
                                  }
                              });
                        }
                    });
                },

                deleteItem(eventoId, event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja deletar este evento?',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Deletar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.closest('form').submit();
                        }
                    });
                }
            };
        }
    </script>
</div>
@endsection
