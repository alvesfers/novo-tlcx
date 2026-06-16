@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Movimentações Financeiras</h1>
        <a href="{{ route('financeiro-movimentos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Movimento
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

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('financeiro-movimentos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="data_inicio" class="block text-sm font-semibold mb-2">Data Inicial</label>
                <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label for="data_fim" class="block text-sm font-semibold mb-2">Data Final</label>
                <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label for="tipo" class="block text-sm font-semibold mb-2">Tipo</label>
                <select id="tipo" name="tipo" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Todos</option>
                    <option value="entrada" @selected(request('tipo') === 'entrada')>Entrada</option>
                    <option value="saida" @selected(request('tipo') === 'saida')>Saída</option>
                </select>
            </div>
            <div>
                <label for="categoria_id" class="block text-sm font-semibold mb-2">Categoria</label>
                <select id="categoria_id" name="categoria_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Todas</option>
                    @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" @selected(request('categoria_id') === (string)$cat->id)>{{ $cat->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filtrar
                </button>
                <a href="{{ route('financeiro-movimentos.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Data</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Descrição</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Categoria</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tipo</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold">Valor</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movimentos as $movimento)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $movimento->data_movimento->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $movimento->descricao }}</td>
                    <td class="px-6 py-4">{{ $movimento->categoria->nome }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($movimento->tipo->value === 'entrada') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $movimento->tipo->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold @if($movimento->tipo->value === 'entrada') text-green-700 @else text-red-700 @endif">
                        @if($movimento->tipo->value === 'entrada')
                        +
                        @else
                        -
                        @endif
                        R$ {{ number_format($movimento->valor, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('financeiro-movimentos.edit', $movimento) }}" class="text-blue-600 hover:text-blue-800 mr-4">Editar</a>
                        <form method="POST" action="{{ route('financeiro-movimentos.destroy', $movimento) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Deletar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma movimentação encontrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $movimentos->links() }}
    </div>
</div>
@endsection
