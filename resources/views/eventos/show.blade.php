@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">{{ $evento->nome }}</h1>
            <p class="text-gray-600 mt-1">{{ $evento->entidadeCriadora->nome }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('eventos.edit', $evento) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('eventos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- Detalhes do Evento -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Detalhes do Evento</h2>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Tipo</p>
                <p class="text-lg">{{ $evento->tipoEvento->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Entidade Criadora</p>
                <p class="text-lg">{{ $evento->entidadeCriadora->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Data Início</p>
                <p class="text-lg">{{ $evento->data_inicio->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Data Fim</p>
                <p class="text-lg">{{ $evento->data_fim ? $evento->data_fim->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Status</p>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-sm font-semibold
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
                <p class="text-gray-600 text-sm font-semibold">Escopo</p>
                <p class="text-lg">{{ $evento->escopo->label() }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Local</p>
                <p class="text-lg">{{ $evento->local ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Ativo</p>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-sm font-semibold @if($evento->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ $evento->ativo ? 'Sim' : 'Não' }}
                    </span>
                </p>
            </div>
        </div>
        @if ($evento->descricao)
        <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm font-semibold">Descrição</p>
            <p class="text-lg">{{ $evento->descricao }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
