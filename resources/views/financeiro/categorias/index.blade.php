@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Categorias Financeiras</h1>
        <a href="{{ route('financeiro-categorias.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Nova Categoria
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $categoria->nome }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($categoria->tipo->value === 'entrada') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $categoria->tipo->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($categoria->ativo) bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $categoria->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('financeiro-categorias.edit', $categoria) }}" class="text-blue-600 hover:text-blue-800 mr-4">Editar</a>
                        <form method="POST" action="{{ route('financeiro-categorias.destroy', $categoria) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Deletar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma categoria encontrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categorias->links() }}
    </div>
</div>
@endsection
