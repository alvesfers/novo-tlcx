@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Relatório de Eventos</h1>
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
        <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total de Eventos</p>
            <p class="text-2xl font-bold text-blue-600">{{ $resumo['total'] }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Tipos</p>
            <p class="text-2xl font-bold text-purple-600">{{ $resumo['por_tipo'] }}</p>
        </div>
        <div class="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Total de Participantes</p>
            <p class="text-2xl font-bold text-green-600">{{ $resumo['total_participantes'] }}</p>
        </div>
        <div class="bg-orange-50 rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm">Taxa de Presença</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($taxaPresenca, 1) }}%</p>
        </div>
    </div>

    <!-- Tabela de Eventos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Eventos</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Data Início</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Data Fim</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700">Participantes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eventos as $evento)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $evento->nome }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $evento->data_inicio?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $evento->data_fim?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-right text-gray-900">{{ $evento->participantes->count() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum evento encontrado</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Botões de Exportação -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('relatorios.export', ['tipo' => 'eventos', 'formato' => 'csv']) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Exportar CSV
        </a>
    </div>
</div>
@endsection
