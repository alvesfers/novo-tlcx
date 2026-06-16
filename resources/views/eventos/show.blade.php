@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
        <div>
            <a href="{{ route('eventos.edit', $evento) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mr-2">
                Editar
            </a>
            <a href="{{ route('eventos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Informações do Evento -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
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
        <div class="mt-4 pt-4 border-t">
            <p class="text-gray-600 text-sm font-semibold">Descrição</p>
            <p class="text-lg">{{ $evento->descricao }}</p>
        </div>
        @endif
    </div>

    <!-- Entidades Envolvidas -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Entidades Envolvidas</h2>
            <a href="{{ route('eventos.entidades.create', $evento) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                Adicionar Entidade
            </a>
        </div>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Tipo</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Participação</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evento->eventoEntidades as $ee)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $ee->entidade->nome }}</td>
                    <td class="px-4 py-2">{{ $ee->entidade->tipo_entidade->label() }}</td>
                    <td class="px-4 py-2">{{ $ee->tipo_participacao->label() }}</td>
                    <td class="px-4 py-2">
                        @if (!$ee->isOrganizadora())
                        <form method="POST" action="{{ route('eventos.entidades.destroy', [$evento, $ee]) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remover</button>
                        </form>
                        @else
                        <span class="text-gray-500 text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                        Nenhuma entidade adicionada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Participantes Dirigentes -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Participantes Dirigentes</h2>
            <a href="{{ route('eventos.participantes.create', $evento) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                Adicionar Dirigente
            </a>
        </div>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Presença</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Check-in</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Observação</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evento->participantes->where('tipo_participante', 'dirigente') as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $p->dirigente->nome }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($p->presenca) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $p->presenca ? 'Presente' : 'Ausente' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $p->checkin_em ? $p->checkin_em->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-4 py-2 text-sm">{{ $p->observacao ?: '-' }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if (!$p->presenca)
                        <form method="POST" action="{{ route('eventos.participantes.presenca', [$evento, $p]) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 mr-4">Marcar Presença</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('eventos.participantes.destroy', [$evento, $p]) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Remover</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                        Nenhum dirigente adicionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Participantes Externos -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Participantes Externos</h2>
            <a href="{{ route('eventos.participantes.create', $evento) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                Adicionar Externo
            </a>
        </div>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Presença</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evento->participantes->where('tipo_participante', 'externo') as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $p->participanteExterno->nome }}</td>
                    <td class="px-4 py-2">{{ $p->participanteExterno->email ?: '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($p->presenca) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $p->presenca ? 'Presente' : 'Ausente' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm">
                        @if (!$p->presenca)
                        <form method="POST" action="{{ route('eventos.participantes.presenca', [$evento, $p]) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 mr-4">Marcar Presença</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('eventos.participantes.destroy', [$evento, $p]) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Remover</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                        Nenhum participante externo adicionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
