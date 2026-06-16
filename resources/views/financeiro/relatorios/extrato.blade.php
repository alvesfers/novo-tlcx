@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Extrato Financeiro</h1>
        <a href="{{ route('financeiro-movimentos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <p class="text-gray-600 text-sm font-semibold">Entradas</p>
                <p class="text-3xl font-bold text-green-700">R$ {{ number_format($entradas, 2, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <p class="text-gray-600 text-sm font-semibold">Saídas</p>
                <p class="text-3xl font-bold text-red-700">R$ {{ number_format($saidas, 2, ',', '.') }}</p>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-gray-600 text-sm font-semibold">Saldo</p>
                <p class="text-3xl font-bold @if($saldo >= 0) text-blue-700 @else text-red-700 @endif">
                    R$ {{ number_format($saldo, 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Período: {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</h2>
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
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Nenhuma movimentação no período
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
