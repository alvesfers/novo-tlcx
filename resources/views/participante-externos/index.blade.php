@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Participantes Externos</h1>
        <a href="{{ route('participante-externos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Participante
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Telefone</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Documento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($participantes as $participante)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $participante->nome }}</td>
                    <td class="px-6 py-4">{{ $participante->email ?: '-' }}</td>
                    <td class="px-6 py-4">{{ $participante->telefone ?: '-' }}</td>
                    <td class="px-6 py-4">{{ $participante->documento ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('participante-externos.show', $participante) }}" class="text-blue-600 hover:text-blue-800 mr-4">Ver</a>
                        <a href="{{ route('participante-externos.edit', $participante) }}" class="text-blue-600 hover:text-blue-800 mr-4">Editar</a>
                        <form method="POST" action="{{ route('participante-externos.destroy', $participante) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Deletar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Nenhum participante encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $participantes->links() }}
    </div>
</div>
@endsection
