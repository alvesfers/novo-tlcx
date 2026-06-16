@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Resumo Financeiro</h1>
        <a href="{{ route('financeiro-movimentos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Saldo Atual</h2>
            <p class="text-4xl font-bold @if($saldoAtual >= 0) text-green-700 @else text-red-700 @endif">
                R$ {{ number_format($saldoAtual, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Mês Atual</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Entradas:</span>
                    <span class="font-semibold text-green-700">R$ {{ number_format($periodoAtual['entradas'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Saídas:</span>
                    <span class="font-semibold text-red-700">R$ {{ number_format($periodoAtual['saidas'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-600 font-semibold">Saldo:</span>
                    <span class="font-bold @if($periodoAtual['saldo'] >= 0) text-blue-700 @else text-red-700 @endif">
                        R$ {{ number_format($periodoAtual['saldo'], 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-6">Movimentações por Categoria (Mês Atual)</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Categoria</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Tipo</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $cat)
                    @if($cat['total'] > 0)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $cat['nome'] }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm font-semibold @if($cat['tipo'] === 'entrada') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $cat['tipo'] === 'entrada' ? 'Entrada' : 'Saída' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold @if($cat['tipo'] === 'entrada') text-green-700 @else text-red-700 @endif">
                            R$ {{ number_format($cat['total'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma movimentação no período
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
