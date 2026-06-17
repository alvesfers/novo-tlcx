@extends('layouts.app')

@section('content')
<div x-data="entidadesManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Entidades</h1>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Entidades
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @if(count($entidades) > 0)
                    <button @click="deleteSelected()"
                            class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Deletar Selecionados
                    </button>
                @endif

                <button onclick="openModal('entidadeModal', false)"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Nova Entidade
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
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entidades as $entidade)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $entidade->id }}">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div @click="handleRowSelect({{ $entidade->id }})"
                                        class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                        :class="selectedRows.includes({{ $entidade->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                        <svg :class="selectedRows.includes({{ $entidade->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $entidade->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ ucfirst($entidade->tipo_entidade) }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $entidade->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @if($entidade->ativo)
                                        bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else
                                        bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $entidade->ativo ? 'Ativo/a' : 'Inativo/a' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('entidades.show', $entidade) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openModal('entidadeModal', true, {
                                        id: {{ $entidade->id }},
                                        nome: '{{ addslashes($entidade->nome) }}',
                                        email: '{{ addslashes($entidade->email ?? '') }}',
                                        tipo_entidade: '{{ $entidade->tipo_entidade }}',
                                        tipo_secretaria: '{{ $entidade->tipo_secretaria ?? '' }}',
                                        ativo: {{ $entidade->ativo ? 'true' : 'false' }}
                                    })"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('entidades.destroy', $entidade) }}" method="POST" class="inline"
                                          @submit.prevent="deleteItem({{ $entidade->id }}, $event)">
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
                                Nenhuma entidade encontrada
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <x-modal-form
        id="entidadeModal"
        title="Criar Nova Entidade"
        resource="entidades"
        size="lg"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Entidade</label>
            <select
                name="tipo_entidade"
                id="entidadeModaltipo_entidade"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
                data-create-only
            >
                <option value="">Selecione...</option>
                <option value="diocese">Diocese</option>
                <option value="nucleo">Núcleo</option>
                <option value="secretaria">Secretaria</option>
            </select>
            <span class="text-red-500 text-sm" id="entidadeModaltipo_entidadeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
            <input
                type="text"
                name="nome"
                id="entidadeModalnome"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="entidadeModalnomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
            <input
                type="email"
                name="email"
                id="entidadeModalemail"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="entidadeModalemailError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Secretaria (apenas para Secretarias)</label>
            <select
                name="tipo_secretaria"
                id="entidadeModaltipo_secretaria"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">Não aplicável</option>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </select>
            <span class="text-red-500 text-sm" id="entidadeModaltipo_secretariaError"></span>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
            <label class="flex items-center dark:text-gray-200">
                <input type="checkbox" name="ativo" id="entidadeModalativo" value="1" class="rounded dark:bg-gray-700">
                <span class="ml-2">Entidade ativa no sistema</span>
            </label>
        </div>
    </x-modal-form>

    <script>
        function entidadesManager() {
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

                            fetch('/entidades/delete-multiple', {
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
                                          text: 'Entidades deletadas com sucesso!',
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

                deleteItem(entidadeId, event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja deletar esta entidade?',
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
