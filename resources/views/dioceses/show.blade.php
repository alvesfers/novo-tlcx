@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $diocese->nome }}</h1>
        <div class="space-x-2">
            @can('update', $diocese)
                <a href="#" onclick="editDiocese({{ $diocese->id }}, '{{ $diocese->nome }}', '{{ $diocese->email }}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Editar
                </a>
            @endcan
            @can('delete', $diocese)
                <form action="{{ route('dioceses.destroy', $diocese) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
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

            @if($diocese->getFotoUrl())
                <div class="mb-4 text-center">
                    <img
                        src="{{ $diocese->getFotoUrl() }}"
                        alt="{{ $diocese->nome }}"
                        class="w-48 h-48 object-cover rounded-lg shadow border border-gray-200 mx-auto"
                    >
                </div>
            @endif

            <div class="mb-4">
                <p class="text-sm text-gray-600">Email</p>
                <p>{{ $diocese->email ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-2 py-1 rounded text-sm
                    @if($diocese->ativo) bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $diocese->ativo ? 'Ativa' : 'Inativa' }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Criada em</p>
                <p>{{ $diocese->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Estatísticas</h2>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Total de Núcleos</p>
                <p class="text-2xl font-bold">{{ $diocese->entidadesFilhas->count() }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600">Total de Dirigentes</p>
                <p class="text-2xl font-bold">{{ $diocese->dirigenteVinculos->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Núcleos</h2>

        @if ($diocese->entidadesFilhas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($diocese->entidadesFilhas as $nucleo)
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-lg">{{ $nucleo->nome }}</h3>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($nucleo->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $nucleo->email ?? 'Sem email' }}</p>
                        <a href="{{ route('nucleos.show', $nucleo) }}" class="text-blue-600 hover:underline text-sm">
                            Ver detalhes →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Nenhum núcleo vinculado</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Dirigentes Vinculados</h2>

        @if ($diocese->dirigenteVinculos->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Núcleo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Cargo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diocese->dirigenteVinculos as $vinculo)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $vinculo->dirigente->nome }}</td>
                            <td class="px-4 py-3 text-sm">{{ $vinculo->entidade->nome }}</td>
                            <td class="px-4 py-3 text-sm capitalize">{{ $vinculo->cargo }}</td>
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

<!-- Modal para editar diocese -->
<x-modal id="dioceses-form-modal" title="Editar Diocese" formId="diocese-form">
    <form id="diocese-form" method="POST" action="{{ route('dioceses.update', $diocese) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="diocese-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="diocese-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="diocese-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="diocese-email-error"></span>
        </div>
    </form>
</x-modal>

<script>
    function editDiocese(id, nome, email) {
        document.getElementById('diocese-nome').value = nome;
        document.getElementById('diocese-email').value = email;
        openModal('dioceses-form-modal');
    }
</script>
@endsection
