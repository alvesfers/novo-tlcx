@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Entidades Envolvidas</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>

        <!-- Content -->
        <div x-data="entidadesManager()" class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Lista de Entidades</h2>
                <button @click="openModal()" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition">
                    Adicionar Entidade
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Papel</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($evento->eventoEntidades as $ee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $ee->entidade->nome }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $ee->entidade->tipo_entidade->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if ($ee->isOrganizadora())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Organizadora
                                    </span>
                                @else
                                    Participante
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if (!$ee->isOrganizadora())
                                <form method="POST" action="{{ route('eventos.entidades.destroy', [$evento, $ee]) }}" class="inline" @submit.prevent="submitDelete($event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Remover</button>
                                </form>
                                @else
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Nenhuma entidade adicionada
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Entidades -->
    <div x-data="entidadesManager()" x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Adicionar Entidade ao Evento</h3>
            </div>

            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Entidade *</label>
                    <select
                        x-model="formData.entidade_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Selecione uma entidade...</option>
                        @foreach ($entidades as $entidade)
                            <option value="{{ $entidade->id }}">{{ $entidade->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Tipo de Participação *</label>
                    <select
                        x-model="formData.tipo_participacao"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Selecione o tipo...</option>
                        <option value="organizadora">Organizadora</option>
                        <option value="participante">Participante</option>
                        <option value="apoio">Apoio</option>
                    </select>
                </div>

                <div class="flex gap-3 justify-end pt-4">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function entidadesManager() {
    return {
        showModal: false,
        formData: {
            entidade_id: '',
            tipo_participacao: '',
        },
        openModal() {
            this.formData = { entidade_id: '', tipo_participacao: '' };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        submitForm() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("eventos.entidades.store", $evento) }}';
            form.innerHTML = `
                @csrf
                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                <input type="hidden" name="entidade_id" value="${this.formData.entidade_id}">
                <input type="hidden" name="tipo_participacao" value="${this.formData.tipo_participacao}">
            `;
            document.body.appendChild(form);
            form.submit();
        },
        submitDelete(event) {
            if (confirm('Tem certeza que deseja remover esta entidade?')) {
                event.target.submit();
            }
        }
    };
}
</script>
@endsection
