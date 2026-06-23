@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Participantes Externos</h1>
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
            <div x-data="exterioresManager()" class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Participantes Externos</h2>
                    <button onclick="openModal('participanteExternoModal', false, { evento_id: {{ $evento->id }} })" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Adicionar Participante
                    </button>
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
                                Nenhum participante externo adicionado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Participante Externo -->
    <x-modal-form
        id="participanteExternoModal"
        title="Adicionar Participante Externo"
        :resource="'eventos.participantes'"
        size="md"
        :nested="true"
        :nestedPath="'evento'"
    >
        <input type="hidden" name="evento_id" id="participanteExternoModalevento_id">
        <input type="hidden" name="tipo_participante" id="participanteExternoModaltipo_participante" value="externo">

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Selecione ou Crie um Participante Externo *</label>
            <select
                name="participante_externo_id"
                id="participanteExternoModalparticipante_externo_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">Selecionar...</option>
            </select>
            <span class="text-red-500 text-sm" id="participanteExternoModalparticipante_externo_idError"></span>
        </div>

        <div class="mt-4 border-t pt-4">
            <p class="text-sm text-gray-600 mb-3">Ou crie um novo participante externo:</p>
            <input
                type="text"
                name="nome"
                placeholder="Nome *"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2"
            >
            <input
                type="email"
                name="email"
                placeholder="Email"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
        </div>
    </x-modal-form>
</div>
@endsection
