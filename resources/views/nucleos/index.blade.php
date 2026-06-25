@extends('layouts.app')

@section('content')
<div x-data="nucleosManager()" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Núcleos</h1>
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
            <form method="GET" action="{{ route('nucleos.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome do núcleo..."
                        value="{{ $searchQuery }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Diocese</label>
                    <select
                        name="diocese_id"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todas as dioceses</option>
                        @foreach($dioceses as $diocese)
                            <option value="{{ $diocese->id }}" {{ $selectedDiocese == $diocese->id ? 'selected' : '' }}>
                                {{ $diocese->nome }}
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
                @if($selectedDiocese || $selectedStatus || $searchQuery)
                    <a href="{{ route('nucleos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
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
                    Núcleos
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @can('deleteMultiple')
                    @if(count($nucleos) > 0)
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
                    <button onclick="openCreateNucleoModal()"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Novo Núcleo
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
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Diocese</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Foto</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Nome</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nucleos as $nucleo)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $nucleo->id }}">
                            @can('deleteMultiple')
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div @click="handleRowSelect({{ $nucleo->id }})"
                                            class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                            :class="selectedRows.includes({{ $nucleo->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                            <svg :class="selectedRows.includes({{ $nucleo->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            @endcan
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $nucleo->entidadePai->nome ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <x-avatar :model="$nucleo" size="md" />
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $nucleo->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $nucleo->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @if($nucleo->ativo)
                                        bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else
                                        bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $nucleo->ativo ? 'Ativo/a' : 'Inativo/a' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('nucleos.show', $nucleo) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openInfoModal('nucleo', {{ $nucleo->id }}, '{{ $nucleo->nome }}')"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-cyan-600 hover:bg-cyan-50 dark:hover:bg-cyan-500/10"
                                       title="Informações">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    @can('update', $nucleo)
                                        <button onclick="handleEditNucleo(this)"
                                           data-id="{{ $nucleo->id }}"
                                           data-nome="{{ $nucleo->nome }}"
                                           data-email="{{ $nucleo->email ?? '' }}"
                                           data-paroquia="{{ $nucleo->paroquia ?? '' }}"
                                           data-endereco="{{ $nucleo->endereco_paroquia ?? '' }}"
                                           data-padre="{{ $nucleo->padre ?? '' }}"
                                           data-entidade-pai="{{ $nucleo->entidade_pai_id }}"
                                           data-ativo="{{ $nucleo->ativo ? 1 : 0 }}"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete', $nucleo)
                                        <form action="{{ route('nucleos.destroy', $nucleo) }}" method="POST" class="inline"
                                              @submit.prevent="deleteItem({{ $nucleo->id }}, $event)">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Nenhum núcleo encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @if(count($nucleos) > 0)
            <div class="flex flex-col gap-2">
                @can('create', App\Models\Entidade::class)
                    <button onclick="openCreateNucleoModal()"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Novo Núcleo
                    </button>
                @endcan
            </div>
        @endif

        @forelse($nucleos as $nucleo)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $nucleo->nome }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ $nucleo->entidadePai->nome ?? 'sem diocese' }}</p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2
                        @if($nucleo->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('nucleos.show', $nucleo) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>

                    <button onclick="openInfoModal('nucleo', {{ $nucleo->id }}, '{{ $nucleo->nome }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-cyan-200 bg-white px-3 py-2 text-theme-xs font-medium text-cyan-600 hover:bg-cyan-50 dark:border-cyan-900 dark:bg-cyan-500/10 dark:text-cyan-400 dark:hover:bg-cyan-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Info
                    </button>

                    @can('update', $nucleo)
                        <button onclick="openEditNucleoModal({{ $nucleo->id }}, '{{ addcslashes($nucleo->nome, "'\\") }}', '{{ addcslashes($nucleo->email ?? '', "'\\") }}', '{{ addcslashes($nucleo->paroquia ?? '', "'\\") }}', '{{ addcslashes($nucleo->endereco_paroquia ?? '', "'\\") }}', '{{ addcslashes($nucleo->padre ?? '', "'\\") }}', {{ $nucleo->entidade_pai_id }}, {{ $nucleo->ativo ? 1 : 0 }})"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                    @endcan

                    @can('delete', $nucleo)
                        <form action="{{ route('nucleos.destroy', $nucleo) }}" method="POST" class="flex-1"
                              @submit.prevent="deleteItem({{ $nucleo->id }}, $event)">
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
                Nenhum núcleo encontrado
            </div>
        @endforelse
    </div>

    <!-- Modal de Criação/Edição -->
    <x-modal
        id="nucleoModal"
        title="Criar Novo Núcleo"
        submitText="Criar"
    >
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
            <input
                type="text"
                name="nome"
                id="nucleoModalnome"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
            <span class="text-red-500 text-sm" id="nucleoModalnomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
            <input
                type="email"
                name="email"
                id="nucleoModalemail"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="nucleoModalemailError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Senha</label>
            <input
                type="password"
                name="password"
                id="nucleoModalpassword"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="nucleoModalpasswordError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Paróquia</label>
            <input
                type="text"
                name="paroquia"
                id="nucleoModalparoquia"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="nucleoModalparoquiaError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Endereço da Paróquia</label>
            <input
                type="text"
                name="endereco_paroquia"
                id="nucleoModalendereco_paroquia"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="nucleoModalendereco_paroquiaError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Padre</label>
            <input
                type="text"
                name="padre"
                id="nucleoModalpadre"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="nucleoModalpadreError"></span>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Diocese</label>
            <select
                name="entidade_pai_id"
                id="nucleoModalentidade_pai_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione uma diocese...</option>
                @foreach($dioceses as $diocese)
                    <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm" id="nucleoModalentidade_pai_idError"></span>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
            <label class="flex items-center dark:text-gray-200">
                <input type="checkbox" name="ativo" id="nucleoModalativo" value="1" class="rounded dark:bg-gray-700">
                <span class="ml-2">Núcleo ativo no sistema</span>
            </label>
        </div>
    </x-modal>

    <!-- Info Modal -->
    <x-info-modal id="infoModal">
        <div id="infoModalContent" class="space-y-4">
            <!-- Preenchido dinamicamente via JavaScript -->
        </div>
    </x-info-modal>
</div>

<script>
    let nucleoEditId = null;

    // Abre modal para criar núcleo
    function openCreateNucleoModal() {
        nucleoEditId = null;
        document.getElementById('nucleoModalTitle').textContent = 'Criar Novo Núcleo';
        document.getElementById('nucleoSubmitBtn').textContent = 'Criar';
        document.getElementById('nucleoModalForm').reset();
        document.querySelectorAll('#nucleoModal [data-edit-only]').forEach(el => {
            el.style.display = 'none';
        });
        showModal('nucleoModal');
    }

    // Handle edit button via data attributes
    function handleEditNucleo(button) {
        const id = button.dataset.id;
        const nome = button.dataset.nome;
        const email = button.dataset.email;
        const paroquia = button.dataset.paroquia;
        const endereco = button.dataset.endereco;
        const padre = button.dataset.padre;
        const entidadePai = button.dataset.entidadePai;
        const ativo = button.dataset.ativo;
        openEditNucleoModal(id, nome, email, paroquia, endereco, padre, entidadePai, ativo);
    }

    // Abre modal para editar núcleo
    function openEditNucleoModal(id, nome, email, paroquia, endereco_paroquia, padre, entidade_pai_id, ativo) {
        nucleoEditId = String(id).trim();
        console.log('openEditNucleoModal: nucleoEditId set to:', nucleoEditId);

        const titleEl = document.getElementById('nucleoModalTitle');
        const submitBtn = document.getElementById('nucleoSubmitBtn');

        if (titleEl) titleEl.textContent = 'Editar Núcleo';
        if (submitBtn) submitBtn.textContent = 'Atualizar';

        const nomeEl = document.getElementById('nucleoModalnome');
        const emailEl = document.getElementById('nucleoModalemail');
        const paroquiaEl = document.getElementById('nucleoModalparoquia');
        const enderecoEl = document.getElementById('nucleoModalendereco_paroquia');
        const padreEl = document.getElementById('nucleoModalpadre');
        const entidadeEl = document.getElementById('nucleoModalentidade_pai_id');
        const ativoEl = document.getElementById('nucleoModalativo');

        if (nomeEl) nomeEl.value = nome;
        if (emailEl) emailEl.value = email;
        if (paroquiaEl) paroquiaEl.value = paroquia;
        if (enderecoEl) enderecoEl.value = endereco_paroquia;
        if (padreEl) padreEl.value = padre;
        if (entidadeEl) entidadeEl.value = entidade_pai_id;
        if (ativoEl) ativoEl.checked = ativo == 1 || ativo == true;

        document.querySelectorAll('#nucleoModal [data-edit-only]').forEach(el => {
            el.style.display = 'block';
        });
        showModal('nucleoModal');
    }

    // Submete o formulário
    async function submitNucleoForm(event) {
        event.preventDefault();

        let url = '/nucleos';
        let method = 'POST';

        if (nucleoEditId && String(nucleoEditId).trim() !== '') {
            url = '/nucleos/' + String(nucleoEditId).trim();
            method = 'PUT';
        }

        console.log('submitNucleoForm: nucleoEditId=', nucleoEditId, 'typeof=', typeof nucleoEditId);
        console.log('url construction: nucleoEditId ?=', !!nucleoEditId, 'url=', url, 'method=', method);

        const formData = new FormData(document.getElementById('nucleoModalForm'));
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
                        const errorEl = document.getElementById('nucleoModal' + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
                        if (errorEl) {
                            errorEl.textContent = errorData.errors[key][0];
                        }
                    });
                }
                return;
            }

            hideModal('nucleoModal');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: nucleoEditId ? 'Núcleo atualizado com sucesso!' : 'Núcleo criado com sucesso!',
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
