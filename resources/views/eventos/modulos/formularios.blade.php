@extends('layouts.app')

@section('content')
<div x-data="formulariosPage()" class="container mx-auto px-4 py-8">

    {{-- ===== MODAL: ADICIONAR / EDITAR CAMPO ===== --}}
    <div x-show="modOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm" @click="fechar()"></div>

    <div x-show="modOpen" x-cloak x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-800" x-text="campoEditando ? 'Editar Campo' : 'Novo Campo'"></h2>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="'Formulário: ' + abaLabel(abaAtiva)"></p>
                </div>
                <button @click="fechar()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 pb-2 flex-1 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome do campo <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.nome" required placeholder="Ex.: Nome completo, Data de nascimento..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipo de campo <span class="text-red-500">*</span></label>
                    <select x-model="form.tipo" @change="form.opcoes = ''"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        <option value="text">Texto</option>
                        <option value="email">E-mail</option>
                        <option value="number">Número</option>
                        <option value="date">Data</option>
                        <option value="textarea">Área de texto</option>
                        <option value="select">Seleção (dropdown)</option>
                        <option value="radio">Radio button</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Descrição / ajuda</label>
                    <input type="text" x-model="form.descricao" placeholder="Ex.: Informe sua data de nascimento completa"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div x-show="['select','radio','checkbox'].includes(form.tipo)" x-transition>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Opções <span class="text-gray-400 font-normal normal-case">(uma por linha)</span></label>
                    <textarea x-model="form.opcoes" rows="4" placeholder="Opção 1&#10;Opção 2&#10;Opção 3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" x-model="form.obrigatorio" id="chkObrig"
                        class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="chkObrig" class="text-sm text-gray-700 cursor-pointer">Campo obrigatório</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button @click="fechar()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Cancelar</button>
                <button @click="salvar()" :disabled="!form.nome || !form.tipo || salvando"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[80px] transition-colors">
                    <span x-show="!salvando">Salvar</span><span x-show="salvando">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== HEADER ===== --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Formulários</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $evento->nome }}</p>
        </div>
        <a href="{{ route('eventos.show', $evento) }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
    </div>

    {{-- ===== ABAS ===== --}}
    <div class="flex gap-1 mb-6 border-b border-gray-200 overflow-x-auto">
        @foreach([
            ['dirigentes_interno', '🔵 Dirigente Interno', 'dirigente_interno'],
            ['dirigentes_externo', '🟣 Dirigente Externo', 'dirigente_externo'],
            ['participantes',      '🟡 Participante Externo', null],
            ['dirigentes',         '⚙️ Geral (legado)', null],
        ] as [$tipo, $label, $publico])
        <button @click="abaAtiva = '{{ $tipo }}'"
            :class="abaAtiva === '{{ $tipo }}'
                ? 'border-b-2 border-blue-600 text-blue-600 font-semibold'
                : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-3 text-sm whitespace-nowrap transition-colors">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ===== CONTEÚDO DAS ABAS ===== --}}
    @php
    $abas = [
        'dirigentes_interno' => [
            'campos'  => $formularioDirigentesInterno,
            'label'   => 'Dirigente Interno',
            'cor'     => 'blue',
            'descricao' => 'Formulário para dirigentes com função interna (Música, Chefe, Cozinha, etc.)',
            'url_pub' => route('evento.formulario.show.dirigente.interno', $evento->uuid),
        ],
        'dirigentes_externo' => [
            'campos'  => $formularioDirigentesExterno,
            'label'   => 'Dirigente Externo',
            'cor'     => 'purple',
            'descricao' => 'Formulário para dirigentes com função externa (EQR, Externa, etc.)',
            'url_pub' => route('evento.formulario.show.dirigente.externo', $evento->uuid),
        ],
        'participantes' => [
            'campos'  => $formularioParticipantes,
            'label'   => 'Participante Externo',
            'cor'     => 'amber',
            'descricao' => 'Formulário para participantes externos (cursistas)',
            'url_pub' => route('evento.formulario.show', $evento->uuid),
        ],
        'dirigentes' => [
            'campos'  => $formularioDirigentes,
            'label'   => 'Geral (legado)',
            'cor'     => 'gray',
            'descricao' => 'Formulário genérico original. Use os específicos acima quando possível.',
            'url_pub' => route('evento.formulario.show.dirigente', $evento->uuid),
        ],
    ];
    @endphp

    @foreach($abas as $tipoAba => $aba)
    <div x-show="abaAtiva === '{{ $tipoAba }}'" x-cloak x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            {{-- Cabeçalho da aba --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">{{ $aba['label'] }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $aba['descricao'] }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    {{-- URL pública --}}
                    <div class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-500 max-w-xs truncate">
                        <svg class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <a href="{{ $aba['url_pub'] }}" target="_blank" class="hover:text-blue-600 truncate">
                            {{ $aba['url_pub'] }}
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ $aba['url_pub'] }}')"
                            title="Copiar link"
                            class="shrink-0 text-gray-400 hover:text-blue-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>

                    <button @click="abrirModal('{{ $tipoAba }}')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Novo Campo
                    </button>
                </div>
            </div>

            {{-- Campos --}}
            @if(empty($aba['campos']))
            <div class="px-6 py-12 text-center">
                <p class="text-gray-400 text-sm mb-3">Nenhum campo configurado ainda.</p>
                <button @click="abrirModal('{{ $tipoAba }}')"
                    class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium">
                    + Adicionar primeiro campo
                </button>
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($aba['campos'] as $i => $campo)
                <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group">
                    <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold flex items-center justify-center shrink-0 mt-0.5">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-medium text-gray-800">{{ $campo['nome'] }}</p>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ ['text' => 'Texto', 'email' => 'E-mail', 'number' => 'Número', 'date' => 'Data',
                                    'textarea' => 'Área de texto', 'select' => 'Dropdown',
                                    'radio' => 'Radio', 'checkbox' => 'Checkbox'][$campo['tipo']] ?? $campo['tipo'] }}
                            </span>
                            @if($campo['obrigatorio'])
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full">Obrigatório</span>
                            @endif
                        </div>
                        @if(!empty($campo['descricao']))
                        <p class="text-xs text-gray-400 mt-0.5">{{ $campo['descricao'] }}</p>
                        @endif
                        @if(!empty($campo['opcoes']))
                        <div class="flex flex-wrap gap-1 mt-1.5">
                            @foreach($campo['opcoes'] as $op)
                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $op }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                        <button @click="editarCampo('{{ $tipoAba }}', {{ json_encode($campo) }})"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button @click="removerCampo('{{ $tipoAba }}', '{{ $campo['id'] }}')"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Info: funções do tipo --}}
        @if($tipoAba === 'dirigentes_interno' || $tipoAba === 'dirigentes_externo')
        @php
            $tipoFuncao = $tipoAba === 'dirigentes_interno' ? 'interna' : 'externa';
            $funcoes = \DB::table('funcoes_dirigentes')->where('tipo', $tipoFuncao)->where('ativo', 1)->pluck('nome');
        @endphp
        @if($funcoes->isNotEmpty())
        <div class="mt-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Funções {{ $tipoAba === 'dirigentes_interno' ? 'internas' : 'externas' }} que usarão este formulário
            </p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($funcoes as $f)
                <span class="text-xs {{ $tipoAba === 'dirigentes_interno' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }} px-2.5 py-1 rounded-full font-medium">
                    {{ $f }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        @endif
    </div>
    @endforeach
</div>

@push('scripts')
<script>
function formulariosPage() {
    return {
        eventoId: {{ $evento->id }},
        abaAtiva: 'dirigentes_interno',
        modOpen: false,
        salvando: false,
        campoEditando: null,
        form: { nome: '', tipo: '', descricao: '', opcoes: '', obrigatorio: false },

        abaLabel(tipo) {
            return { dirigentes_interno: 'Dirigente Interno', dirigentes_externo: 'Dirigente Externo',
                     participantes: 'Participante Externo', dirigentes: 'Geral (legado)' }[tipo] || tipo;
        },

        abrirModal(tipoAba) {
            this.abaAtiva = tipoAba;
            this.campoEditando = null;
            this.form = { nome: '', tipo: '', descricao: '', opcoes: '', obrigatorio: false };
            this.modOpen = true;
            document.body.style.overflow = 'hidden';
        },

        editarCampo(tipoAba, campo) {
            this.abaAtiva = tipoAba;
            this.campoEditando = campo;
            this.form = {
                nome: campo.nome,
                tipo: campo.tipo,
                descricao: campo.descricao || '',
                opcoes: campo.opcoes ? campo.opcoes.join('\n') : '',
                obrigatorio: !!campo.obrigatorio,
            };
            this.modOpen = true;
            document.body.style.overflow = 'hidden';
        },

        fechar() { this.modOpen = false; document.body.style.overflow = ''; },

        async salvar() {
            if (!this.form.nome || !this.form.tipo) return;
            this.salvando = true;
            const payload = {
                formulario_tipo: this.abaAtiva,
                nome: this.form.nome,
                tipo: this.form.tipo,
                descricao: this.form.descricao || null,
                obrigatorio: this.form.obrigatorio,
                opcoes: this.form.opcoes ? this.form.opcoes.split('\n').map(s => s.trim()).filter(Boolean) : [],
            };
            const url = this.campoEditando
                ? `/api/eventos/${this.eventoId}/formularios/${this.campoEditando.id}`
                : `/api/eventos/${this.eventoId}/formularios`;
            try {
                const resp = await fetch(url, {
                    method: this.campoEditando ? 'PUT' : 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload),
                });
                const data = await resp.json();
                if (data.success) { this.fechar(); window.location.reload(); }
                else Swal.fire({ icon: 'error', title: 'Erro', text: data.message || 'Erro ao salvar' });
            } catch(e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao salvar campo' }); }
            finally { this.salvando = false; }
        },

        async removerCampo(tipoAba, campoId) {
            const ok = await Swal.fire({
                icon: 'warning', title: 'Remover campo?', text: 'Esta ação não pode ser desfeita.',
                showCancelButton: true, confirmButtonColor: '#dc2626',
                confirmButtonText: 'Remover', cancelButtonText: 'Cancelar',
            });
            if (!ok.isConfirmed) return;
            try {
                await fetch(`/api/eventos/${this.eventoId}/formularios/${campoId}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ formulario_tipo: tipoAba }),
                });
                window.location.reload();
            } catch(e) { Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao remover campo' }); }
        },
    };
}
</script>
@endpush
@endsection
