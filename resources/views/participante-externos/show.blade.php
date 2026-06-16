@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $participanteExterno->nome }}</h1>
        <div>
            <a href="{{ route('participante-externos.edit', $participanteExterno) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mr-2">
                Editar
            </a>
            <a href="{{ route('participante-externos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Email</p>
                <p class="text-lg">{{ $participanteExterno->email ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Telefone</p>
                <p class="text-lg">{{ $participanteExterno->telefone ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Documento</p>
                <p class="text-lg">{{ $participanteExterno->documento ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Gênero</p>
                <p class="text-lg">
                    @if ($participanteExterno->genero == 'm') Masculino
                    @elseif ($participanteExterno->genero == 'f') Feminino
                    @elseif ($participanteExterno->genero == 'outro') Outro
                    @else - @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Data de Nascimento</p>
                <p class="text-lg">{{ $participanteExterno->data_nascimento ? $participanteExterno->data_nascimento->format('d/m/Y') : '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Participações em Eventos</h2>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Evento</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Data</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Presença</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($participanteExterno->eventoParticipantes as $ep)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <a href="{{ route('eventos.show', $ep->evento) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $ep->evento->nome }}
                        </a>
                    </td>
                    <td class="px-4 py-2">{{ $ep->evento->data_inicio->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($ep->presenca) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $ep->presenca ? 'Presente' : 'Ausente' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-2 text-center text-gray-500">
                        Nenhuma participação em eventos
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
