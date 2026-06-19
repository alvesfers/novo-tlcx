@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $dirigente->nome }}</h1>
        <div class="space-x-2">
            <a href="{{ route('dirigentes.edit', $dirigente) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <form action="{{ route('dirigentes.destroy', $dirigente) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Deletar
                </button>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Informações Pessoais</h2>

            @if($dirigente->foto_arquivo || $dirigente->foto_url)
                <div class="mb-4 text-center">
                    <img
                        src="{{ $dirigente->getFotoUrl() }}"
                        alt="{{ $dirigente->nome }}"
                        class="w-48 h-48 object-cover rounded-lg shadow border border-gray-200 mx-auto"
                    >
                </div>
            @endif

            <div class="mb-4">
                <p class="text-sm text-gray-600">UUID</p>
                <div class="flex items-center gap-2">
                    <p class="font-mono text-sm">{{ $dirigente->uuid }}</p>
                    <a href="{{ route('dirigentes.qrcode', $dirigente) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver QR Code
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Telefone</p>
                <p>{{ $dirigente->telefone ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Gênero</p>
                <p>
                    @if ($dirigente->genero === 'm')
                        Masculino
                    @elseif ($dirigente->genero === 'f')
                        Feminino
                    @elseif ($dirigente->genero === 'outro')
                        Outro
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Data de Nascimento</p>
                <p>{{ $dirigente->data_nascimento?->format('d/m/Y') ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-2 py-1 rounded text-sm
                    @if($dirigente->ativo) bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $dirigente->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Criado em</p>
                <p>{{ $dirigente->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vínculo Principal</h2>

            @php
                $vinculoPrincipal = $dirigente->getVinculoPrincipal();
                $nucleoPrincipal = $dirigente->getNucleoPrincipal();
            @endphp

            @if ($vinculoPrincipal && $nucleoPrincipal)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Núcleo</p>
                    <p class="font-semibold">{{ $nucleoPrincipal->nome }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Diocese</p>
                    <p>{{ $nucleoPrincipal->entidadePai?->nome ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Cargo</p>
                    <p class="capitalize">{{ $vinculoPrincipal->cargo }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Papel</p>
                    <p>{{ $vinculoPrincipal->papel ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Data de Início</p>
                    <p>{{ $vinculoPrincipal->data_inicio?->format('d/m/Y') ?? '-' }}</p>
                </div>
            @else
                <p class="text-red-600">Sem vínculo principal</p>
            @endif
        </div>
    </div>

    <div x-data="vinculosManager()" class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Vínculos Adicionais</h2>
            <button onclick="openModal('vinculoModal', false, { dirigente_id: {{ $dirigente->id }} })"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                Adicionar Vínculo
            </button>
        </div>

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
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Entidade</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Cargo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Papel</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vinculosAdicionais as $vinculo)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <strong>{{ $vinculo->entidade->nome }}</strong>
                                <br>
                                <small class="text-gray-600">{{ $vinculo->entidade->getHierarquiaCompleta() }}</small>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-sm font-medium
                                    @if($vinculo->isCoordenacao()) bg-purple-100 text-purple-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $vinculo->tipo_vinculo->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">{{ $vinculo->cargo }}</td>
                            <td class="px-4 py-3 text-sm">{{ $vinculo->papel ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-sm
                                    @if($vinculo->ativo) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <button onclick="openModal('vinculoModal', true, {
                                    id: {{ $vinculo->id }},
                                    dirigente_id: {{ $dirigente->id }},
                                    tipo_vinculo: '{{ $vinculo->tipo_vinculo->value }}',
                                    entidade_id: {{ $vinculo->entidade_id }},
                                    cargo: '{{ $vinculo->cargo }}',
                                    papel: '{{ addslashes($vinculo->papel ?? '') }}',
                                    data_inicio: '{{ $vinculo->data_inicio?->format('Y-m-d') ?? '' }}',
                                    data_fim: '{{ $vinculo->data_fim?->format('Y-m-d') ?? '' }}',
                                    ativo: {{ $vinculo->ativo ? 'true' : 'false' }}
                                })"
                                    class="text-blue-600 hover:underline">Editar</button>
                                <form action="{{ route('dirigentes.vinculos.destroy', [$dirigente, $vinculo]) }}"
                                    method="POST" class="inline" @submit.prevent="deleteItem($event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Nenhum vínculo adicional</p>
        @endif
    </div>

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

    <script>
        function vinculosManager() {
            let entidadesData = { nucleos: [], secretarias: [], dioceses: [] };

            // Load entidades data on page load
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
