@extends('layouts.app')

@section('content')
<div x-data="secretariasManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Secretarias</h1>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('secretarias.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome da secretaria..."
                        value="{{ $searchQuery }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
                    <select
                        name="status"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos os status</option>
                        <option value="ativo" {{ $selectedStatus === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ $selectedStatus === 'inativo' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if($selectedStatus || $searchQuery)
                    <a href="{{ route('secretarias.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
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
                    Secretarias
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @can('deleteMultiple')
                    @if(count($secretarias) > 0)
                        <button @click="deleteSelected()"
                                class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950">
                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Deletar Selecionados
                        </button>
                    @endif
                @endcan

                @can('create', App\Models\Entidade::class)
                    <button onclick="openCreateSecretariaModal()"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nova Secretaria
                    </button>
                @endcan
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        @can('deleteMultiple')
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
                        @endcan
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Foto</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Nome</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Habilidades</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($secretarias as $secretaria)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $secretaria->id }}">
                            @can('deleteMultiple')
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div @click="handleRowSelect({{ $secretaria->id }})"
                                            class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                            :class="selectedRows.includes({{ $secretaria->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                            <svg :class="selectedRows.includes({{ $secretaria->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            @endcan
                            <td class="px-6 py-3.5">
                                <x-avatar :model="$secretaria" size="md" />
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $secretaria->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ ucfirst($secretaria->tipo_secretaria->value) }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                                    {{ $secretaria->habilidades()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $secretaria->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @if($secretaria->ativo)
                                        bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else
                                        bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $secretaria->ativo ? 'Ativo/a' : 'Inativo/a' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('secretarias.show', $secretaria) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openInfoModal('secretaria', {{ $secretaria->id }}, '{{ $secretaria->nome }}')"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-cyan-600 hover:bg-cyan-50 dark:hover:bg-cyan-500/10"
                                       title="Informações">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    @can('update', $secretaria)
                                        <button onclick="handleEditSecretaria(this)"
                                           data-id="{{ $secretaria->id }}"
                                           data-nome="{{ $secretaria->nome }}"
                                           data-email="{{ $secretaria->email ?? '' }}"
                                           data-tipo="{{ $secretaria->tipo_secretaria ? $secretaria->tipo_secretaria->value : '' }}"
                                           data-ativo="{{ $secretaria->ativo ? 1 : 0 }}"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete', $secretaria)
                                        <form action="{{ route('secretarias.destroy', $secretaria) }}" method="POST" class="inline"
                                              @submit.prevent="deleteItem({{ $secretaria->id }}, $event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                                    title="Deletar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Nenhuma secretaria encontrada
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @if(count($secretarias) > 0)
            <div class="flex flex-col gap-2">
                @can('create', App\Models\Entidade::class)
                    <button onclick="openCreateSecretariaModal()"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Nova Secretaria
                    </button>
                @endcan
            </div>
        @endif

        @forelse($secretarias as $secretaria)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $secretaria->nome }}</h4>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2
                        @if($secretaria->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $secretaria->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('secretarias.show', $secretaria) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>

                    <button onclick="openInfoModal('secretaria', {{ $secretaria->id }}, '{{ $secretaria->nome }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-cyan-200 bg-white px-3 py-2 text-theme-xs font-medium text-cyan-600 hover:bg-cyan-50 dark:border-cyan-900 dark:bg-cyan-500/10 dark:text-cyan-400 dark:hover:bg-cyan-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Info
                    </button>

                    @can('update', $secretaria)
                        <button onclick="openEditSecretariaModal({{ $secretaria->id }}, '{{ addcslashes($secretaria->nome, "'\\") }}', '{{ addcslashes($secretaria->email ?? '', "'\\") }}', '{{ $secretaria->tipo_secretaria ? addcslashes($secretaria->tipo_secretaria->value, "'\\") : '' }}', {{ $secretaria->entidade_pai_id }}, {{ $secretaria->ativo ? 1 : 0 }})"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                    @endcan

                    @can('delete', $secretaria)
                        <form action="{{ route('secretarias.destroy', $secretaria) }}" method="POST" class="flex-1"
                              @submit.prevent="deleteItem({{ $secretaria->id }}, $event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Deletar
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500">
                Nenhuma secretaria encontrada
            </div>
        @endforelse
    </div>

    <!-- Modal de Criação/Edição -->
    <x-crud-modal
        id="secretariaModal"
        title="Criar Nova Secretaria"
        formId="secretariaModalForm"
        submitText="Criar"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
            <input
                type="text"
                name="nome"
                id="secretariaModalnome"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="secretariaModalnomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
            <input
                type="email"
                name="email"
                id="secretariaModalemail"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="secretariaModalemailError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Senha</label>
            <input
                type="password"
                name="password"
                id="secretariaModalpassword"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="secretariaModalpasswordError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Secretaria</label>
            <select
                name="tipo_secretaria"
                id="secretariaModaltipo_secretaria"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione...</option>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </select>
            <span class="text-red-500 text-sm" id="secretariaModaltipo_secretariaError"></span>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
            <label class="flex items-center dark:text-gray-200">
                <input type="checkbox" name="ativo" id="secretariaModalativo" value="1" class="rounded dark:bg-gray-700">
                <span class="ml-2">Secretaria ativa no sistema</span>
            </label>
        </div>

        <!-- Habilidades Section -->
        <div class="mt-6 pt-4 border-t dark:border-gray-700" data-edit-only style="display: none;">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Habilidades</h3>

            <!-- Skills List Table -->
            <div class="mb-4" id="habilidadesListContainer">
                <table class="w-full text-sm">
                    <thead class="border-b dark:border-gray-700">
                        <tr>
                            <th class="text-left py-2 px-3 dark:text-gray-300">Nome</th>
                            <th class="text-left py-2 px-3 dark:text-gray-300">Descrição</th>
                            <th class="text-left py-2 px-3 dark:text-gray-300">Status</th>
                            <th class="text-left py-2 px-3 dark:text-gray-300">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="habilidadesList" class="dark:text-gray-300">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Add/Edit Form -->
            <div class="border-t pt-4 dark:border-gray-700">
                <div class="grid grid-cols-[1fr_auto] gap-2 mb-4">
                    <div>
                        <input
                            type="text"
                            id="habilidadeNome"
                            placeholder="Nome da habilidade..."
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                    </div>
                    <button
                        type="button"
                        onclick="addOrUpdateHabilidade()"
                        id="habilidadeAddBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >+ Adicionar</button>
                </div>
                <textarea
                    id="habilidadeDescricao"
                    placeholder="Descrição (opcional)"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2"
                    rows="2"
                ></textarea>
                <label class="flex items-center gap-2 dark:text-gray-300">
                    <input type="checkbox" id="habilidadeAtivo" checked class="rounded dark:bg-gray-700">
                    <span class="text-sm">Ativo</span>
                </label>
            </div>
        </div>
    </x-crud-modal>

    <!-- Info Modal -->
    <x-info-modal id="infoModal">
        <div id="infoModalContent" class="space-y-4">
            <!-- Preenchido dinamicamente via JavaScript -->
        </div>
    </x-info-modal>
</div>

<script>
    let secretariaEditId = null;
    let currentEditingHabilidadeId = null;

    // Load habilidades when modal opens
    function loadHabilidades(secretariaId) {
        if (!secretariaId) return;

        fetch(`/secretarias/${secretariaId}/habilidades`)
            .then(r => r.json())
            .then(data => {
                renderHabilidades(data.habilidades || []);
            })
            .catch(e => console.error('Erro ao carregar habilidades:', e));
    }

    function renderHabilidades(habilidades) {
        const tbody = document.getElementById('habilidadesList');
        tbody.innerHTML = '';

        if (habilidades.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">Nenhuma habilidade cadastrada</td></tr>';
            return;
        }

        habilidades.forEach(h => {
            const tr = document.createElement('tr');
            tr.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800';
            tr.innerHTML = `
                <td class="py-3 px-3">${h.nome}</td>
                <td class="py-3 px-3 text-gray-600 dark:text-gray-400">${h.descricao || '-'}</td>
                <td class="py-3 px-3">
                    <span class="text-xs px-2 py-1 rounded ${h.ativo ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'}">
                        ${h.ativo ? 'Ativa' : 'Inativa'}
                    </span>
                </td>
                <td class="py-3 px-3 flex gap-2">
                    <button type="button" onclick="editHabilidade(${h.id}, '${h.nome.replace(/'/g, "\\'")}', '${(h.descricao || '').replace(/'/g, "\\'")}', ${h.ativo})"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-300"
                        title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="deleteHabilidade(${h.id})"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-800 dark:text-red-400 dark:hover:bg-red-500/10 dark:hover:text-red-300"
                        title="Excluir">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function editHabilidade(id, nome, descricao, ativo) {
        currentEditingHabilidadeId = id;
        document.getElementById('habilidadeNome').value = nome;
        document.getElementById('habilidadeDescricao').value = descricao;
        document.getElementById('habilidadeAtivo').checked = ativo;
        document.getElementById('habilidadeAddBtn').textContent = '✓ Salvar Edição';
        document.getElementById('habilidadeNome').focus();
    }

    function cancelEditHabilidade() {
        currentEditingHabilidadeId = null;
        document.getElementById('habilidadeNome').value = '';
        document.getElementById('habilidadeDescricao').value = '';
        document.getElementById('habilidadeAtivo').checked = true;
        document.getElementById('habilidadeAddBtn').textContent = '+ Adicionar';
    }

    async function addOrUpdateHabilidade() {
        const secretariaId = secretariaEditId;
        if (!secretariaId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Salve a secretaria primeiro',
                
            });
            return;
        }

        const nome = document.getElementById('habilidadeNome').value.trim();
        const descricao = document.getElementById('habilidadeDescricao').value.trim();
        const ativo = document.getElementById('habilidadeAtivo').checked;

        if (!nome) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Digite o nome da habilidade',
                
            });
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = currentEditingHabilidadeId
            ? `/secretarias/${secretariaId}/habilidades/${currentEditingHabilidadeId}`
            : `/secretarias/${secretariaId}/habilidades`;

        const method = currentEditingHabilidadeId ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    _method: method,
                    nome: nome,
                    descricao: descricao,
                    ativo: ativo ? 1 : 0,
                }),
            });

            if (response.ok) {
                const data = await response.json();
                loadHabilidades(secretariaId);
                cancelEditHabilidade();
            } else {
                const errorData = await response.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: errorData.message || 'Erro desconhecido',
                    didOpen: () => {
                        document.querySelector('.swal2-container').style.zIndex = '99999';
                    }
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao salvar habilidade',
                
            });
        }
    }

    async function deleteHabilidade(habilidadeId) {
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
                deleteHabilidadeConfirmed(habilidadeId);
            }
        });
    }

    async function deleteHabilidadeConfirmed(habilidadeId) {

        const secretariaId = secretariaEditId;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/secretarias/${secretariaId}/habilidades/${habilidadeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (response.ok) {
                loadHabilidades(secretariaId);
                if (currentEditingHabilidadeId === habilidadeId) {
                    cancelEditHabilidade();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao deletar habilidade',
                    didOpen: () => {
                        document.querySelector('.swal2-container').style.zIndex = '99999';
                    }
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao deletar habilidade',
                
            });
        }
    }

    // Abre modal para criar secretaria
    function openCreateSecretariaModal() {
        secretariaEditId = null;
        document.getElementById('secretariaModalTitle').textContent = 'Criar Nova Secretaria';
        document.getElementById('secretariaSubmitBtn').textContent = 'Criar';
        document.getElementById('secretariaModalForm').reset();
        document.querySelectorAll('#secretariaModal [data-edit-only]').forEach(el => {
            el.style.display = 'none';
        });
        document.getElementById('secretariaModal').classList.remove('hidden');
    }

    // Handle edit button via data attributes
    function handleEditSecretaria(button) {
        const id = button.dataset.id;
        const nome = button.dataset.nome;
        const email = button.dataset.email;
        const tipo = button.dataset.tipo;
        const ativo = button.dataset.ativo;
        openEditSecretariaModal(id, nome, email, tipo, ativo);
    }

    // Abre modal para editar secretaria
    function openEditSecretariaModal(id, nome, email, tipo_secretaria, ativo) {
        secretariaEditId = String(id).trim();
        console.log('openEditSecretariaModal: secretariaEditId set to:', secretariaEditId);

        const titleEl = document.getElementById('secretariaModalTitle');
        const submitBtn = document.getElementById('secretariaSubmitBtn');
        const nomeEl = document.getElementById('secretariaModalnome');
        const emailEl = document.getElementById('secretariaModalemail');
        const tipoEl = document.getElementById('secretariaModaltipo_secretaria');
        const ativoEl = document.getElementById('secretariaModalativo');

        if (titleEl) titleEl.textContent = 'Editar Secretaria';
        if (submitBtn) submitBtn.textContent = 'Atualizar';
        if (nomeEl) nomeEl.value = nome;
        if (emailEl) emailEl.value = email;
        if (tipoEl) tipoEl.value = tipo_secretaria;
        if (ativoEl) ativoEl.checked = ativo == 1 || ativo == true;

        document.querySelectorAll('#secretariaModal [data-edit-only]').forEach(el => {
            el.style.display = 'block';
        });
        document.getElementById('secretariaModal').classList.remove('hidden');
        loadHabilidades(id);
    }

    // Submete o formulário
    async function submitSecretariaForm(event) {
        event.preventDefault();

        let url = '/secretarias';
        let method = 'POST';

        if (secretariaEditId && String(secretariaEditId).trim() !== '') {
            url = '/secretarias/' + String(secretariaEditId).trim();
            method = 'PUT';
        }

        console.log('submitSecretariaForm: secretariaEditId=', secretariaEditId, 'typeof=', typeof secretariaEditId);
        console.log('url construction: secretariaEditId ?=', !!secretariaEditId, 'url=', url, 'method=', method);

        const formData = new FormData(document.getElementById('secretariaModalForm'));
        const data = Object.fromEntries(formData);

        // Remove id from data if it exists
        delete data.id;

        // Para PUT, adicionar _method ao payload
        if (method === 'PUT') {
            data._method = 'PUT';
        }

        console.log('About to fetch: url=', url, 'method=', method, 'data=', data);

        try {
            const response = await fetch(url, {
                method: 'POST', // Sempre POST para compatibilidade
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
                        const errorEl = document.getElementById('secretariaModal' + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
                        if (errorEl) {
                            errorEl.textContent = errorData.errors[key][0];
                        }
                    });
                }
                return;
            }

            document.getElementById('secretariaModal').classList.add('hidden');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: secretariaEditId ? 'Secretaria atualizada com sucesso!' : 'Secretaria criada com sucesso!',
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
</script>
@endsection
