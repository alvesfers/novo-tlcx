@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="entidadesManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Entidades Envolvidas</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow">
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

    {{-- Backdrop --}}
    <div x-show="showModal" x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm"
         @click="closeModal()" style="display:none;"></div>

    {{-- Modal for Adding Entidades --}}
    <div x-show="showModal" x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col" @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h3 class="text-base font-semibold text-gray-800">Adicionar Entidade ao Evento</h3>
                <button type="button" @click="closeModal()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <form @submit.prevent="submitForm" class="overflow-y-auto px-6 pb-2 flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipo de Entidade <span class="text-red-500">*</span></label>
                        <select x-model="formData.tipoEntidade" @change="resetSubSelects()"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione o tipo...</option>
                            <option value="diocese">Diocese</option>
                            <option value="secretaria">Secretaria</option>
                            <option value="nucleo">Núcleo</option>
                        </select>
                    </div>

                    <div x-show="formData.tipoEntidade === 'diocese'">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese <span class="text-red-500">*</span></label>
                        <select x-model="formData.entidade_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione uma diocese...</option>
                            @foreach ($dioceses as $diocese)
                                <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="formData.tipoEntidade === 'secretaria'">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Secretaria <span class="text-red-500">*</span></label>
                        <select x-model="formData.entidade_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione uma secretaria...</option>
                            @foreach ($secretarias as $secretaria)
                                <option value="{{ $secretaria->id }}">{{ $secretaria->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="formData.tipoEntidade === 'nucleo'" class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese <span class="text-red-500">*</span></label>
                            <select x-model="formData.dioceseId" @change="carregarNucleos()"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Selecione uma diocese...</option>
                                @foreach ($dioceses as $diocese)
                                    <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Núcleo <span class="text-red-500">*</span></label>
                            <select x-model="formData.entidade_id"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Selecione um núcleo...</option>
                                <template x-for="nucleo in nucleosFiltrados" :key="nucleo.id">
                                    <option :value="nucleo.id" x-text="nucleo.nome"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div x-show="formData.entidade_id">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipo de Participação <span class="text-red-500">*</span></label>
                        <select x-model="formData.tipo_participacao"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione o tipo...</option>
                            <option value="organizadora">Organizadora</option>
                            <option value="participante">Participante</option>
                            <option value="apoio">Apoio</option>
                        </select>
                    </div>
                </div>
            </form>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" @click="closeModal()"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button type="button" @click="submitForm()"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg min-w-[90px] transition-colors">
                    Adicionar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('entidadesManager', () => ({
        showModal: false,
        nucleosFiltrados: [],
        formData: {
            tipoEntidade: '',
            dioceseId: '',
            entidade_id: '',
            tipo_participacao: '',
        },
        allNucleos: @json($nucleos),
        openModal() {
            this.formData = {
                tipoEntidade: '',
                dioceseId: '',
                entidade_id: '',
                tipo_participacao: '',
            };
            this.nucleosFiltrados = [];
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        resetSubSelects() {
            this.formData.dioceseId = '';
            this.formData.entidade_id = '';
            this.formData.tipo_participacao = '';
            this.nucleosFiltrados = [];
        },
        carregarNucleos() {
            if (!this.formData.dioceseId) {
                this.nucleosFiltrados = [];
                return;
            }
            this.nucleosFiltrados = this.allNucleos.filter(n => n.entidade_pai_id == this.formData.dioceseId);
            this.formData.entidade_id = '';
        },
        submitForm() {
            if (!this.formData.entidade_id || !this.formData.tipo_participacao) {
                alert('Por favor, preencha todos os campos obrigatórios');
                return;
            }

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
    }));
});
</script>
@endpush
@endsection
