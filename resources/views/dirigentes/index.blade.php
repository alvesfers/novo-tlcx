@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Dirigentes</h1>
    </div>

    <!-- Filtros por Escopo (Login) -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('dirigentes.index', ['filter' => 'todos']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'todos') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
            Todos
        </a>
        @if(auth()->user()->isDiocese() || auth()->user()->isNucleo() || auth()->user()->isSecretaria())
            @if(auth()->user()->isDiocese())
                <a href="{{ route('dirigentes.index', ['filter' => 'minha_diocese']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'minha_diocese') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Minha Diocese
                </a>
            @endif
            @if(auth()->user()->isNucleo())
                <a href="{{ route('dirigentes.index', ['filter' => 'meu_nucleo']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'meu_nucleo') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Meu Núcleo
                </a>
            @endif
            @if(auth()->user()->isSecretaria())
                <a href="{{ route('dirigentes.index', ['filter' => 'minha_secretaria']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'minha_secretaria') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Minha Secretaria
                </a>
            @endif
        @endif
    </div>

    <!-- Filtros Independentes -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros Avançados</h3>
            <form method="GET" action="{{ route('dirigentes.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Nome do dirigente..."
                        value="{{ request('search') }}"
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
                            <option value="{{ $diocese->id }}" {{ request('diocese_id') == $diocese->id ? 'selected' : '' }}>
                                {{ $diocese->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Núcleo</label>
                    <select
                        name="nucleo_id"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos os núcleos</option>
                        @foreach($nucleos as $nucleo)
                            <option value="{{ $nucleo->id }}" {{ request('nucleo_id') == $nucleo->id ? 'selected' : '' }}>
                                {{ $nucleo->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Secretaria</label>
                    <select
                        name="secretaria_id"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todas as secretarias</option>
                        @foreach($secretarias as $secretaria)
                            <option value="{{ $secretaria->id }}" {{ request('secretaria_id') == $secretaria->id ? 'selected' : '' }}>
                                {{ $secretaria->nome }}
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
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('diocese_id') || request('nucleo_id') || request('secretaria_id') || request('status') || request('search'))
                    <a href="{{ route('dirigentes.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <!-- Desktop/Tablet View (≥768px) -->
    <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Dirigentes
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @can('deleteMultiple', App\Models\Dirigente::class)
                    @if(count($dirigentes) > 0)
                        <button onclick="deleteSelected()"
                                class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950">
                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Deletar Selecionados
                        </button>
                    @endif
                @endcan

                @can('create', App\Models\Dirigente::class)
                    <button onclick="openCreateDirigenteModal()"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Novo Dirigente
                    </button>
                @endcan
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        @can('deleteMultiple', App\Models\Dirigente::class)
                            <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                    <div onclick="handleSelectAll()"
                                        class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] border-gray-300 dark:border-gray-700 bg-white dark:bg-white/0"
                                        id="selectAllCheckbox">
                                        <svg class="hidden" id="selectAllCheckmarkIcon" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                        @endcan
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Foto</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Nome</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Telefone</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Núcleo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dirigentes as $dirigente)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $dirigente->id }}">
                            @can('deleteMultiple', App\Models\Dirigente::class)
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div onclick="handleRowSelect({{ $dirigente->id }})"
                                            class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px] border-gray-300 dark:border-gray-700 bg-white dark:bg-white/0 checkbox-row"
                                            data-id="{{ $dirigente->id }}">
                                            <svg class="hidden checkmark-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            @endcan
                            <td class="px-6 py-3.5">
                                <x-avatar :model="$dirigente" size="md" />
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $dirigente->nome }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $dirigente->telefone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $dirigente->vinculos->first()?->entidade->nome ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                    @if($dirigente->ativo)
                                        bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else
                                        bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $dirigente->ativo ? 'Ativo/a' : 'Inativo/a' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dirigentes.show', $dirigente) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                       title="Visualizar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openInfoModal('dirigente', {{ $dirigente->id }}, '{{ $dirigente->nome }}')"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-cyan-600 hover:bg-cyan-50 dark:hover:bg-cyan-500/10"
                                       title="Informações">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    @can('update', $dirigente)
                                        <button onclick="openEditDirigenteModal({
                                            id: {{ $dirigente->id }},
                                            nome: '{{ addslashes($dirigente->nome) }}',
                                            telefone: '{{ addslashes($dirigente->telefone ?? '') }}',
                                            genero: '{{ $dirigente->genero }}',
                                            data_nascimento: '{{ $dirigente->data_nascimento?->format('Y-m-d') ?? '' }}',
                                            foto_url: '{{ addslashes($dirigente->foto_url ?? '') }}',
                                            ativo: {{ $dirigente->ativo ? 'true' : 'false' }}
                                        })"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete', $dirigente)
                                        <button onclick="deleteDirigente({{ $dirigente->id }}, '{{ $dirigente->nome }}')"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                                title="Deletar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Nenhum dirigente encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dirigentes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.05]">
                {{ $dirigentes->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @if(count($dirigentes) > 0)
            <div class="flex flex-col gap-2">
                @can('create', App\Models\Dirigente::class)
                    <button onclick="openCreateDirigenteModal()"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Novo Dirigente
                    </button>
                @endcan
            </div>
        @endif

        @forelse($dirigentes as $dirigente)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $dirigente->nome }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ $dirigente->vinculos->first()?->entidade->nome ?? 'sem núcleo' }}</p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2
                        @if($dirigente->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $dirigente->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('dirigentes.show', $dirigente) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>

                    <button onclick="openInfoModal('dirigente', {{ $dirigente->id }}, '{{ $dirigente->nome }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-cyan-200 bg-white px-3 py-2 text-theme-xs font-medium text-cyan-600 hover:bg-cyan-50 dark:border-cyan-900 dark:bg-cyan-500/10 dark:text-cyan-400 dark:hover:bg-cyan-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Info
                    </button>

                    @can('update', $dirigente)
                        <button onclick="openEditDirigenteModal({
                            id: {{ $dirigente->id }},
                            nome: '{{ addslashes($dirigente->nome) }}',
                            telefone: '{{ addslashes($dirigente->telefone ?? '') }}',
                            genero: '{{ $dirigente->genero }}',
                            data_nascimento: '{{ $dirigente->data_nascimento?->format('Y-m-d') ?? '' }}',
                            foto_url: '{{ addslashes($dirigente->foto_url ?? '') }}',
                            ativo: {{ $dirigente->ativo ? 'true' : 'false' }}
                        })"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                    @endcan

                    @can('delete', $dirigente)
                        <button onclick="deleteDirigente({{ $dirigente->id }}, '{{ $dirigente->nome }}')"
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Deletar
                        </button>
                    @endcan
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500">
                Nenhum dirigente encontrado
            </div>
        @endforelse
    </div>

    <!-- Dirigente Modal -->
    <div id="dirigenteModal" class="hidden fixed inset-0 bg-black/50 z-[999999] flex items-center justify-center p-4 overflow-y-auto" onclick="if(event.target === this) closeDirigenteModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl my-8">
            <!-- Modal Header -->
            <div class="sticky top-0 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white" id="dirigenteModalTitle">Criar Novo Dirigente</h2>
                <button onclick="closeDirigenteModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <!-- Basic Info Form -->
                <form id="dirigenteForm" onsubmit="submitDirigenteForm(event); return false;" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
                        <input
                            type="text"
                            name="nome"
                            id="nome"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required
                        >
                        <span class="text-red-500 text-sm" id="nomeError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Telefone</label>
                        <input
                            type="tel"
                            name="telefone"
                            id="telefone"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="(00) 00000-0000"
                        >
                        <span class="text-red-500 text-sm" id="telefoneError"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Gênero</label>
                            <select
                                name="genero"
                                id="genero"
                                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                                <option value="">Selecione</option>
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                                <option value="outro">Outro</option>
                            </select>
                            <span class="text-red-500 text-sm" id="generoError"></span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data de Nascimento</label>
                            <input
                                type="date"
                                name="data_nascimento"
                                id="data_nascimento"
                                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                            <span class="text-red-500 text-sm" id="data_nascimentoError"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Foto</label>
                        <div class="flex flex-col gap-3">
                            <input
                                type="file"
                                name="foto"
                                id="foto"
                                accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                            <small class="text-gray-500">Máximo 2MB. Formatos: JPG, PNG, GIF</small>
                            <div id="fotoPreview" class="hidden">
                                <img id="fotoImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        </div>
                        <span class="text-red-500 text-sm" id="fotoError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Foto URL (alternativa)</label>
                        <input
                            type="url"
                            name="foto_url"
                            id="foto_url"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                        <span class="text-red-500 text-sm" id="foto_urlError"></span>
                    </div>

                    <div id="ativoContainer" style="display: none;">
                        <label class="flex items-center dark:text-gray-200">
                            <input type="checkbox" name="ativo" id="ativo" value="1" class="rounded dark:bg-gray-700">
                            <span class="ml-2">Dirigente ativo no sistema</span>
                        </label>
                    </div>
                </form>

                <!-- Vinculos Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">Núcleos e Secretarias</h3>

                    <!-- Vinculos Table -->
                    <div class="mb-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Nome</th>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Tipo</th>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Cargo</th>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="vinculosTableBody" class="divide-y dark:divide-gray-700">
                                <tr class="text-gray-500 dark:text-gray-400">
                                    <td colspan="4" class="px-4 py-3 text-center">Carregando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Vinculo Form -->
                    <form id="addVinculoForm" onsubmit="addVinculo(event); return false;" class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg space-y-3">
                        <h4 class="font-medium dark:text-white">Adicionar Vínculo</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-200">Núcleo/Secretaria</label>
                                <select
                                    id="entidade_id"
                                    name="entidade_id"
                                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                    required
                                >
                                    <option value="">Selecione...</option>
                                </select>
                                <span class="text-red-500 text-sm" id="entidade_idError"></span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-200">Cargo</label>
                                <select
                                    id="cargo"
                                    name="cargo"
                                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                    required
                                >
                                    <option value="">Selecione...</option>
                                    <option value="dirigente">Dirigente</option>
                                    <option value="coordenador">Coordenador</option>
                                </select>
                                <span class="text-red-500 text-sm" id="cargoError"></span>
                            </div>
                        </div>
                        <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Adicionar
                        </button>
                    </form>
                </div>

                <!-- Habilidades Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">Habilidades</h3>

                    <!-- Habilidades Table -->
                    <div class="mb-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Nome</th>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Nível</th>
                                    <th class="px-4 py-2 text-left dark:text-gray-300">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="habilidadesTableBody" class="divide-y dark:divide-gray-700">
                                <tr class="text-gray-500 dark:text-gray-400">
                                    <td colspan="3" class="px-4 py-3 text-center">Carregando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Habilidade Form -->
                    <form id="addHabilidadeForm" onsubmit="addHabilidade(event); return false;" class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg space-y-3">
                        <h4 class="font-medium dark:text-white">Adicionar Habilidade</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-200">Selecione a Secretaria</label>
                                <select
                                    id="secretaria_filter"
                                    name="secretaria_filter"
                                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                >
                                    <option value="">Selecione...</option>
                                </select>
                                <span class="text-red-500 text-sm" id="secretaria_filterError"></span>
                            </div>
                            <div id="habilidadeSelectContainer" style="display: none;">
                                <label class="block text-sm font-medium mb-1 dark:text-gray-200">Selecione a Habilidade</label>
                                <select
                                    id="habilidade_id"
                                    name="habilidade_id"
                                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                    required
                                >
                                    <option value="">Selecione...</option>
                                </select>
                                <span class="text-red-500 text-sm" id="habilidade_idError"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Nível</label>
                            <select
                                id="nivel"
                                name="nivel"
                                class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                required
                            >
                                <option value="">Selecione...</option>
                                <option value="iniciante">Iniciante</option>
                                <option value="basico">Básico</option>
                                <option value="intermediario">Intermediário</option>
                                <option value="experiente">Experiente</option>
                                <option value="profissional">Profissional</option>
                            </select>
                            <span class="text-red-500 text-sm" id="nivelError"></span>
                        </div>
                        <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Adicionar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-6 py-4 flex items-center justify-end gap-3">
                <button onclick="closeDirigenteModal()" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancelar
                </button>
                <button type="button" onclick="submitDirigenteForm()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Salvar Dirigente
                </button>
            </div>
        </div>
    </div>

    <!-- Info Modal -->
    <x-info-modal id="infoModal">
        <div id="infoModalContent" class="space-y-4">
            <!-- Filled dynamically via JavaScript -->
        </div>
    </x-info-modal>
</div>

<script>
let dirigenteEditId = null;
let selectedRows = [];
let selectAll = false;
const nucleosData = @json($nucleos);
const secretariasData = @json($secretarias);
let allHabilidades = [];

function handleSelectAll() {
    const allIds = document.querySelectorAll('[data-row-id]');
    selectAll = !selectAll;
    selectedRows = selectAll ? Array.from(allIds).map(row => parseInt(row.getAttribute('data-row-id'))) : [];

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectAllCheckmark = document.getElementById('selectAllCheckmarkIcon');
    if (selectAll) {
        selectAllCheckbox.classList.add('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
        selectAllCheckbox.classList.remove('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
        selectAllCheckmark.classList.remove('hidden');
    } else {
        selectAllCheckbox.classList.remove('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
        selectAllCheckbox.classList.add('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
        selectAllCheckmark.classList.add('hidden');
    }

    document.querySelectorAll('.checkbox-row').forEach(checkbox => {
        const checkmarkIcon = checkbox.querySelector('.checkmark-icon');
        if (selectAll) {
            checkbox.classList.add('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
            checkbox.classList.remove('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
            checkmarkIcon.classList.remove('hidden');
        } else {
            checkbox.classList.remove('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
            checkbox.classList.add('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
            checkmarkIcon.classList.add('hidden');
        }
    });
}

function handleRowSelect(id) {
    const checkbox = document.querySelector(`.checkbox-row[data-id="${id}"]`);
    const checkmarkIcon = checkbox.querySelector('.checkmark-icon');

    if (selectedRows.includes(id)) {
        selectedRows = selectedRows.filter(rowId => rowId !== id);
        checkbox.classList.remove('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
        checkbox.classList.add('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
        checkmarkIcon.classList.add('hidden');
    } else {
        selectedRows.push(id);
        checkbox.classList.add('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
        checkbox.classList.remove('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
        checkmarkIcon.classList.remove('hidden');
    }

    const allIds = document.querySelectorAll('[data-row-id]');
    selectAll = selectedRows.length === allIds.length;
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectAllCheckmark = document.getElementById('selectAllCheckmarkIcon');
    if (selectAll) {
        selectAllCheckbox.classList.add('border-blue-500', 'dark:border-blue-500', 'bg-blue-500');
        selectAllCheckbox.classList.remove('bg-white', 'dark:bg-white/0', 'border-gray-300', 'dark:border-gray-700');
        selectAllCheckmark.classList.remove('hidden');
    }
}

function deleteSelected() {
    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione pelo menos um dirigente',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
        return;
    }

    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja deletar ${selectedRows.length} dirigente(s)?`,
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
            deleteSelectedConfirmed();
        }
    });
}

function deleteSelectedConfirmed() {

    fetch('{{ route("dirigentes.delete-multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ids: selectedRows })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao deletar dirigentes',
                
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao deletar dirigentes',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    });
}

function deleteDirigente(id, nome) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja deletar ${nome}?`,
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
            deleteDirigenteConfirmed(id);
        }
    });
}

function deleteDirigenteConfirmed(id) {

    fetch(`/dirigentes/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function openCreateDirigenteModal() {
    dirigenteEditId = null;
    document.getElementById('dirigenteModalTitle').textContent = 'Criar Novo Dirigente';
    document.getElementById('dirigenteForm').reset();
    document.getElementById('ativoContainer').style.display = 'none';
    document.getElementById('vinculosTableBody').innerHTML = '<tr class="text-gray-500"><td colspan="4" class="px-4 py-3 text-center">Nenhum vínculo adicionado</td></tr>';
    document.getElementById('habilidadesTableBody').innerHTML = '<tr class="text-gray-500"><td colspan="3" class="px-4 py-3 text-center">Nenhuma habilidade adicionada</td></tr>';
    loadEntidades();
    loadHabilidadesSelect();
    document.getElementById('dirigenteModal').classList.remove('hidden');
}

function openEditDirigenteModal(data) {
    dirigenteEditId = data.id;

    const titleEl = document.getElementById('dirigenteModalTitle');
    const nomeEl = document.getElementById('nome');
    const telefoneEl = document.getElementById('telefone');
    const generoEl = document.getElementById('genero');
    const dataNascEl = document.getElementById('data_nascimento');
    const fotoEl = document.getElementById('foto_url');
    const ativoEl = document.getElementById('ativo');
    const ativoContainer = document.getElementById('ativoContainer');

    if (titleEl) titleEl.textContent = 'Editar Dirigente';
    if (nomeEl) nomeEl.value = data.nome;
    if (telefoneEl) telefoneEl.value = data.telefone;
    if (generoEl) generoEl.value = data.genero || '';
    if (dataNascEl) dataNascEl.value = data.data_nascimento;
    if (fotoEl) fotoEl.value = data.foto_url;
    if (ativoEl) ativoEl.checked = data.ativo;
    if (ativoContainer) ativoContainer.style.display = 'block';

    loadEntidades();
    loadHabilidadesSelect();
    loadVinculos(data.id);
    loadHabilidades(data.id);
    document.getElementById('dirigenteModal').classList.remove('hidden');
}

function closeDirigenteModal() {
    document.getElementById('dirigenteModal').classList.add('hidden');
    document.getElementById('dirigenteForm').reset();
    dirigenteEditId = null;
}

// Preview foto
document.getElementById('foto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('fotoPreview');
    const img = document.getElementById('fotoImg');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

async function submitDirigenteForm(event) {
    if (event) event.preventDefault();

    const form = document.getElementById('dirigenteForm');
    const formData = new FormData(form);

    try {
        const url = dirigenteEditId
            ? `/dirigentes/${dirigenteEditId}`
            : '/dirigentes';

        // Para PUT com FormData, precisamos adicionar _method
        if (dirigenteEditId) {
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: result.message,
                showConfirmButton: false,
                timer: 1500,
                
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: result.message || 'Erro ao salvar dirigente',
                
            });
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.getElementById(key + 'Error');
                    if (errorEl) {
                        errorEl.textContent = result.errors[key][0];
                    }
                });
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao salvar dirigente',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    }
}

function loadEntidades() {
    const select = document.getElementById('entidade_id');
    select.innerHTML = '<option value="">Selecione...</option>';

    nucleosData.forEach(nucleo => {
        const option = document.createElement('option');
        option.value = nucleo.id;
        option.textContent = `${nucleo.nome} (Núcleo)`;
        select.appendChild(option);
    });

    secretariasData.forEach(secretaria => {
        const option = document.createElement('option');
        option.value = secretaria.id;
        option.textContent = `${secretaria.nome} (Secretaria)`;
        select.appendChild(option);
    });
}

function loadHabilidadesSelect() {
    // Load secretarias filter
    const secretariaSelect = document.getElementById('secretaria_filter');
    secretariaSelect.innerHTML = '<option value="">Selecione...</option>';

    secretariasData.forEach(secretaria => {
        const option = document.createElement('option');
        option.value = secretaria.id;
        option.textContent = secretaria.nome;
        secretariaSelect.appendChild(option);
    });

    // Add change listener to secretaria select
    secretariaSelect.addEventListener('change', async function() {
        const habilidadeSelectContainer = document.getElementById('habilidadeSelectContainer');
        const habilidadeSelect = document.getElementById('habilidade_id');

        if (this.value) {
            // Show habilidade select
            habilidadeSelectContainer.style.display = 'block';
            habilidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            habilidadeSelect.disabled = true;

            try {
                // Fetch habilidades from the selected secretaria
                const response = await fetch(`/api/secretarias/${this.value}/habilidades`);
                const habilidades = await response.json();

                habilidadeSelect.innerHTML = '<option value="">Selecione...</option>';

                if (habilidades && habilidades.length > 0) {
                    habilidades.forEach(habilidade => {
                        const option = document.createElement('option');
                        option.value = habilidade.id;
                        option.textContent = habilidade.nome;
                        habilidadeSelect.appendChild(option);
                    });
                } else {
                    habilidadeSelect.innerHTML = '<option value="">Nenhuma habilidade disponível</option>';
                }
                habilidadeSelect.disabled = false;
            } catch (error) {
                console.error('Erro ao carregar habilidades:', error);
                habilidadeSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                habilidadeSelect.disabled = true;
            }
        } else {
            // Hide habilidade select
            habilidadeSelectContainer.style.display = 'none';
            habilidadeSelect.value = '';
        }
    });
}

async function loadVinculos(dirigenteId) {
    try {
        const response = await fetch(`/api/dirigentes/${dirigenteId}/vinculos`);
        const vinculos = await response.json();
        renderVinculos(vinculos);

        // Extract secretarias from vinculos and populate the habilidades form
        const secretariasFromVinculos = vinculos
            .filter(v => v.tipo_entidade === 'secretaria')
            .map(v => ({
                id: v.entidade_id,
                nome: v.entidade_nome
            }));

        // Populate the secretaria_filter select with only linked secretarias
        const secretariaSelect = document.getElementById('secretaria_filter');
        if (secretariaSelect) {
            secretariaSelect.innerHTML = '<option value="">Selecione...</option>';

            if (secretariasFromVinculos.length === 0) {
                secretariaSelect.innerHTML += '<option value="" disabled>Nenhuma secretaria vinculada</option>';
                secretariaSelect.disabled = true;
            } else {
                secretariasFromVinculos.forEach(s => {
                    const option = document.createElement('option');
                    option.value = s.id;
                    option.textContent = s.nome;
                    secretariaSelect.appendChild(option);
                });
                secretariaSelect.disabled = false;
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadHabilidades(dirigenteId) {
    try {
        const response = await fetch(`/api/dirigentes/${dirigenteId}/habilidades`);
        const habilidades = await response.json();
        renderHabilidades(habilidades);
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderVinculos(vinculos) {
    const tbody = document.getElementById('vinculosTableBody');

    if (vinculos.length === 0) {
        tbody.innerHTML = '<tr class="text-gray-500"><td colspan="4" class="px-4 py-3 text-center">Nenhum vínculo adicionado</td></tr>';
        return;
    }

    tbody.innerHTML = vinculos.map(v => `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-3 dark:text-gray-300">${v.entidade_nome}</td>
            <td class="px-4 py-3 dark:text-gray-300">${v.tipo_entidade}</td>
            <td class="px-4 py-3 dark:text-gray-300">${v.cargo_label || 'N/A'}</td>
            <td class="px-4 py-3">
                <div class="flex gap-2">
                    <button type="button" onclick="editarVinculo(${v.id}, '${v.entidade_nome}', '${v.cargo}')"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                            title="Editar">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button type="button" onclick="removerVinculo(${v.id})"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                            title="Remover">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderHabilidades(habilidades) {
    const tbody = document.getElementById('habilidadesTableBody');

    if (habilidades.length === 0) {
        tbody.innerHTML = '<tr class="text-gray-500"><td colspan="3" class="px-4 py-3 text-center">Nenhuma habilidade adicionada</td></tr>';
        return;
    }

    tbody.innerHTML = habilidades.map(h => `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-3 dark:text-gray-300">${h.nome}</td>
            <td class="px-4 py-3 dark:text-gray-300">${h.nivel_label}</td>
            <td class="px-4 py-3">
                <button type="button" onclick="removerHabilidade(${h.id})"
                        class="text-red-600 hover:text-red-800 text-sm font-medium">Remover</button>
            </td>
        </tr>
    `).join('');
}

async function addVinculo(event) {
    event.preventDefault();

    if (!dirigenteEditId) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Salve o dirigente antes de adicionar vínculos',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
        return;
    }

    const entidade_id = document.getElementById('entidade_id').value;
    const cargo = document.getElementById('cargo').value;

    if (!entidade_id || !cargo) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Preencha todos os campos',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
        return;
    }

    try {
        const response = await fetch(`/api/dirigentes/${dirigenteEditId}/vinculos`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ entidade_id, cargo })
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('addVinculoForm').reset();
            loadVinculos(dirigenteEditId);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: result.message || 'Erro ao adicionar vínculo',
                
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao adicionar vínculo',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    }
}

async function removerVinculo(vinculoId) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja remover este vínculo?',
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
            removerVinculoConfirmed(vinculoId);
        }
    });
}

async function removerVinculoConfirmed(vinculoId) {

    try {
        const response = await fetch(`/api/dirigentes/${dirigenteEditId}/vinculos/${vinculoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        const result = await response.json();

        if (result.success) {
            loadVinculos(dirigenteEditId);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: result.message || 'Erro ao remover vínculo',
                
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao remover vínculo',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    }
}

async function addHabilidade(event) {
    event.preventDefault();

    if (!dirigenteEditId) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Salve o dirigente antes de adicionar habilidades',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
        return;
    }

    const habilidade_id = document.getElementById('habilidade_id').value;
    const nivel = document.getElementById('nivel').value;

    if (!habilidade_id || !nivel) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Preencha todos os campos',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
        return;
    }

    try {
        const response = await fetch(`/api/dirigentes/${dirigenteEditId}/habilidades`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ habilidade_id, nivel })
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('addHabilidadeForm').reset();
            loadHabilidades(dirigenteEditId);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: result.message || 'Erro ao adicionar habilidade',
                
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao adicionar habilidade',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    }
}

async function removerHabilidade(habilidadeId) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja remover esta habilidade?',
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
            removerHabilidadeConfirmed(habilidadeId);
        }
    });
}

async function removerHabilidadeConfirmed(habilidadeId) {

    try {
        const response = await fetch(`/api/dirigentes/${dirigenteEditId}/habilidades/${habilidadeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        const result = await response.json();

        if (result.success) {
            loadHabilidades(dirigenteEditId);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: result.message || 'Erro ao remover habilidade',
                
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao remover habilidade',
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    }
}

function editarVinculo(vinculoId, nome, cargo) {
    // This would typically open an edit modal for the vinculo
    // For now, we can implement inline editing if needed
    console.log('Edit vinculo:', vinculoId, nome, cargo);
}
</script>
@endsection
