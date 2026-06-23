@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Participantes Dirigentes</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar é renderizado automaticamente pelo layout.app -->

        <div class="lg:col-span-3">
            <div x-data="participantesManager()" class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Participantes Dirigentes</h2>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('eventos.participantes.todos-escopo', $evento) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                Adicionar Todos do Escopo
                            </button>
                        </form>
                        <button onclick="openModal('participanteModal', false, { evento_id: {{ $evento->id }}, tipo_participante: 'dirigente' })" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Adicionar Participante
                        </button>
                    </div>
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
                        @forelse ($evento->participantes->where('tipo_participante', 'dirigente') as $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $p->dirigente->nome }}</td>
                            <td class="px-4 py-2">{{ $p->dirigente->email ?: '-' }}</td>
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
                                <form method="POST" action="{{ route('eventos.participantes.destroy', [$evento, $p]) }}" class="inline" @submit.prevent="deleteItem($event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Remover</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                Nenhum participante dirigente adicionado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Participantes -->
    <x-modal-form
        id="participanteModal"
        title="Adicionar Participante"
        :resource="'eventos.participantes'"
        size="md"
        :nested="true"
        :nestedPath="'evento'"
    >
        <input type="hidden" name="evento_id" id="participanteModalevento_id">
        <input type="hidden" name="tipo_participante" id="participanteModaltipo_participante">

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Dirigente *</label>
            <select
                name="dirigente_id"
                id="participanteModaldirigente_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione um dirigente...</option>
            </select>
            <span class="text-red-500 text-sm" id="participanteModaldirigente_idError"></span>
        </div>
    </x-modal-form>
</div>
@endsection
