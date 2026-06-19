@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-sm text-gray-600 mb-1">{{ $nucleo->entidadePai?->nome ?? '-' }}</p>
            <h1 class="text-3xl font-bold">{{ $nucleo->nome }}</h1>
        </div>
        <div class="space-x-2">
            @can('update', $nucleo)
                <a href="#" onclick="editNucleo({{ $nucleo->id }}, '{{ $nucleo->nome }}', '{{ $nucleo->email }}', {{ $nucleo->entidade_pai_id }})" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Editar
                </a>
            @endcan
            @can('delete', $nucleo)
                <form action="{{ route('nucleos.destroy', $nucleo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
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

            @if($nucleo->getFotoUrl())
                <div class="mb-4 text-center">
                    <img
                        src="{{ $nucleo->getFotoUrl() }}"
                        alt="{{ $nucleo->nome }}"
                        class="w-48 h-48 object-cover rounded-lg shadow border border-gray-200 mx-auto"
                    >
                </div>
            @endif

            <div class="mb-4">
                <p class="text-sm text-gray-600">Diocese</p>
                <p class="font-semibold">
                    @if ($nucleo->entidadePai)
                        <a href="{{ route('dioceses.show', $nucleo->entidadePai) }}" class="text-blue-600 hover:underline">
                            {{ $nucleo->entidadePai->nome }}
                        </a>
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Email</p>
                <p>{{ $nucleo->email ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-2 py-1 rounded text-sm
                    @if($nucleo->ativo) bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Criado em</p>
                <p>{{ $nucleo->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Estatísticas</h2>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Total de Secretarias</p>
                <p class="text-2xl font-bold">{{ $nucleo->entidadesFilhas->count() }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Total de Dirigentes</p>
                <p class="text-2xl font-bold">{{ $nucleo->dirigenteVinculos->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Secretarias</h2>

        @if ($nucleo->entidadesFilhas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($nucleo->entidadesFilhas as $secretaria)
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-lg">{{ $secretaria->nome }}</h3>
                                <p class="text-xs text-gray-600">
                                    <span class="px-1 py-0.5 rounded text-xs font-medium
                                        @if($secretaria->tipo_secretaria->isAberta()) bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ $secretaria->tipo_secretaria->label() }}
                                    </span>
                                </p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($secretaria->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $secretaria->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $secretaria->email ?? 'Sem email' }}</p>
                        <a href="{{ route('secretarias.show', $secretaria) }}" class="text-blue-600 hover:underline text-sm">
                            Ver detalhes →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Nenhuma secretaria vinculada</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Dirigentes Vinculados</h2>

        @if ($nucleo->dirigenteVinculos->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Cargo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Papel</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nucleo->dirigenteVinculos as $vinculo)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $vinculo->dirigente->nome }}</td>
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
</div>

<!-- Modal para editar núcleo -->
<x-modal id="nucleos-form-modal" title="Editar Núcleo" formId="nucleo-form">
    <form id="nucleo-form" method="POST" action="{{ route('nucleos.update', $nucleo) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Diocese *</label>
            <select name="entidade_pai_id" id="nucleo-diocese" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione uma diocese</option>
                @foreach(\App\Models\Entidade::where('tipo_entidade', 'diocese')->ativas()->get() as $diocese)
                    <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm hidden" id="nucleo-diocese-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="nucleo-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="nucleo-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="nucleo-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="nucleo-email-error"></span>
        </div>
    </form>
</x-modal>

<script>
    function editNucleo(id, nome, email, dioceseId) {
        document.getElementById('nucleo-nome').value = nome;
        document.getElementById('nucleo-email').value = email;
        document.getElementById('nucleo-diocese').value = dioceseId;
        openModal('nucleos-form-modal');
    }
</script>
@endsection
