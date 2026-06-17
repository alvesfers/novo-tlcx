@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Relatório Financeiro</h1>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Entradas</p>
            <p class="text-2xl font-bold text-green-600">R$ {{ number_format($resumo['entradas'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Saídas</p>
            <p class="text-2xl font-bold text-red-600">R$ {{ number_format($resumo['saidas'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Saldo</p>
            <p class="text-2xl font-bold text-blue-600">R$ {{ number_format($resumo['saldo'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg shadow-md p-6 border-l-4 border-gray-500">
            <p class="text-gray-600 text-sm">Movimentações</p>
            <p class="text-2xl font-bold text-gray-600">{{ $resumo['total_movimentos'] }}</p>
        </div>
    </div>

    <!-- Tabela de Movimentações -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Movimentações</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Data</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Categoria</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descrição</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimentos as $movimento)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $movimento->data_movimento->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $movimento->categoria->nome }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $movimento->descricao }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs font-medium
                            {{ $movimento->tipo->value === 'entrada' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ ucfirst($movimento->tipo->value) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">
                        {{ $movimento->tipo === 'entrada' ? '+' : '-' }} R$ {{ number_format($movimento->valor, 2, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhuma movimentação encontrada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Gráfico por Categoria -->
    @if($porCategoria->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Por Categoria</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($porCategoria as $categoria => $dados)
            <div class="border rounded-lg p-4">
                <p class="font-medium text-gray-700">{{ $categoria }}</p>
                <p class="text-2xl font-bold text-blue-600">R$ {{ number_format($dados['total'], 2, ',', '.') }}</p>
                <p class="text-sm text-gray-500">{{ $dados['count'] }} movimentação(ões)</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Botões de Exportação -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('relatorios.export', ['tipo' => 'financeiro', 'formato' => 'csv']) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Exportar CSV
        </a>
    </div>
</div>
@endsection
