@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">QR Code: {{ $dirigente->nome }}</h1>
    </div>

    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-4">
                <p class="text-gray-600">UUID: <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">{{ $dirigente->uuid }}</code></p>
            </div>

            <!-- QR Code -->
            <div class="mb-6 flex justify-center">
                <img src="{{ $qrCode }}" alt="QR Code para {{ $dirigente->nome }}" class="w-64 h-64 border border-gray-300 rounded-lg">
            </div>

            <div class="space-y-2 mb-6 text-sm text-gray-600">
                <p>Nome: <strong>{{ $dirigente->nome }}</strong></p>
                @if($dirigente->data_nascimento)
                    <p>Data Nascimento: <strong>{{ $dirigente->data_nascimento->format('d/m/Y') }}</strong></p>
                @endif
                @if($dirigente->telefone)
                    <p>Telefone: <strong>{{ $dirigente->telefone }}</strong></p>
                @endif
            </div>

            <!-- Botões de Ação -->
            <div class="space-y-2">
                <button onclick="window.print()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Imprimir
                </button>
                <a href="{{ route('dirigentes.show', $dirigente) }}" class="block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none;
        }
    }
</style>
@endsection
