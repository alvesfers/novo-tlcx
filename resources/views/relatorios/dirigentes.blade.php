@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Relatório de Dirigentes</h1>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total</p>
            <p class="text-2xl font-bold text-blue-600">{{ $resumo['total'] }}</p>
        </div>
        <div class="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Ativos</p>
            <p class="text-2xl font-bold text-green-600">{{ $resumo['ativos'] }}</p>
        </div>
        <div class="bg-red-50 rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Inativos</p>
            <p class="text-2xl font-bold text-red-600">{{ $resumo['inativos'] }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Taxa Ativa</p>
            <p class="text-2xl font-bold text-purple-600">
                {{ $resumo['total'] > 0 ? number_format(($resumo['ativos'] / $resumo['total']) * 100, 1) : 0 }}%
            </p>
        </div>
    </div>

    <!-- Tabela de Dirigentes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Dirigentes</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">UUID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Data Criação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dirigentes as $dirigente)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $dirigente->nome }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $dirigente->uuid }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs font-medium
                            {{ $dirigente->ativo ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $dirigente->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $dirigente->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum dirigente encontrado</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Distribuição por Entidade -->
    @if(count($porEntidade) > 0)
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribuição por Entidade</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($porEntidade as $entidade => $count)
            <div class="border rounded-lg p-4">
                <p class="font-medium text-gray-700">{{ $entidade }}</p>
                <p class="text-2xl font-bold text-blue-600">{{ $count }}</p>
                <p class="text-sm text-gray-500">dirigentes</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Distribuição por Cargo -->
    @if(count($porCargo) > 0)
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribuição por Cargo</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($porCargo as $cargo => $count)
            <div class="border rounded-lg p-4">
                <p class="font-medium text-gray-700 capitalize">{{ $cargo }}</p>
                <p class="text-2xl font-bold text-purple-600">{{ $count }}</p>
                <p class="text-sm text-gray-500">dirigentes</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Botões de Exportação -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('relatorios.export', ['tipo' => 'dirigentes', 'formato' => 'csv']) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Exportar CSV
        </a>
    </div>
</div>
@endsection
