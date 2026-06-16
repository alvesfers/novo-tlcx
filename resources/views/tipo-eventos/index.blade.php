@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tipos de Eventos</h1>
        <a href="{{ route('tipo-eventos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Tipo
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Descrição</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tiposEvento as $tipo)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $tipo->nome }}</td>
                    <td class="px-6 py-4">{{ Str::limit($tipo->descricao, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($tipo->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('tipo-eventos.edit', $tipo) }}" class="text-blue-600 hover:text-blue-800 mr-4">Editar</a>
                        <form method="POST" action="{{ route('tipo-eventos.destroy', $tipo) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Deletar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Nenhum tipo de evento encontrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tiposEvento->links() }}
    </div>
</div>
@endsection
