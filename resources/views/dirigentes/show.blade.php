@extends('layouts.app')

@section('content')
<div x-data="dirigenteShowManager()" class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $dirigente->nome }}</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dirigentes.edit', $dirigente) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                <form action="{{ route('dirigentes.destroy', $dirigente) }}" method="POST" class="inline"
                      @submit.prevent="deleteDirigente($event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white text-red-600 px-4 py-2 text-sm font-medium hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-950 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Deletar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Informações Pessoais Card -->
        <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Pessoais</h2>

            @if($dirigente->foto_arquivo || $dirigente->foto_url)
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ $dirigente->getFotoUrl() }}"
                        alt="{{ $dirigente->nome }}"
                        class="w-40 h-40 object-cover rounded-xl shadow-md border border-gray-200 dark:border-gray-700"
                    >
                </div>
            @endif

            <div class="space-y-5">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">UUID</p>
                    <div class="flex items-center gap-2">
                        <p class="font-mono text-sm text-gray-900 dark:text-gray-200">{{ $dirigente->uuid }}</p>
                        <a href="{{ route('dirigentes.qrcode', $dirigente) }}"
                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            QR Code
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Telefone</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $dirigente->telefone ?? 'Não informado' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Gênero</p>
                    <p class="text-gray-900 dark:text-white font-medium">
                        @if ($dirigente->genero === 'm')
                            Masculino
                        @elseif ($dirigente->genero === 'f')
                            Feminino
                        @elseif ($dirigente->genero === 'outro')
                            Outro
                        @else
                            Não informado
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Data de Nascimento</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $dirigente->data_nascimento?->format('d/m/Y') ?? 'Não informada' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        @if($dirigente->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $dirigente->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Criado em</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $dirigente->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Vínculo Principal Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Vínculo Principal</h2>

            @php
                $vinculoPrincipal = $dirigente->getVinculoPrincipal();
                $nucleoPrincipal = $dirigente->getNucleoPrincipal();
            @endphp

            @if ($vinculoPrincipal && $nucleoPrincipal)
                <div class="space-y-5">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Núcleo</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $nucleoPrincipal->nome }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Diocese</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $nucleoPrincipal->entidadePai?->nome ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cargo</p>
                        <p class="text-gray-900 dark:text-white font-medium capitalize">{{ $vinculoPrincipal->cargo }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Papel</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $vinculoPrincipal->papel ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Data de Início</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $vinculoPrincipal->data_inicio?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Sem vínculo principal</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Vínculos Adicionais Section -->
    <div x-data="vinculosManager()" class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05] flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Vínculos Adicionais</h2>
            <button onclick="openModal('vinculoModal', false, { dirigente_id: {{ $dirigente->id }} })"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-3 py-2 text-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar
            </button>
        </div>

        <div class="overflow-x-auto">
            @php
                $vinculosAdicionais = $dirigente->vinculos()
                    ->where(function ($q) {
                        $q->where('tipo_vinculo', 'adicional')->orWhere('tipo_vinculo', 'coordenacao');
                    })
                    ->with('entidade')
                    ->get();
            @endphp

            @if ($vinculosAdicionais->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Entidade</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tipo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Cargo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Papel</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vinculosAdicionais as $vinculo)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $vinculo->entidade->nome }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $vinculo->entidade->getHierarquiaCompleta() }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                        @if($vinculo->isCoordenacao())
                                            bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400
                                        @else
                                            bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400
                                        @endif">
                                        {{ $vinculo->tipo_vinculo->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $vinculo->cargo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $vinculo->papel ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                        @if($vinculo->ativo)
                                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                        @else
                                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                        @endif">
                                        {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openModal('vinculoModal', true, {
                                            id: {{ $vinculo->id }},
                                            dirigente_id: {{ $dirigente->id }},
                                            tipo_vinculo: '{{ $vinculo->tipo_vinculo->value }}',
                                            entidade_id: {{ $vinculo->entidade_id }},
                                            cargo: '{{ addslashes((string)$vinculo->cargo->value) }}',
                                            papel: '{{ addslashes($vinculo->papel ?? '') }}',
                                            data_inicio: '{{ $vinculo->data_inicio?->format('Y-m-d') ?? '' }}',
                                            data_fim: '{{ $vinculo->data_fim?->format('Y-m-d') ?? '' }}',
                                            ativo: {{ $vinculo->ativo ? 'true' : 'false' }}
                                        })"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('dirigentes.vinculos.destroy', [$dirigente, $vinculo]) }}"
                                            method="POST" class="inline" @submit.prevent="deleteItem($event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition" title="Deletar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhum vínculo adicional</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Habilidades Section -->
    <div class="mt-8">
        <x-dirigente-habilidades-form :dirigente="$dirigente" />
    </div>

    <!-- Modal -->
    <x-modal-form
        id="vinculoModal"
        title="Criar Novo Vínculo"
        :resource="'dirigentes.vinculos'"
        size="md"
        :nested="true"
        :nestedPath="'dirigente'"
    >
        <input type="hidden" name="dirigente_id" id="vinculoModaldirigente_id">

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Vínculo *</label>
            <select
                name="tipo_vinculo"
                id="vinculoModaltipo_vinculo"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
                data-edit-only
            >
                <option value="">Selecione</option>
                <option value="adicional">Adicional</option>
                <option value="coordenacao">Coordenação</option>
            </select>
            <span class="text-red-500 text-sm" id="vinculoModaltipo_vinculoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Entidade *</label>
            <select
                name="entidade_id"
                id="vinculoModalentidade_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
                data-edit-only
            >
                <option value="">Selecione uma entidade</option>
            </select>
            <span class="text-red-500 text-sm" id="vinculoModalentidade_idError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Cargo *</label>
            <select
                name="cargo"
                id="vinculoModalcargo"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione</option>
                <option value="dirigente">Dirigente</option>
                <option value="coordenador">Coordenador</option>
            </select>
            <span class="text-red-500 text-sm" id="vinculoModalcargoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Papel</label>
            <input
                type="text"
                name="papel"
                id="vinculoModalpapel"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Ex: Líder de Jovens, Tesoureiro"
            >
            <span class="text-red-500 text-sm" id="vinculoModalpapelError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data de Início</label>
            <input
                type="date"
                name="data_inicio"
                id="vinculoModaldata_inicio"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="vinculoModaldata_inicioError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Data de Fim</label>
            <input
                type="date"
                name="data_fim"
                id="vinculoModaldata_fim"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
            <span class="text-red-500 text-sm" id="vinculoModaldata_fimError"></span>
        </div>

        <div data-edit-only style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
            <label class="flex items-center dark:text-gray-200">
                <input type="checkbox" name="ativo" id="vinculoModalativo" value="1" class="rounded dark:bg-gray-700">
                <span class="ml-2">Vínculo ativo no sistema</span>
            </label>
        </div>
    </x-modal-form>
</div>

<script>
    function dirigenteShowManager() {
        return {
            deleteDirigente(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar este dirigente? Esta ação não pode ser desfeita.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    zIndex: 99999
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });
            }
        };
    }

    function vinculosManager() {
        let entidadesData = { nucleos: [], secretarias: [], dioceses: [] };

        async function loadEntidades() {
            try {
                const response = await fetch('{{ route("dirigentes.vinculos.create", $dirigente) }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const data = await response.json();
                entidadesData = data;
            } catch (error) {
                console.error('Error loading entidades:', error);
            }
        }

        loadEntidades();

        return {
            deleteItem(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar este vínculo?',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    zIndex: 99999
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.closest('form').submit();
                    }
                });
            }
        };
    }
</script>
@endsection
