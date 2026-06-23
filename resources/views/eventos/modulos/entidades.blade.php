@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Entidades Envolvidas</h1>
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
            <div x-data="entidadesManager()" class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Entidades Envolvidas</h2>
                    <button onclick="openModal('entidadeModal', false, { evento_id: {{ $evento->id }} })" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Adicionar Entidade
                    </button>
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
                                <form method="POST" action="{{ route('eventos.entidades.destroy', [$evento, $ee]) }}" class="inline" @submit.prevent="deleteItem($event)">
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
        </div>
    </div>

    <!-- Modal for Entidades -->
    <x-modal-form
        id="entidadeModal"
        title="Adicionar Entidade ao Evento"
        :resource="'eventos.entidades'"
        size="md"
        :nested="true"
        :nestedPath="'evento'"
    >
        <input type="hidden" name="evento_id" id="entidadeModalevento_id">

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Entidade *</label>
            <select
                name="entidade_id"
                id="entidadeModalentidade_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione uma entidade...</option>
            </select>
            <span class="text-red-500 text-sm" id="entidadeModalentidade_idError"></span>
        </div>
    </x-modal-form>
</div>
@endsection
