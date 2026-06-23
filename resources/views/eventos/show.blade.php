@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">{{ $evento->nome }}</h1>
            <p class="text-gray-600 mt-1">{{ $evento->entidadeCriadora->nome }}</p>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- Informações Gerais do Evento -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-6">Informações Gerais</h2>
        <div class="grid grid-cols-3 gap-6 mb-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Tipo</p>
                <p class="text-lg font-medium">{{ $evento->tipoEvento->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Data Início</p>
                <p class="text-lg font-medium">{{ $evento->data_inicio->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Data Fim</p>
                <p class="text-lg font-medium">{{ $evento->data_fim ? $evento->data_fim->format('d/m/Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Escopo</p>
                <p class="text-lg font-medium">{{ $evento->escopo->label() }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Status</p>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if ($evento->isPublicado()) bg-blue-100 text-blue-800
                        @elseif ($evento->isRascunho()) bg-yellow-100 text-yellow-800
                        @elseif ($evento->isEncerrado()) bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">
                        {{ $evento->status->label() }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Ativo</p>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold @if($evento->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ $evento->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Casa de Retiro</p>
                <p class="text-lg font-medium">{{ $evento->casaDeRetiro->nome ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Fornecedor de Camisetas</p>
                <p class="text-lg font-medium">{{ $evento->fornecedorCamisetas->nome ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Local</p>
                <p class="text-lg font-medium">{{ $evento->local ?: '-' }}</p>
            </div>
        </div>

        @if ($evento->descricao)
        <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide mb-2">Descrição</p>
            <p class="text-lg">{{ $evento->descricao }}</p>
        </div>
        @endif
    </div>

    <!-- Estatísticas de Participantes -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Resumo de Participantes</h2>
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Dirigentes Chamados</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['dirigentes_chamados'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Dirigentes Confirmados</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['dirigentes_confirmados'] }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    @if ($stats['dirigentes_chamados'] > 0)
                        {{ round(($stats['dirigentes_confirmados'] / $stats['dirigentes_chamados']) * 100) }}% de confirmação
                    @else
                        0% de confirmação
                    @endif
                </p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Externos Total</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['externos_total'] }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Externos Confirmados</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['externos_confirmados'] }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    @if ($stats['externos_total'] > 0)
                        {{ round(($stats['externos_confirmados'] / $stats['externos_total']) * 100) }}% de confirmação
                    @else
                        0% de confirmação
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide mb-4">Total Geral</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex justify-between items-center">
                    <span class="font-medium">Total de Participantes:</span>
                    <span class="text-2xl font-bold">{{ $stats['dirigentes_chamados'] + $stats['externos_total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">Total Confirmados:</span>
                    <span class="text-2xl font-bold">{{ $stats['dirigentes_confirmados'] + $stats['externos_confirmados'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
