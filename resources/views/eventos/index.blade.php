@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Eventos</h1>
        <a href="{{ route('eventos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Evento
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Entidade Criadora</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Data Início</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Escopo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eventos as $evento)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $evento->nome }}</td>
                    <td class="px-6 py-4">{{ $evento->tipoEvento->nome }}</td>
                    <td class="px-6 py-4">{{ $evento->entidadeCriadora->nome }}</td>
                    <td class="px-6 py-4">{{ $evento->data_inicio->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            @if ($evento->isPublicado()) bg-blue-100 text-blue-800
                            @elseif ($evento->isRascunho()) bg-yellow-100 text-yellow-800
                            @elseif ($evento->isEncerrado()) bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">
                            {{ $evento->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $evento->escopo->label() }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('eventos.show', $evento) }}" class="text-blue-600 hover:text-blue-800 mr-4">Ver</a>
                        <a href="{{ route('eventos.edit', $evento) }}" class="text-blue-600 hover:text-blue-800 mr-4">Editar</a>
                        <form method="POST" action="{{ route('eventos.destroy', $evento) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Deletar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Nenhum evento encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $eventos->links() }}
    </div>
</div>
@endsection
