@extends('layouts.fullscreen-layout')

@section('content')
<script defer>
document.addEventListener('alpine:init', () => {
    window.secretariasManager = function(dioceses, secretarias, habilidades, niveis) {
        return {
        dioceses,
        secretarias,
        habilidades,
        niveis,

        secretariaAtual: null,
        modal: null,
        loadingModal: false,

        formSecretaria: { nome: '', tipo_secretaria: 'aberta' },
        formHabilidade: { nome: '' },

        routes: {
            storeSecretaria: "{{ route('inscricoes.secretarias.store') }}",
            storeHabilidade: "{{ route('inscricoes.secretarias.habilidade.store') }}",
            deleteHabilidade: "{{ route('inscricoes.secretarias.habilidade.delete') }}",
            updateSecretaria: "{{ route('inscricoes.secretarias.update') }}",
        },

        openModal(type) {
            if (type === 'habilidade' && !this.secretariaAtual) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecione uma secretaria',
                    text: 'Escolha uma secretaria primeiro para adicionar habilidades.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            this.modal = type;
            if (type === 'secretaria') {
                this.formSecretaria = { nome: '', tipo_secretaria: 'aberta' };
            } else {
                this.formHabilidade = { nome: '' };
            }
        },

        selectSecretaria(id) {
            this.secretariaAtual = this.secretariaAtual === id ? null : id;
        },

        async submitSecretaria() {
            if (!this.formSecretaria.nome.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nome obrigatório',
                    text: 'Informe o nome da secretaria.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            this.loadingModal = true;
            try {
                const res = await fetch(this.routes.storeSecretaria, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        nome: this.formSecretaria.nome,
                        tipo_secretaria: this.formSecretaria.tipo_secretaria,
                    }),
                });

                if (!res.ok) {
                    const error = await res.json();
                    throw new Error(error.message || 'Erro ao criar secretaria');
                }

                const data = await res.json();
                this.secretarias.push(data);
                this.modal = null;

                Swal.fire({
                    icon: 'success',
                    title: 'Secretaria criada!',
                    text: `"${data.nome}" foi criada com sucesso.`,
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: e.message,
                    confirmButtonColor: '#3b82f6'
                });
            } finally {
                this.loadingModal = false;
            }
        },

        async submitHabilidade() {
            if (!this.formHabilidade.nome.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nome obrigatório',
                    text: 'Informe o nome da habilidade.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            this.loadingModal = true;
            try {
                const res = await fetch(this.routes.storeHabilidade, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        nome: this.formHabilidade.nome,
                        secretaria_id: this.secretariaAtual,
                    }),
                });

                if (!res.ok) {
                    const error = await res.json();
                    throw new Error(error.message || 'Erro ao criar habilidade');
                }

                const data = await res.json();
                this.habilidades.push(data);
                this.modal = null;

                Swal.fire({
                    icon: 'success',
                    title: 'Habilidade criada!',
                    text: `"${data.nome}" foi adicionada com sucesso.`,
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: e.message,
                    confirmButtonColor: '#3b82f6'
                });
            } finally {
                this.loadingModal = false;
            }
        },

        async deleteHabilidade(habilidadeId, secretariaId) {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Confirmar exclusão',
                text: 'Tem certeza que deseja remover esta habilidade?',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, remover',
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(this.routes.deleteHabilidade, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ habilidade_id: habilidadeId }),
                });

                if (!res.ok) throw new Error('Erro ao remover habilidade');

                this.habilidades = this.habilidades.filter(h => h.id !== habilidadeId);

                Swal.fire({
                    icon: 'success',
                    title: 'Removido!',
                    text: 'Habilidade removida com sucesso.',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: e.message,
                    confirmButtonColor: '#3b82f6'
                });
            }
        },

        async toggleSecretariaAberta(secretariaId, novoStatus) {
            try {
                const res = await fetch(this.routes.updateSecretaria, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        secretaria_id: secretariaId,
                        tipo_secretaria: novoStatus,
                    }),
                });

                if (!res.ok) throw new Error('Erro ao atualizar secretaria');

                const data = await res.json();
                const secretaria = this.secretarias.find(s => s.id === secretariaId);
                if (secretaria) secretaria.tipo_secretaria = data.tipo_secretaria;
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: e.message,
                    confirmButtonColor: '#3b82f6'
                });
            }
        },

        getHabilidades(secretariaId) {
            return this.habilidades.filter(h => Number(h.entidade_id) === Number(secretariaId));
        },

        getSecretariaNome(secretariaId) {
            return this.secretarias.find(s => s.id === secretariaId)?.nome || '—';
        },
        };
    };
});
</script>

<div x-data="secretariasManager(
        {{ collect($dioceses)->values()->toJson() }},
        {{ collect($secretarias)->values()->toJson() }},
        {{ collect($habilidades)->values()->toJson() }},
        {{ collect($niveis)->values()->toJson() }}
    )"
    class="min-h-screen bg-gray-50">

    {{-- MODAIS --}}
    <div x-show="modal !== null" x-cloak x-transition.opacity.duration.300ms
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm" @click="modal = null"></div>

    {{-- Modal Criar Secretaria --}}
    <div x-show="modal === 'secretaria'" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-lg font-semibold text-gray-800 mb-5">Criar nova Secretaria</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Nome <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formSecretaria.nome"
                        placeholder="Ex.: Secretaria de Música"
                        @keydown.enter.prevent="submitSecretaria()"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-base md:text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all placeholder-gray-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Status</label>
                    <select x-model="formSecretaria.tipo_secretaria"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-base md:text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                        <option value="aberta">Aberta (aceita novos membros)</option>
                        <option value="fechada">Fechada</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button type="button" @click="submitSecretaria()" :disabled="loadingModal"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px] transition-colors">
                    <span x-show="!loadingModal">Criar</span>
                    <span x-show="loadingModal">Criando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Criar Habilidade --}}
    <div x-show="modal === 'habilidade'" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-lg font-semibold text-gray-800 mb-5">Criar nova Habilidade</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Secretaria selecionada</label>
                    <p class="text-sm text-gray-700 font-medium" x-text="getSecretariaNome(secretariaAtual)"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Nome da Habilidade <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formHabilidade.nome"
                        placeholder="Ex.: Violão, Piano, Canto"
                        @keydown.enter.prevent="submitHabilidade()"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-base md:text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all placeholder-gray-400">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button type="button" @click="submitHabilidade()" :disabled="loadingModal"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px] transition-colors">
                    <span x-show="!loadingModal">Criar</span>
                    <span x-show="loadingModal">Criando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- CONTEÚDO PRINCIPAL --}}
    <div class="max-w-4xl mx-auto px-4 py-6 md:py-10">
        <div class="text-center mb-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Secretarias e Habilidades</h1>
            <p class="mt-2 text-gray-500 text-sm md:text-base">Gerencie secretarias e suas habilidades</p>
        </div>

        {{-- Seção de Secretarias --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
                <h2 class="text-lg md:text-base font-semibold text-gray-700">Secretarias</h2>
                <button type="button" @click="openModal('secretaria')"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Secretaria
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="s in secretarias" :key="s.id">
                    <div class="border border-gray-100 rounded-xl overflow-hidden hover:border-gray-200 transition-colors">
                        <button type="button" @click="selectSecretaria(s.id)"
                            class="w-full flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3 flex-1">
                                <span class="text-sm font-medium text-gray-700" x-text="s.nome"></span>
                                <span class="px-2.5 py-1 text-xs rounded-full"
                                    :class="s.tipo_secretaria === 'aberta' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                                    x-text="s.tipo_secretaria === 'aberta' ? '✓ Aberta' : 'Fechada'"></span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform"
                                :class="secretariaAtual === s.id ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </button>

                        {{-- Conteúdo da Secretaria --}}
                        <div x-show="secretariaAtual === s.id"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="border-t border-gray-100 bg-gray-50 px-5 py-5 space-y-5">

                            {{-- Status da Secretaria --}}
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Status da Secretaria</p>
                                    <p class="text-xs text-gray-500 mt-1">Define se aceita novos membros</p>
                                </div>
                                <select :value="s.tipo_secretaria"
                                    @change="toggleSecretariaAberta(s.id, $event.target.value)"
                                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="aberta">Aberta</option>
                                    <option value="fechada">Fechada</option>
                                </select>
                            </div>

                            {{-- Habilidades --}}
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Habilidades</h4>
                                    <button type="button" @click="openModal('habilidade')"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                                        + Adicionar
                                    </button>
                                </div>

                                <div x-show="getHabilidades(s.id).length === 0" class="text-sm text-gray-400 italic py-4">
                                    Nenhuma habilidade cadastrada
                                </div>

                                <div class="space-y-2">
                                    <template x-for="h in getHabilidades(s.id)" :key="h.id">
                                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                                            <span class="text-sm text-gray-700 font-medium" x-text="h.nome"></span>
                                            <button type="button" @click="deleteHabilidade(h.id, s.id)"
                                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="secretarias.length === 0" class="text-center py-12">
                    <p class="text-gray-400 text-sm mb-4">Nenhuma secretaria cadastrada</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>
@endsection
