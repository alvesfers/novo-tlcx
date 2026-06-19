@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-sm text-gray-600 mb-1">{{ $secretaria->entidadePai?->nome ?? '-' }}</p>
            <h1 class="text-3xl font-bold">{{ $secretaria->nome }}</h1>
        </div>
        <div class="space-x-2">
            @can('update', $secretaria)
                <a href="#" onclick="editSecretaria({{ $secretaria->id }}, '{{ $secretaria->nome }}', '{{ $secretaria->email }}', {{ $secretaria->entidade_pai_id }}, '{{ $secretaria->tipo_secretaria }}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Editar
                </a>
            @endcan
            @can('delete', $secretaria)
                <form action="{{ route('secretarias.destroy', $secretaria) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Deletar
                    </button>
                </form>
            @endcan
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
            <h2 class="text-xl font-semibold mb-4">Informações</h2>

            @if($secretaria->getFotoUrl())
                <div class="mb-4 text-center">
                    <img
                        src="{{ $secretaria->getFotoUrl() }}"
                        alt="{{ $secretaria->nome }}"
                        class="w-48 h-48 object-cover rounded-lg shadow border border-gray-200 mx-auto"
                    >
                </div>
            @endif

            <div class="mb-4">
                <p class="text-sm text-gray-600">Núcleo</p>
                <p class="font-semibold">
                    @if ($secretaria->entidadePai)
                        <a href="{{ route('nucleos.show', $secretaria->entidadePai) }}" class="text-blue-600 hover:underline">
                            {{ $secretaria->entidadePai->nome }}
                        </a>
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Tipo</p>
                <span class="px-2 py-1 rounded text-sm font-medium
                    @if($secretaria->tipo_secretaria->isAberta()) bg-blue-100 text-blue-800
                    @else bg-purple-100 text-purple-800
                    @endif">
                    {{ $secretaria->tipo_secretaria->label() }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Email</p>
                <p>{{ $secretaria->email ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-2 py-1 rounded text-sm
                    @if($secretaria->ativo) bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $secretaria->ativo ? 'Ativa' : 'Inativa' }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Criada em</p>
                <p>{{ $secretaria->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Estatísticas</h2>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Total de Dirigentes</p>
                <p class="text-2xl font-bold">{{ $secretaria->dirigenteVinculos->count() }}</p>
            </div>

            <div class="mt-8">
                <p class="text-sm text-gray-600">Hierarquia Completa</p>
                <p class="text-sm font-mono">{{ $secretaria->getHierarquiaCompleta() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Dirigentes Vinculados</h2>

        @if ($secretaria->dirigenteVinculos->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Cargo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Papel</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Tipo de Vínculo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($secretaria->dirigenteVinculos as $vinculo)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $vinculo->dirigente->nome }}</td>
                            <td class="px-4 py-3 text-sm capitalize">{{ $vinculo->cargo }}</td>
                            <td class="px-4 py-3 text-sm">{{ $vinculo->papel ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-sm font-medium
                                    @if($vinculo->isCoordenacao()) bg-purple-100 text-purple-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $vinculo->tipo_vinculo->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-sm
                                    @if($vinculo->ativo) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('dirigentes.show', $vinculo->dirigente) }}" class="text-blue-600 hover:underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Nenhum dirigente vinculado</p>
        @endif
    </div>

    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Habilidades</h2>

        @if ($secretaria->habilidades->count() > 0)
            <div class="mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Habilidade</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Descrição</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($secretaria->habilidades as $habilidade)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">{{ $habilidade->nome }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $habilidade->descricao ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-sm
                                            @if($habilidade->ativo) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $habilidade->ativo ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm space-x-2">
                                        <button type="button" class="text-blue-600 hover:underline" onclick="editarHabilidade({{ $habilidade->id }}, '{{ $habilidade->nome }}', '{{ addslashes($habilidade->descricao ?? '') }}', {{ $habilidade->ativo ? 'true' : 'false' }})">
                                            Editar
                                        </button>
                                        <form action="{{ route('habilidades.destroy', $habilidade) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">
                                                Deletar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-gray-500 mb-6">Nenhuma habilidade registrada ainda.</p>
        @endif

        <!-- Formulário para adicionar habilidade -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Adicionar Habilidade</h3>
            <form action="{{ route('habilidades.store', $secretaria) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nome da Habilidade *</label>
                        <input type="text" name="nome" class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Violão" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Descrição</label>
                        <input type="text" name="descricao" class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Habilidade em tocar violão">
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar Habilidade
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar secretaria -->
<x-modal id="secretarias-form-modal" title="Editar Secretaria" formId="secretaria-form">
    <form id="secretaria-form" method="POST" action="{{ route('secretarias.update', $secretaria) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Núcleo *</label>
            <select name="entidade_pai_id" id="secretaria-nucleo" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione um núcleo</option>
                @foreach(\App\Models\Entidade::where('tipo_entidade', 'nucleo')->ativas()->get() as $nucleo)
                    <option value="{{ $nucleo->id }}">{{ $nucleo->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm hidden" id="secretaria-nucleo-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="secretaria-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="secretaria-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tipo *</label>
            <select name="tipo_secretaria" id="secretaria-tipo" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione um tipo</option>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </select>
            <span class="text-red-500 text-sm hidden" id="secretaria-tipo-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="secretaria-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="secretaria-email-error"></span>
        </div>
    </form>
</x-modal>

<script>
    function editSecretaria(id, nome, email, nucleoId, tipo) {
        document.getElementById('secretaria-nome').value = nome;
        document.getElementById('secretaria-email').value = email;
        document.getElementById('secretaria-nucleo').value = nucleoId;
        document.getElementById('secretaria-tipo').value = tipo;
        openModal('secretarias-form-modal');
    }
</script>
@endsection
