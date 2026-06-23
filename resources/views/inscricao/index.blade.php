@extends('layouts.fullscreen-layout')

@section('content')
<div x-data="inscricao(
        {{ collect($dioceses)->values()->toJson() }},
        {{ collect($nucleos)->values()->toJson() }},
        {{ collect($secretarias)->values()->toJson() }},
        {{ collect($habilidades)->values()->toJson() }},
        {{ collect($eventosTlc)->values()->toJson() }},
        {{ collect($eventosMiniTlc)->values()->toJson() }},
        {{ collect($niveis)->values()->toJson() }}
    )"
    class="min-h-screen bg-gray-50">

    {{-- ==================== MODAIS ==================== --}}
    <div x-show="modal !== null" x-cloak x-transition.opacity
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm" @click="modal = null"></div>

    {{-- Modal Diocese --}}
    <div x-show="modal === 'diocese'" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-5">Cadastrar nova Diocese</h3>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" x-model="mf.nome" @keydown.enter.prevent="submitModal()" placeholder="Ex.: Diocese de São Paulo"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="button" @click="submitModal()" :disabled="mloading" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px]">
                    <span x-show="!mloading">Cadastrar</span><span x-show="mloading">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Núcleo --}}
    <div x-show="modal === 'nucleo'" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-5">Cadastrar novo Núcleo</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese <span class="text-red-500">*</span></label>
                    <select x-model="mf.entidade_pai_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Selecione</option>
                        <template x-for="d in dioceses" :key="d.id"><option :value="d.id" x-text="d.nome"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" x-model="mf.nome" @keydown.enter.prevent="submitModal()" placeholder="Ex.: Núcleo São João"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="button" @click="submitModal()" :disabled="mloading" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px]">
                    <span x-show="!mloading">Cadastrar</span><span x-show="mloading">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Secretaria --}}
    <div x-show="modal === 'secretaria'" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-5">Cadastrar nova Secretaria</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese <span class="text-red-500">*</span></label>
                    <select x-model="mf.entidade_pai_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Selecione</option>
                        <template x-for="d in dioceses" :key="d.id"><option :value="d.id" x-text="d.nome"></option></template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" x-model="mf.nome" @keydown.enter.prevent="submitModal()" placeholder="Ex.: Secretaria de Música"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="button" @click="submitModal()" :disabled="mloading" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px]">
                    <span x-show="!mloading">Cadastrar</span><span x-show="mloading">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal TLC --}}
    <div x-show="modal === 'tlc'" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-5">Cadastrar novo TLC</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" x-model="mf.nome" placeholder="Ex.: TLC São José 2024"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tema <span class="text-gray-400 font-normal normal-case">(opcional)</span></label>
                    <input type="text" x-model="mf.tema" placeholder="Ex.: Renovados pelo Espírito"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Data de realização <span class="text-red-500">*</span></label>
                    <input type="date" x-model="mf.data_inicio" @keydown.enter.prevent="submitModal()"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <p class="text-xs text-gray-400">Será vinculado à diocese/núcleo já selecionado.</p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="button" @click="submitModal()" :disabled="mloading" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px]">
                    <span x-show="!mloading">Cadastrar</span><span x-show="mloading">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Mini TLC --}}
    <div x-show="modal === 'mini_tlc'" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-5">Cadastrar novo Mini TLC</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" x-model="mf.nome" placeholder="Ex.: Mini TLC Paróquia São José"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tema <span class="text-gray-400 font-normal normal-case">(opcional)</span></label>
                    <input type="text" x-model="mf.tema" placeholder="Ex.: Chamados à Santidade"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Data de realização <span class="text-red-500">*</span></label>
                    <input type="date" x-model="mf.data_inicio" @keydown.enter.prevent="submitModal()"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <p class="text-xs text-gray-400">Será vinculado ao núcleo já selecionado.</p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modal = null" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="button" @click="submitModal()" :disabled="mloading" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px]">
                    <span x-show="!mloading">Cadastrar</span><span x-show="mloading">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== WIZARD ==================== --}}
    <div class="max-w-2xl mx-auto px-4 py-10">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Ficha de Inscrição</h1>
            <p class="mt-2 text-gray-500 text-sm">Preencha seus dados para se cadastrar como dirigente.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
            <p class="font-semibold mb-1">Corrija os erros:</p>
            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- Progress --}}
        <div class="flex items-center mb-10">
            <template x-for="(label, i) in steps" :key="i">
                <div class="flex items-center" :class="i < steps.length - 1 ? 'flex-1' : ''">
                    <button type="button" @click="goToStep(i + 1)" :disabled="i + 1 > maxStep"
                        class="flex flex-col items-center focus:outline-none disabled:cursor-default shrink-0">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-200"
                            :class="{
                                'bg-blue-600 text-white shadow shadow-blue-200': step === i+1,
                                'bg-blue-100 text-blue-600': step !== i+1 && i+1 <= maxStep,
                                'bg-gray-100 text-gray-400': i+1 > maxStep
                            }">
                            <template x-if="i+1 < step">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="i+1 >= step"><span x-text="i+1"></span></template>
                        </div>
                        <span class="absolute -bottom-5 text-xs whitespace-nowrap font-medium hidden sm:block"
                            :class="step === i+1 ? 'text-blue-600' : (i+1 < step ? 'text-gray-500' : 'text-gray-400')"
                            x-text="label"></span>
                    </button>
                    <div x-show="i < steps.length - 1" class="flex-1 h-px mx-1 transition-colors duration-300"
                        :class="i+1 < step ? 'bg-blue-400' : 'bg-gray-200'"></div>
                </div>
            </template>
        </div>

        <form method="POST" action="{{ route('inscricao.store') }}" class="mt-6">
            @csrf
            <input type="hidden" name="timeline" :value="JSON.stringify(computeTimeline())">

            {{-- ===== STEP 1: DADOS PESSOAIS ===== --}}
            <div x-show="step === 1" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-base font-semibold text-gray-700 mb-6">Dados Pessoais</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome completo <span class="text-red-500">*</span></label>
                            <input type="text" name="nome" value="{{ old('nome') }}" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Apelido</label>
                            <input type="text" name="apelido" value="{{ old('apelido') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Telefone / WhatsApp</label>
                            <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(11) 99999-9999"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Data de nascimento</label>
                            <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Gênero</label>
                            <select name="genero" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="M" @selected(old('genero')==='M')>Masculino</option>
                                <option value="F" @selected(old('genero')==='F')>Feminino</option>
                                <option value="O" @selected(old('genero')==='O')>Outro</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== STEP 2: MINI TLC ===== --}}
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
                    <h2 class="text-base font-semibold text-gray-700">Mini TLC</h2>

                    {{-- Pergunta principal --}}
                    <div>
                        <p class="text-sm text-gray-600 mb-3">Você já fez o Mini TLC?</p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="fez_mini" value="sim" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Sim</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="fez_mini" value="nao" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Não</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="fez_mini === 'nao'" x-transition>
                        <p class="text-sm text-gray-400 italic">Tudo bem! Continue para o TLC.</p>
                    </div>

                    <div x-show="fez_mini === 'sim'" x-transition class="space-y-5">

                        {{-- Seleção do Mini TLC --}}
                        <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Qual Mini TLC você fez?</p>

                            @include('inscricao._select-row', ['label' => 'Diocese', 'xModel' => 'm_dio', 'optExpr' => 'dioceses', 'onChange' => 'm_nuc = null; m_evt = null; m_data = ""', 'modalType' => 'diocese', 'modalTarget' => 'm_dio', 'modalPrefill' => '{}'])

                            <div x-show="m_dio" x-transition>
                                @include('inscricao._select-row', ['label' => 'Núcleo', 'xModel' => 'm_nuc', 'optExpr' => 'nucsPorDio(m_dio)', 'onChange' => 'm_evt = null; m_data = ""', 'modalType' => 'nucleo', 'modalTarget' => 'm_nuc', 'modalPrefill' => '{ entidade_pai_id: m_dio }'])
                            </div>

                            <div x-show="m_nuc" x-transition>
                                @include('inscricao._select-row', ['label' => 'Mini TLC', 'xModel' => 'm_evt', 'name' => 'id_mini_tlc', 'optExpr' => 'minisPorNuc(m_nuc)', 'withTema' => true, 'onChange' => 'onMiniEvtChange()', 'modalType' => 'mini_tlc', 'modalTarget' => 'm_evt', 'modalPrefill' => '{ entidade_criadora_id: m_nuc }'])
                            </div>

                            <div x-show="m_evt" x-transition>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Confirme a data do Mini TLC</label>
                                <input type="date" x-model="m_data"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">💡 Se a data estiver errada, corrija aqui.</p>
                            </div>
                        </div>

                        {{-- Pergunta: foi para outro núcleo após o mini? --}}
                        <div x-show="m_evt && m_data" x-transition class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-3">Após o Mini TLC, você foi para outro núcleo?</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="m_mudou_pos" value="sim" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Sim</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="m_mudou_pos" value="nao" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Não</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="m_mudou_pos === 'sim'" x-transition class="p-4 bg-blue-50 rounded-xl space-y-4">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Núcleo que foi após o Mini TLC</p>
                                @include('inscricao._select-row', ['label' => 'Diocese', 'xModel' => 'm_pos_dio', 'optExpr' => 'dioceses', 'onChange' => 'm_pos_nuc = null', 'modalType' => 'diocese', 'modalTarget' => 'm_pos_dio', 'modalPrefill' => '{}'])
                                <div x-show="m_pos_dio" x-transition>
                                    @include('inscricao._select-row', ['label' => 'Núcleo', 'xModel' => 'm_pos_nuc', 'optExpr' => 'nucsPorDio(m_pos_dio)', 'modalType' => 'nucleo', 'modalTarget' => 'm_pos_nuc', 'modalPrefill' => '{ entidade_pai_id: m_pos_dio }'])
                                </div>
                                <div x-show="m_pos_nuc" x-transition>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Quando entrou neste núcleo?</label>
                                    <input type="date" x-model="m_pos_entrada"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <p class="text-xs text-gray-400 mt-1">💡 Se não lembrar, coloque 01/01 do ano aproximado.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Pergunta: tinha outro núcleo antes do mini? --}}
                        <div x-show="m_mudou_pos !== null" x-transition class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-3">Antes de fazer o Mini TLC, você estava em outro núcleo?</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="m_tem_antes" value="sim" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Sim</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="m_tem_antes" value="nao" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Não</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="m_tem_antes === 'sim'" x-transition class="space-y-3">
                                <template x-for="(n, idx) in m_antes" :key="n._id">
                                    <div class="p-4 bg-gray-50 rounded-xl space-y-3 relative">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide" x-text="'Núcleo anterior ' + (idx + 1)"></p>
                                            <button type="button" @click="m_antes.splice(idx, 1)"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese</label>
                                            <div class="flex gap-2 items-center">
                                                <select x-model="n.dio" @change="n.nuc = null"
                                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="">Selecione</option>
                                                    <template x-for="d in dioceses" :key="d.id"><option :value="d.id" x-text="d.nome"></option></template>
                                                </select>
                                                <button type="button" @click="openModal('diocese', 'm_pos_dio', {})" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 shrink-0">Não encontrei</button>
                                            </div>
                                        </div>
                                        <div x-show="n.dio" x-transition>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Núcleo</label>
                                            <div class="flex gap-2 items-center">
                                                <select x-model="n.nuc"
                                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="">Selecione</option>
                                                    <template x-for="nc in nucsPorDio(n.dio)" :key="nc.id"><option :value="nc.id" x-text="nc.nome"></option></template>
                                                </select>
                                                <button type="button" @click="openModal('nucleo', 'inline', { entidade_pai_id: n.dio })" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 shrink-0">Não encontrei</button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Entrou em</label>
                                                <input type="date" x-model="n.entrada" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Saiu em</label>
                                                <input type="date" x-model="n.saida" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400">💡 Se não lembrar a data exata, coloque 01/01 do ano aproximado.</p>
                                    </div>
                                </template>

                                <button type="button" @click="m_antes.push({ _id: Date.now(), dio: null, nuc: null, entrada: '', saida: '' })"
                                    class="w-full py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                                    + Adicionar outro núcleo anterior
                                </button>
                            </div>
                        </div>

                    </div>{{-- /fez_mini sim --}}
                </div>
            </div>

            {{-- ===== STEP 3: TLC ===== --}}
            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
                    <h2 class="text-base font-semibold text-gray-700">TLC</h2>

                    <div>
                        <p class="text-sm text-gray-600 mb-3">Você fez o TLC?</p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="fez_tlc" value="sim" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Sim</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="fez_tlc" value="nao" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Ainda não</span>
                            </label>
                        </div>
                    </div>

                    {{-- SE FEZ O TLC --}}
                    <div x-show="fez_tlc === 'sim'" x-transition class="space-y-5">

                        {{-- Seleção TLC --}}
                        <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Qual TLC você fez?</p>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipo</label>
                                <select x-model="t_tipo" @change="t_dio = null; t_nuc = null; t_evt = null; t_data = ''"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="">Selecione</option>
                                    <option value="diocesano">Diocesano</option>
                                    <option value="nucleo">De Núcleo</option>
                                </select>
                            </div>

                            <div x-show="t_tipo" x-transition>
                                @include('inscricao._select-row', ['label' => 'Diocese', 'xModel' => 't_dio', 'optExpr' => 'dioceses', 'onChange' => 't_nuc = null; t_evt = null; t_data = ""', 'modalType' => 'diocese', 'modalTarget' => 't_dio', 'modalPrefill' => '{}'])
                            </div>

                            <div x-show="t_tipo === 'nucleo' && t_dio" x-transition>
                                @include('inscricao._select-row', ['label' => 'Núcleo', 'xModel' => 't_nuc', 'optExpr' => 'nucsPorDio(t_dio)', 'onChange' => 't_evt = null; t_data = ""', 'modalType' => 'nucleo', 'modalTarget' => 't_nuc', 'modalPrefill' => '{ entidade_pai_id: t_dio }'])
                            </div>

                            <div x-show="t_dio && (t_tipo === 'diocesano' || t_nuc)" x-transition>
                                @include('inscricao._select-row', ['label' => 'TLC', 'xModel' => 't_evt', 'name' => 'id_tlc', 'optExpr' => 'tlcsPorEntidade(t_tipo === "diocesano" ? t_dio : t_nuc)', 'withTema' => true, 'onChange' => 'onTlcEvtChange()', 'modalType' => 'tlc', 'modalTarget' => 't_evt', 'modalPrefill' => '{ entidade_criadora_id: t_tipo === "diocesano" ? t_dio : t_nuc }'])
                            </div>

                            <div x-show="t_evt" x-transition>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Confirme a data do TLC</label>
                                <input type="date" x-model="t_data"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">💡 Se a data estiver errada, corrija aqui.</p>
                            </div>
                        </div>

                        {{-- Pergunta: trocou de núcleo após o TLC? --}}
                        <div x-show="t_evt && t_data" x-transition class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-3">Depois do TLC, você trocou de núcleo?</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="t_trocou" value="sim" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Sim</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="t_trocou" value="nao" class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Não, estou no mesmo</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="t_trocou === 'nao'" x-transition>
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-700">Desde quando você está neste núcleo?</p>
                                    <input type="date" x-model="t_desde_quando" class="mt-2 w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <p class="text-xs text-blue-500 mt-1">💡 Se não lembrar, coloque 01/01 do ano aproximado.</p>
                                </div>
                            </div>

                            {{-- Mudanças pós TLC (repetível) --}}
                            <div x-show="t_trocou === 'sim'" x-transition class="space-y-3">
                                <template x-for="(n, idx) in t_pos" :key="n._id">
                                    <div class="p-4 bg-gray-50 rounded-xl space-y-3 relative">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide" x-text="'Núcleo ' + (idx + 1)"></p>
                                            <button type="button" @click="t_pos.splice(idx, 1)" x-show="idx > 0"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese</label>
                                            <div class="flex gap-2 items-center">
                                                <select x-model="n.dio" @change="n.nuc = null"
                                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="">Selecione</option>
                                                    <template x-for="d in dioceses" :key="d.id"><option :value="d.id" x-text="d.nome"></option></template>
                                                </select>
                                                <button type="button" @click="openModal('diocese', 't_dio', {})" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 shrink-0">Não encontrei</button>
                                            </div>
                                        </div>
                                        <div x-show="n.dio" x-transition>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Núcleo</label>
                                            <div class="flex gap-2 items-center">
                                                <select x-model="n.nuc"
                                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    <option value="">Selecione</option>
                                                    <template x-for="nc in nucsPorDio(n.dio)" :key="nc.id"><option :value="nc.id" x-text="nc.nome"></option></template>
                                                </select>
                                                <button type="button" @click="openModal('nucleo', 'inline', { entidade_pai_id: n.dio })" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 shrink-0">Não encontrei</button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Entrou em</label>
                                                <input type="date" x-model="n.entrada" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            <div x-show="!n.ativo">
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Saiu em</label>
                                                <input type="date" x-model="n.saida" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400">💡 Se não lembrar a data, coloque 01/01 do ano aproximado.</p>
                                        <label class="flex items-center gap-2 cursor-pointer mt-1">
                                            <input type="checkbox" x-model="n.ativo" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <span class="text-sm text-gray-700">Ainda estou neste núcleo (núcleo atual)</span>
                                        </label>
                                    </div>
                                </template>

                                <div x-show="t_pos.length === 0 || !t_pos[t_pos.length-1].ativo" x-transition>
                                    <button type="button"
                                        @click="t_pos.push({ _id: Date.now(), dio: null, nuc: null, entrada: '', saida: '', ativo: false })"
                                        class="w-full py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                                        + Adicionar próximo núcleo
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Checkbox TLC de Pais --}}
                        <div x-show="t_trocou !== null" x-transition class="pt-1">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="participa_nucleo_tlc_pais" value="1" x-model="tlc_pais"
                                    class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm text-gray-700">Além do núcleo principal, também participo do <strong>Núcleo do TLC de Pais</strong>.</span>
                            </label>
                        </div>

                    </div>{{-- /fez_tlc sim --}}

                    {{-- SE NÃO FEZ O TLC --}}
                    <div x-show="fez_tlc === 'nao'" x-transition class="space-y-5">
                        <div class="p-4 bg-gray-50 rounded-xl space-y-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Qual é o seu núcleo atual?</p>
                            @include('inscricao._select-row', ['label' => 'Diocese', 'xModel' => 'direto_dio', 'optExpr' => 'dioceses', 'onChange' => 'direto_nuc = null', 'modalType' => 'diocese', 'modalTarget' => 'direto_dio', 'modalPrefill' => '{}'])
                            <div x-show="direto_dio" x-transition>
                                @include('inscricao._select-row', ['label' => 'Núcleo', 'xModel' => 'direto_nuc', 'optExpr' => 'nucsPorDio(direto_dio)', 'modalType' => 'nucleo', 'modalTarget' => 'direto_nuc', 'modalPrefill' => '{ entidade_pai_id: direto_dio }'])
                            </div>
                            <div x-show="direto_nuc" x-transition>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Desde quando está neste núcleo?</label>
                                <input type="date" x-model="direto_entrada"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">💡 Se não lembrar, coloque 01/01 do ano aproximado.</p>
                            </div>
                        </div>

                        <div x-show="direto_nuc" x-transition>
                            <p class="text-sm text-gray-600 mb-3">Antes deste núcleo, você estava em outro?</p>
                            <div class="flex gap-6 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="direto_tem_antes" value="sim" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Sim</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="direto_tem_antes" value="nao" class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Não</span>
                                </label>
                            </div>

                            <div x-show="direto_tem_antes === 'sim'" x-transition class="space-y-3">
                                <template x-for="(n, idx) in direto_antes" :key="n._id">
                                    <div class="p-4 bg-gray-50 rounded-xl space-y-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide" x-text="'Núcleo anterior ' + (idx + 1)"></p>
                                            <button type="button" @click="direto_antes.splice(idx, 1)"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese</label>
                                            <select x-model="n.dio" @change="n.nuc = null" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                <option value="">Selecione</option>
                                                <template x-for="d in dioceses" :key="d.id"><option :value="d.id" x-text="d.nome"></option></template>
                                            </select>
                                        </div>
                                        <div x-show="n.dio" x-transition>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Núcleo</label>
                                            <select x-model="n.nuc" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                <option value="">Selecione</option>
                                                <template x-for="nc in nucsPorDio(n.dio)" :key="nc.id"><option :value="nc.id" x-text="nc.nome"></option></template>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Entrou em</label>
                                                <input type="date" x-model="n.entrada" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Saiu em</label>
                                                <input type="date" x-model="n.saida" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400">💡 Se não lembrar, coloque 01/01 do ano aproximado.</p>
                                    </div>
                                </template>
                                <button type="button" @click="direto_antes.push({ _id: Date.now(), dio: null, nuc: null, entrada: '', saida: '' })"
                                    class="w-full py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                                    + Adicionar outro núcleo anterior
                                </button>
                            </div>
                        </div>

                        <div x-show="direto_nuc" x-transition class="pt-1">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="participa_nucleo_tlc_pais" value="1" x-model="tlc_pais"
                                    class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm text-gray-700">Além do núcleo principal, também participo do <strong>Núcleo do TLC de Pais</strong>.</span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ===== STEP 4: SECRETARIAS & HABILIDADES ===== --}}
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex items-start justify-between mb-2">
                        <h2 class="text-base font-semibold text-gray-700">Secretarias e Habilidades</h2>
                        <button type="button" @click="openModal('secretaria', 'secretaria')"
                            class="shrink-0 text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors">
                            Não encontrei minha secretaria
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Selecione as secretarias das quais você participa e informe o nível em cada habilidade.</p>

                    <div class="space-y-3">
                        <template x-for="s in secretarias" :key="s.id">
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                <label class="flex items-center gap-3 px-4 py-3.5 cursor-pointer hover:bg-gray-50 transition-colors select-none">
                                    <input type="checkbox" :value="s.id" x-model="secs_sel" name="secretarias[]"
                                        @change="onSecChange(s.id)"
                                        class="rounded text-blue-600 focus:ring-blue-500 border-gray-300 shrink-0">
                                    <span class="text-sm font-medium text-gray-700" x-text="s.nome"></span>
                                </label>
                                <div x-show="secs_sel.map(Number).includes(Number(s.id))"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    class="border-t border-gray-100 bg-gray-50 px-4 py-4">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Habilidades</p>
                                    <div x-show="habsPorSec(s.id).length === 0">
                                        <p class="text-sm text-gray-400 italic">Nenhuma habilidade cadastrada para esta secretaria.</p>
                                    </div>
                                    <div class="space-y-3">
                                        <template x-for="h in habsPorSec(s.id)" :key="h.id">
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm text-gray-700 flex-1 min-w-0 truncate" x-text="h.nome"></span>
                                                <select x-model="habs_niveis[h.id]" :name="`habilidades[${h.id}][nivel]`"
                                                    class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 w-44 shrink-0">
                                                    <option value="">Nível</option>
                                                    <template x-for="n in niveis" :key="n.value">
                                                        <option :value="n.value" x-text="n.label"></option>
                                                    </template>
                                                </select>
                                                <input type="hidden" :name="`habilidades[${h.id}][id]`" :value="h.id">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ===== STEP 5: RESUMO ===== --}}
            <div x-show="step === 5" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="space-y-4">
                    {{-- Dados pessoais --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Dados Pessoais</h3>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p><span class="text-gray-400">Nome:</span> <span x-text="$el.closest('form').querySelector('[name=nome]')?.value || '—'"></span></p>
                        </div>
                    </div>

                    {{-- Linha do tempo de núcleos --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Linha do Tempo — Núcleos</h3>
                        <template x-if="computeTimeline().length === 0">
                            <p class="text-sm text-gray-400 italic">Nenhum núcleo informado.</p>
                        </template>
                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                            <div class="space-y-4 pl-10">
                                <template x-for="(p, i) in computeTimeline()" :key="i">
                                    <div class="relative">
                                        <div class="absolute -left-6 top-1 w-3 h-3 rounded-full border-2 border-white shadow"
                                            :class="p.ativo ? 'bg-blue-500' : 'bg-gray-300'"></div>
                                        <p class="text-sm font-medium text-gray-700" x-text="nucNome(p.nucleo_id)"></p>
                                        <p class="text-xs text-gray-400">
                                            <span x-text="p.data_inicio || '?'"></span>
                                            <span> → </span>
                                            <span x-text="p.ativo ? 'atual' : (p.data_fim || '?')"></span>
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Mini TLC & TLC --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">TLC e Mini TLC</h3>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p>
                                <span class="text-gray-400">Mini TLC: </span>
                                <span x-text="fez_mini === 'sim' && m_evt ? evtLabel(eventosMiniTlc.find(e => Number(e.id) === Number(m_evt))) : (fez_mini === 'nao' ? 'Não fez' : '—')"></span>
                            </p>
                            <p>
                                <span class="text-gray-400">TLC: </span>
                                <span x-text="fez_tlc === 'sim' && t_evt ? evtLabel(eventosTlc.find(e => Number(e.id) === Number(t_evt))) : (fez_tlc === 'nao' ? 'Ainda não fez' : '—')"></span>
                            </p>
                        </div>
                    </div>

                    {{-- Secretarias --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Secretarias</h3>
                        <template x-if="secs_sel.length === 0">
                            <p class="text-sm text-gray-400 italic">Nenhuma secretaria selecionada.</p>
                        </template>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="id in secs_sel" :key="id">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs rounded-full font-medium"
                                    x-text="secretarias.find(s => Number(s.id) === Number(id))?.nome || id"></span>
                            </template>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 text-center">Revise as informações acima antes de enviar.</p>
                </div>
            </div>

            {{-- Navegação --}}
            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep()" x-show="step > 1"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Anterior
                </button>
                <div x-show="step === 1"></div>

                <button type="button" @click="nextStep()" x-show="step < 5"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                    Próximo
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button type="submit" x-show="step === 5"
                    class="flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Enviar inscrição
                </button>
            </div>
        </form>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>
@endsection

@push('scripts')
<script>
function inscricao(dioceses, nucleos, secretarias, habilidades, eventosTlc, eventosMiniTlc, niveis) {
    return {
        step: 1, maxStep: 1,
        steps: ['Dados', 'Mini TLC', 'TLC', 'Secretarias', 'Resumo'],

        nextStep() { if(this.step<5){this.step++;this.maxStep=Math.max(this.maxStep,this.step);window.scrollTo({top:0,behavior:'smooth'});} },
        prevStep() { if(this.step>1){this.step--;window.scrollTo({top:0,behavior:'smooth'});} },
        goToStep(n) { if(n<=this.maxStep){this.step=n;window.scrollTo({top:0,behavior:'smooth'});} },

        // Mini TLC
        fez_mini: null,
        m_dio: null, m_nuc: null, m_evt: null, m_data: '',
        m_mudou_pos: null,
        m_pos_dio: null, m_pos_nuc: null, m_pos_entrada: '',
        m_tem_antes: null,
        m_antes: [],

        onMiniEvtChange() {
            const e = this.eventosMiniTlc.find(x => Number(x.id) === Number(this.m_evt));
            if (e && e.data_inicio) this.m_data = e.data_inicio;
        },

        // TLC
        fez_tlc: null,
        t_tipo: '', t_dio: null, t_nuc: null, t_evt: null, t_data: '',
        t_trocou: null, t_desde_quando: '',
        t_pos: [],
        tlc_pais: false,

        onTlcEvtChange() {
            const e = this.eventosTlc.find(x => Number(x.id) === Number(this.t_evt));
            if (e && e.data_inicio) this.t_data = e.data_inicio;
        },

        // Direto (sem TLC)
        direto_dio: null, direto_nuc: null, direto_entrada: '',
        direto_tem_antes: null, direto_antes: [],

        // Secretarias
        secs_sel: [], habs_niveis: {},

        // Modal
        modal: null, modal_target: null,
        mf: { nome: '', tema: '', data_inicio: '', entidade_pai_id: null, entidade_criadora_id: null },
        mloading: false,

        // Dados
        dioceses, nucleos, secretarias, habilidades, eventosTlc, eventosMiniTlc, niveis,

        routes: {
            diocese:    "{{ route('quick.diocese') }}",
            nucleo:     "{{ route('quick.nucleo') }}",
            secretaria: "{{ route('quick.secretaria') }}",
            tlc:        "{{ route('quick.tlc') }}",
            mini_tlc:   "{{ route('quick.mini-tlc') }}",
        },

        openModal(type, target, prefill) {
            this.modal = type;
            this.modal_target = target;
            this.mf = Object.assign({ nome: '', tema: '', data_inicio: '', entidade_pai_id: null, entidade_criadora_id: null }, prefill || {});
        },

        async submitModal() {
            if (!this.mf.nome.trim()) {
                Swal.fire({ icon: 'warning', title: 'Campo obrigatório', text: 'Informe o nome.', confirmButtonColor: '#465fff' });
                return;
            }
            this.mloading = true;
            const key = this.modal === 'mini_tlc' ? 'mini_tlc' : this.modal;
            try {
                const res = await fetch(this.routes[key], {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify(this.mf),
                });
                if (!res.ok) { const e = await res.json(); throw new Error(Object.values(e.errors||{}).flat().join(' ')||'Erro.'); }
                const data = await res.json();
                this.onQuickSuccess(data);
            } catch(e) {
                Swal.fire({ icon: 'error', title: 'Erro', text: e.message, confirmButtonColor: '#465fff' });
            } finally { this.mloading = false; }
        },

        onQuickSuccess(data) {
            const type = this.modal, target = this.modal_target;
            if (type === 'diocese') {
                this.dioceses = [...this.dioceses, data];
                if (target === 'm_dio')       this.m_dio       = data.id;
                if (target === 'm_pos_dio')   this.m_pos_dio   = data.id;
                if (target === 't_dio')       this.t_dio       = data.id;
                if (target === 'direto_dio')  this.direto_dio  = data.id;
            }
            if (type === 'nucleo') {
                this.nucleos = [...this.nucleos, data];
                if (target === 'm_nuc')       this.m_nuc       = data.id;
                if (target === 'm_pos_nuc')   this.m_pos_nuc   = data.id;
                if (target === 't_nuc')       this.t_nuc       = data.id;
                if (target === 'direto_nuc')  this.direto_nuc  = data.id;
            }
            if (type === 'secretaria') this.secretarias = [...this.secretarias, data];
            if (type === 'tlc')      { this.eventosTlc     = [...this.eventosTlc, data];     this.t_evt = data.id; }
            if (type === 'mini_tlc') { this.eventosMiniTlc = [...this.eventosMiniTlc, data]; this.m_evt = data.id; this.onMiniEvtChange(); }
            this.modal = null;
            Swal.fire({ icon: 'success', title: 'Cadastrado!', text: '"' + data.nome + '" adicionado e selecionado.', timer: 2500, showConfirmButton: false, timerProgressBar: true });
        },

        // Helpers de filtro
        nucsPorDio(id)        { return id ? this.nucleos.filter(n => Number(n.entidade_pai_id) === Number(id)) : []; },
        habsPorSec(id)        { return this.habilidades.filter(h => Number(h.entidade_id) === Number(id)); },
        minisPorNuc(id)       { return id ? this.eventosMiniTlc.filter(e => Number(e.entidade_criadora_id) === Number(id)) : []; },
        tlcsPorEntidade(id)   { return id ? this.eventosTlc.filter(e => Number(e.entidade_criadora_id) === Number(id)) : []; },
        evtLabel(e)           { return e ? (e.tema ? e.nome + ' — ' + e.tema : e.nome) : '—'; },
        nucNome(id)           { return this.nucleos.find(n => Number(n.id) === Number(id))?.nome || ('Núcleo #' + id); },

        onSecChange(id) {
            if (!this.secs_sel.map(Number).includes(Number(id))) {
                this.habsPorSec(id).forEach(h => delete this.habs_niveis[h.id]);
            }
        },

        // Montar timeline cronológica para salvar
        computeTimeline() {
            const periods = [];

            if (this.fez_tlc === 'sim') {
                // Núcleos anteriores ao TLC (inseridos pelo usuário, do mais recente p/ mais antigo)
                [...this.m_antes].reverse().forEach(n => {
                    if (n.nuc) periods.push({ nucleo_id: n.nuc, data_inicio: n.entrada || null, data_fim: n.saida || null, ativo: false });
                });

                // Núcleo do Mini TLC (se mudou após mini)
                if (this.fez_mini === 'sim' && this.m_mudou_pos === 'sim' && this.m_pos_nuc) {
                    // O núcleo do mini TLC em si: saiu quando foi para o próximo
                    if (this.m_nuc && this.m_nuc !== this.m_pos_nuc) {
                        periods.push({ nucleo_id: this.m_nuc, data_inicio: this.m_data || null, data_fim: this.m_pos_entrada || null, ativo: false });
                    }
                    // Núcleo pós mini (pode ser o mesmo do TLC ou outro)
                    const posEhTlc = Number(this.m_pos_nuc) === Number(this.t_nuc);
                    if (!posEhTlc) {
                        periods.push({ nucleo_id: this.m_pos_nuc, data_inicio: this.m_pos_entrada || null, data_fim: this.t_data || null, ativo: false });
                    }
                } else if (this.fez_mini === 'sim' && this.m_nuc && this.m_mudou_pos === 'nao') {
                    // Ficou no mesmo após mini — apenas registra se diferente do TLC nucleus
                    if (Number(this.m_nuc) !== Number(this.t_nuc)) {
                        periods.push({ nucleo_id: this.m_nuc, data_inicio: this.m_data || null, data_fim: this.t_data || null, ativo: false });
                    }
                }

                // Núcleo do TLC
                if (this.t_nuc) {
                    if (this.t_trocou === 'nao') {
                        // Ainda no mesmo
                        periods.push({ nucleo_id: this.t_nuc, data_inicio: this.t_desde_quando || this.t_data || null, data_fim: null, ativo: true });
                    } else if (this.t_trocou === 'sim' && this.t_pos.length > 0) {
                        // Saiu do TLC nucleus — data_fim é a entrada do próximo
                        periods.push({ nucleo_id: this.t_nuc, data_inicio: this.t_data || null, data_fim: this.t_pos[0].entrada || null, ativo: false });
                        // Demais mudanças pós TLC
                        this.t_pos.forEach((n, idx) => {
                            if (!n.nuc) return;
                            const next = this.t_pos[idx + 1];
                            periods.push({ nucleo_id: n.nuc, data_inicio: n.entrada || null, data_fim: n.ativo ? null : (next ? next.entrada : n.saida || null), ativo: !!n.ativo });
                        });
                    }
                }

            } else if (this.fez_tlc === 'nao') {
                // Sem TLC — apenas núcleo direto e anteriores
                [...this.direto_antes].reverse().forEach(n => {
                    if (n.nuc) periods.push({ nucleo_id: n.nuc, data_inicio: n.entrada || null, data_fim: n.saida || null, ativo: false });
                });
                if (this.direto_nuc) {
                    periods.push({ nucleo_id: this.direto_nuc, data_inicio: this.direto_entrada || null, data_fim: null, ativo: true });
                }
            }

            return periods;
        },
    };
}
</script>
@endpush
