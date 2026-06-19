@extends('layouts.app')

@section('content')
<div x-data="participantesManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Participantes Externos</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('participante-externos.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome do participante..."
                        value="{{ request('search') ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Gênero</label>
                    <select
                        name="genero"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos</option>
                        <option value="m" {{ request('genero') === 'm' ? 'selected' : '' }}>Masculino</option>
                        <option value="f" {{ request('genero') === 'f' ? 'selected' : '' }}>Feminino</option>
                        <option value="outro" {{ request('genero') === 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search') || request('genero'))
                    <a href="{{ route('participante-externos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
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
                    Participantes Externos
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @if(count($participantes) > 0)
                    <button @click="deleteSelected()"
                            class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Deletar Selecionados
                    </button>
                @endif

                <button onclick="openModal('participanteModal', false)"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Novo Participante
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
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Telefone</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participantes as $participante)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $participante->id }}">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div @click="handleRowSelect({{ $participante->id }})"
                                        class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                        :class="selectedRows.includes({{ $participante->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                        <svg :class="selectedRows.includes({{ $participante->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $participante->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $participante->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $participante->telefone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('participante-externos.show', $participante) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openModal('participanteModal', true, {
                                        id: {{ $participante->id }},
                                        nome: '{{ addslashes($participante->nome) }}',
                                        email: '{{ addslashes($participante->email ?? '') }}',
                                        telefone: '{{ addslashes($participante->telefone ?? '') }}',
                                        documento: '{{ addslashes($participante->documento ?? '') }}',
                                        genero: '{{ $participante->genero ?? '' }}',
                                        data_nascimento: '{{ $participante->data_nascimento?->format('Y-m-d') ?? '' }}'
                                    })"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('participante-externos.destroy', $participante) }}" method="POST" class="inline"
                                          @submit.prevent="deleteItem({{ $participante->id }}, $event)">
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
                                Nenhum participante encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($participantes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.05]">
                {{ $participantes->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @if(count($participantes) > 0)
            <div class="flex flex-col gap-2">
                <button onclick="openModal('participanteModal', false)"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Novo Participante
                </button>
            </div>
        @endif

        @forelse($participantes as $participante)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $participante->nome }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ $participante->email ?? 'sem email' }}</p>
                    </div>
                </div>

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('participante-externos.show', $participante) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>

                    <button onclick="openModal('participanteModal', true, {
                        id: {{ $participante->id }},
                        nome: '{{ addslashes($participante->nome) }}',
                        email: '{{ addslashes($participante->email ?? '') }}',
                        telefone: '{{ addslashes($participante->telefone ?? '') }}',
                        documento: '{{ addslashes($participante->documento ?? '') }}',
                        genero: '{{ $participante->genero ?? '' }}',
                        data_nascimento: '{{ $participante->data_nascimento?->format('Y-m-d') ?? '' }}'
                    })"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>

                    <form action="{{ route('participante-externos.destroy', $participante) }}" method="POST" class="flex-1"
                          @submit.prevent="deleteItem({{ $participante->id }}, $event)">
                        @csrf
                        @method('DELETE')
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
                Nenhum participante encontrado
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    <x-modal-form
        id="participanteModal"
        title="Criar Novo Participante Externo"
        resource="participante-externos"
        size="lg"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
            <input
                type="text"
                name="nome"
                id="participanteModalnome"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="participanteModalnomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
            <input
                type="email"
                name="email"
                id="participanteModalemail"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="participanteModalemailError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Telefone</label>
            <input
                type="text"
                name="telefone"
                id="participanteModaltelefone"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="participanteModaltelefoneError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Documento (CPF/RG)</label>
            <input
                type="text"
                name="documento"
                id="participanteModaldocumento"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="participanteModaldocumentoError"></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Gênero</label>
                <select
                    name="genero"
                    id="participanteModalgenero"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Selecione...</option>
                    <option value="m">Masculino</option>
                    <option value="f">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
                <span class="text-red-500 text-sm" id="participanteModalgeneroError"></span>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data de Nascimento</label>
                <input
                    type="date"
                    name="data_nascimento"
                    id="participanteModaldata_nascimento"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                <span class="text-red-500 text-sm" id="participanteModaldata_nascimentoError"></span>
            </div>
        </div>
    </x-modal-form>

    <script>
        function participantesManager() {
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
                            didOpen: () => {
                                document.querySelector('.swal2-container').style.zIndex = '99999';
                            }
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: `Tem certeza que deseja deletar ${this.selectedRows.length} item(ns) selecionado(s)?`,
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
                            const formData = new FormData();
                            formData.append('ids', JSON.stringify(this.selectedRows));
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                            fetch('/participante-externos/delete-multiple', {
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
                                          text: 'Participantes deletados com sucesso!',
                                          showConfirmButton: false,
                                          timer: 1500,
                                          didOpen: () => {
                                              document.querySelector('.swal2-container').style.zIndex = '99999';
                                          }
                                      }).then(() => window.location.reload());
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Erro',
                                          text: 'Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'),
                                          didOpen: () => {
                                              document.querySelector('.swal2-container').style.zIndex = '99999';
                                          }
                                      });
                                  }
                              });
                        }
                    });
                },

                deleteItem(participanteId, event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja deletar este participante?',
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
            };
        }
    </script>
</div>
@endsection
