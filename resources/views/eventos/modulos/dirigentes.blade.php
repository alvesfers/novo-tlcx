@extends('layouts.app')

@section('content')
<div x-data="dirigentesPage()" class="container mx-auto px-4 py-8">

    {{-- ===== MODAL: INFORMAÇÕES DO PARTICIPANTE ===== --}}
    <div x-show="modInfoOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm"
         @click="modInfoOpen = false; document.body.style.overflow = ''"></div>

    <div x-show="modInfoOpen" x-cloak x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h2 class="text-base font-semibold text-gray-800">Informações do Participante</h2>
                <button @click="modInfoOpen = false; document.body.style.overflow = ''"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 pb-6 flex-1 space-y-5" x-show="infoAtual">
                {{-- Avatar + nome --}}
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold shrink-0"
                         x-text="infoAtual ? infoAtual.nome.charAt(0).toUpperCase() : ''"></div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900" x-text="infoAtual?.nome"></p>
                        <p class="text-sm text-gray-400" x-text="infoAtual?.apelido ? '&quot;' + infoAtual.apelido + '&quot;' : ''"></p>
                    </div>
                </div>

                {{-- Dados pessoais --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Telefone</p>
                        <p class="text-sm text-gray-800" x-text="infoAtual?.telefone || '—'"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">E-mail</p>
                        <p class="text-sm text-gray-800 truncate" x-text="infoAtual?.email || '—'"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Gênero</p>
                        <p class="text-sm text-gray-800" x-text="generoLabel(infoAtual?.genero)"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Nascimento</p>
                        <p class="text-sm text-gray-800" x-text="formatDate(infoAtual?.data_nascimento)"></p>
                    </div>
                </div>

                {{-- Dados do evento --}}
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">No Evento</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Função</p>
                            <p class="text-sm text-gray-800" x-text="infoAtual?.funcao || '—'"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Inscrição</p>
                            <p class="text-sm text-gray-800" x-text="formatDateTime(infoAtual?.inscricao_em)"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3" x-show="infoAtual?.presenca">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Check-in</p>
                            <p class="text-sm text-gray-800" x-text="formatDateTime(infoAtual?.checkin_em)"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3" x-show="infoAtual?.camiseta">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Camiseta</p>
                            <p class="text-sm text-gray-800" x-text="infoAtual?.camiseta"></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3" x-show="infoAtual?.observacao">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Observação</p>
                        <p class="text-sm text-gray-800" x-text="infoAtual?.observacao"></p>
                    </div>
                </div>

                {{-- Links --}}
                <div class="flex flex-wrap gap-2 pt-1">
                    <a :href="'/dirigentes/' + infoAtual?.dirigente_id" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Ver perfil
                    </a>
                    <a x-show="infoAtual?.formulario_url" :href="infoAtual?.formulario_url" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-purple-600 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Ver formulário
                    </a>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t border-gray-100">
                <button @click="modInfoOpen = false; document.body.style.overflow = ''"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: ADICIONAR PARTICIPANTES ===== --}}
    <div x-show="modAddOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm"
         @click="closeAdd()"></div>

    <div x-show="modAddOpen" x-cloak x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h2 class="text-base font-semibold text-gray-800">Adicionar Participantes</h2>
                <button @click="closeAdd()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 pb-2 flex-1 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Origem</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-gray-50">
                            <input type="radio" x-model="addOrigem" value="escopo" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Escopo do evento</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-gray-50">
                            <input type="radio" x-model="addOrigem" value="outro" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Outra fonte</span>
                        </label>
                    </div>
                </div>

                <div x-show="addOrigem === 'escopo'" x-transition>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Entidade do Evento</label>
                    <select x-model="addEntidadeId" @change="buscarDirigentes()"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Selecione uma entidade...</option>
                        @foreach ($entidadesEvento as $ent)
                            <option value="{{ $ent->id }}">{{ $ent->nome }} ({{ $ent->tipo_entidade }})</option>
                        @endforeach
                        @if ($entidadesEvento->isEmpty())
                            <option value="{{ $evento->entidade_criadora_id }}">{{ $evento->entidadeCriadora->nome ?? '' }} (criadora)</option>
                        @endif
                    </select>
                </div>

                <div x-show="addOrigem === 'outro'" x-transition class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipo</label>
                        <select x-model="addTipo" @change="addEntidadeId = ''; addNucleoId = ''; dirigentesDisponiveis = []"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            <option value="diocese">Diocese</option>
                            <option value="nucleo">Núcleo</option>
                            <option value="secretaria">Secretaria</option>
                        </select>
                    </div>
                    <div x-show="addTipo === 'diocese'" x-transition>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese</label>
                        <select x-model="addEntidadeId" @change="buscarDirigentes()"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            @foreach ($dioceses as $d)
                                <option value="{{ $d->id }}">{{ $d->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="addTipo === 'nucleo'" x-transition class="space-y-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diocese</label>
                            <select x-model="addDioceseId" @change="addNucleoId = ''; dirigentesDisponiveis = []"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                @foreach ($dioceses as $d)
                                    <option value="{{ $d->id }}">{{ $d->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-show="addDioceseId" x-transition>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Núcleo</label>
                            <select x-model="addNucleoId" @change="addEntidadeId = addNucleoId; buscarDirigentes()"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <template x-for="n in nucleosDaDiocese()" :key="n.id">
                                    <option :value="n.id" x-text="n.nome"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div x-show="addTipo === 'secretaria'" x-transition>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Secretaria</label>
                        <select x-model="addEntidadeId" @change="buscarDirigentes()"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione...</option>
                            @foreach ($secretarias as $s)
                                <option value="{{ $s->id }}">{{ $s->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div x-show="dirigentesDisponiveis.length > 0 || addCarregando" x-transition class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Disponíveis</label>
                        <button type="button" x-show="dirigentesDisponiveis.length > 0" @click="selecionarTodos()"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">Selecionar todos</button>
                    </div>
                    <div x-show="addCarregando" class="text-center py-3">
                        <svg class="animate-spin w-5 h-5 mx-auto text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                    <div x-show="!addCarregando && dirigentesDisponiveis.length > 0">
                        <input type="text" x-model="addBusca" placeholder="Filtrar por nome..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 mb-2">
                        <div class="border border-gray-100 rounded-xl overflow-hidden max-h-44 overflow-y-auto">
                            <template x-for="d in dirigentesFiltrados()" :key="d.id">
                                <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0">
                                    <input type="checkbox" :value="d.id" x-model="addSelecionados"
                                        class="rounded text-blue-600 focus:ring-blue-500 border-gray-300 shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate" x-text="d.nome"></p>
                                        <p class="text-xs text-gray-400 truncate" x-text="d.email || 'sem e-mail'"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                    <p x-show="!addCarregando && dirigentesDisponiveis.length === 0 && addEntidadeId"
                        class="text-sm text-gray-400 text-center py-2">Nenhum dirigente disponível.</p>
                </div>

                {{-- Seleção de inscrição --}}
                @if($tiposInscricao->isNotEmpty())
                <div x-show="addSelecionados.length > 0" x-transition class="space-y-3 border-t border-gray-100 pt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo de Inscrição</p>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                        <select x-model="addInscricaoTipoId" @change="addInscricaoOpcaoId = ''; addInscricaoCamisetaTipo = ''"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione (opcional)...</option>
                            @foreach($tiposInscricao as $tipoInsc)
                            <option value="{{ $tipoInsc->id }}" data-opcoes="{{ $tipoInsc->opcoesAtivas->toJson() }}">
                                {{ $tipoInsc->nome }} ({{ $tipoInsc->publicoLabel() }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="addInscricaoTipoId" x-transition>
                        <label class="block text-xs text-gray-500 mb-1">Opção / Valor</label>
                        <select x-model="addInscricaoOpcaoId" @change="addInscricaoCamisetaTipo = ''"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione a opção...</option>
                            <template x-for="op in opcoesDeTipo()" :key="op.id">
                                <option :value="op.id" x-text="op.nome + ' — R$ ' + parseFloat(op.valor_base).toFixed(2).replace('.', ',')"></option>
                            </template>
                        </select>
                    </div>
                    <div x-show="addInscricaoOpcaoId && opcaoSelecionada()?.inclui_camiseta" x-transition>
                        <label class="block text-xs text-gray-500 mb-1">Tipo de Camiseta</label>
                        <select x-model="addInscricaoCamisetaTipo"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Selecione o tipo...</option>
                            <template x-for="cam in camisetasDaOpcao()" :key="cam.tipo_camiseta">
                                <option :value="cam.tipo_camiseta"
                                    x-text="labelCamiseta(cam.tipo_camiseta) + (cam.valor_adicional > 0 ? ' +R$ ' + parseFloat(cam.valor_adicional).toFixed(2).replace('.', ',') : ' (incluso)')">
                                </option>
                            </template>
                        </select>
                    </div>
                </div>
                @endif

                <div x-show="addSelecionados.length > 0" x-transition>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Selecionados (<span x-text="addSelecionados.length"></span>)
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="id in addSelecionados" :key="id">
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                <span x-text="nomeDirigente(id)"></span>
                                <button type="button" @click="addSelecionados = addSelecionados.filter(x => x !== id)"
                                    class="text-blue-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button @click="closeAdd()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Cancelar</button>
                <button @click="salvarParticipantes()" :disabled="addSelecionados.length === 0 || addSalvando"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg min-w-[90px] transition-colors">
                    <span x-show="!addSalvando">Adicionar (<span x-text="addSelecionados.length"></span>)</span>
                    <span x-show="addSalvando">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: TODOS DO ESCOPO ===== --}}
    <div x-show="modEscopoOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm"
         @click="modEscopoOpen = false"></div>

    <div x-show="modEscopoOpen" x-cloak x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.stop>
            <h3 class="text-base font-semibold text-gray-800 mb-2">Adicionar Todos do Escopo</h3>
            <p class="text-sm text-gray-500 mb-5">
                Adiciona todos os dirigentes das entidades do evento com escopo
                <strong class="text-gray-700">{{ $evento->escopo->label() }}</strong>. Já adicionados são ignorados.
            </p>
            <div class="flex justify-end gap-3">
                <button @click="modEscopoOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <form method="POST" action="{{ route('eventos.participantes.todos-escopo', $evento) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Confirmar</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== HEADER ===== --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Participantes Dirigentes</h1>
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

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- ===== TABLE ===== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-base font-semibold text-gray-800">Dirigentes</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $participantes->count() }} participante(s) · {{ $participantes->where('presenca', true)->count() }} presentes</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="modEscopoOpen = true"
                    class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 px-4 py-2 text-sm font-medium hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Todos do Escopo
                </button>
                <button @click="openAdd()"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nome</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Telefone</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Função</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Inscrição</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Inscrito em</th>
                        @if($camisetaHabilitada)
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Camiseta</th>
                        @endif
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Presença</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participantes as $p)
                    @php
                        $camiseta = null;
                        if ($camisetaHabilitada) {
                            $medida = $p->camisetaMedidas->first();
                            if ($medida && $medida->tamanho) {
                                $camiseta = ($p->tipo_camiseta ? ucfirst($p->tipo_camiseta) . ' ' : '') . $medida->tamanho->tamanho ?? '';
                            } elseif ($p->tamanho_camiseta) {
                                $camiseta = ($p->tipo_camiseta ? ucfirst($p->tipo_camiseta) . ' ' : '') . strtoupper($p->tamanho_camiseta);
                            }
                        }
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors group">
                        {{-- Nome --}}
                        <td class="px-5 py-3.5">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $p->dirigente->nome }}</p>
                                @if($p->dirigente->apelido)
                                    <p class="text-xs text-gray-400">"{{ $p->dirigente->apelido }}"</p>
                                @endif
                            </div>
                        </td>
                        {{-- Telefone --}}
                        <td class="px-5 py-3.5 text-sm text-gray-500 whitespace-nowrap">
                            {{ $p->dirigente->telefone ?: '—' }}
                        </td>
                        {{-- Função --}}
                        <td class="px-5 py-3.5 text-sm text-gray-500">
                            {{ $p->funcaoDirigente?->nome ?? '—' }}
                        </td>
                        {{-- Inscrição --}}
                        <td class="px-5 py-3.5">
                            @if($p->inscricaoOpcao)
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $p->inscricaoOpcao->nome }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $p->inscricaoOpcao->tipo->nome }}
                                        @if($p->inscricao_camiseta_tipo)
                                            · {{ match($p->inscricao_camiseta_tipo) {
                                                'normal' => 'Camiseta Normal', 'plus' => 'Camiseta Plus',
                                                'babylook' => 'Babylook', 'oversized' => 'Oversized',
                                                'infantil' => 'Infantil', default => $p->inscricao_camiseta_tipo
                                            } }}
                                        @endif
                                    </p>
                                    <p class="text-xs font-semibold text-blue-600">R$ {{ number_format($p->inscricaoOpcao->valor_base, 2, ',', '.') }}</p>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>
                        {{-- Inscrito em --}}
                        <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                            {{ $p->created_at->format('d/m/Y H:i') }}
                        </td>
                        {{-- Camiseta --}}
                        @if($camisetaHabilitada)
                        <td class="px-5 py-3.5 text-sm text-gray-500">
                            {{ $camiseta ?: '—' }}
                        </td>
                        @endif
                        {{-- Presença --}}
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $p->presenca ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $p->presenca ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $p->presenca ? 'Presente' : 'Ausente' }}
                            </span>
                        </td>
                        {{-- Ações --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-0.5">
                                {{-- Marcar presença --}}
                                @if(!$p->presenca)
                                <form method="POST" action="{{ route('eventos.participantes.presenca', [$evento, $p]) }}" class="inline">
                                    @csrf
                                    <button type="submit" title="Marcar presença"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                {{-- Desmarcar presença --}}
                                <form method="POST" action="{{ route('eventos.participantes.desmarcar-presenca', [$evento, $p]) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Desmarcar presença"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                {{-- Informações --}}
                                <button type="button" title="Informações"
                                    @click="abrirInfo({{ json_encode([
                                        'id'              => $p->id,
                                        'dirigente_id'    => $p->dirigente_id,
                                        'nome'            => $p->dirigente->nome,
                                        'apelido'         => $p->dirigente->apelido,
                                        'telefone'        => $p->dirigente->telefone,
                                        'email'           => $p->dirigente->email,
                                        'genero'          => $p->dirigente->genero,
                                        'data_nascimento' => $p->dirigente->data_nascimento?->format('Y-m-d'),
                                        'funcao'          => $p->funcaoDirigente?->nome,
                                        'inscricao_em'    => $p->created_at->toIso8601String(),
                                        'presenca'        => $p->presenca,
                                        'checkin_em'      => $p->checkin_em?->toIso8601String(),
                                        'observacao'      => $p->observacao,
                                        'camiseta'        => $camiseta,
                                        'formulario_url'  => null,
                                    ]) }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>

                                {{-- Remover --}}
                                <form method="POST" action="{{ route('eventos.participantes.destroy', [$evento, $p]) }}" class="inline"
                                      @submit.prevent="confirmarRemover($event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remover"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $camisetaHabilitada ? 8 : 7 }}" class="px-5 py-12 text-center text-gray-400 text-sm">
                            Nenhum participante adicionado ainda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dirigentesPage() {
    return {
        modInfoOpen: false,
        modAddOpen: false,
        modEscopoOpen: false,
        infoAtual: null,

        // Add modal state
        addOrigem: 'escopo',
        addTipo: '',
        addEntidadeId: '',
        addDioceseId: '',
        addNucleoId: '',
        addBusca: '',
        addCarregando: false,
        addSalvando: false,
        addSelecionados: [],
        dirigentesDisponiveis: [],
        todosDirigentes: [],
        nucleosData: @json($nucleos),
        tiposInscricaoData: @json($tiposInscricao),
        addInscricaoTipoId: '',
        addInscricaoOpcaoId: '',
        addInscricaoCamisetaTipo: '',

        abrirInfo(dados) {
            this.infoAtual = dados;
            this.modInfoOpen = true;
            document.body.style.overflow = 'hidden';
        },

        generoLabel(g) {
            return { 'm': 'Masculino', 'f': 'Feminino', 'M': 'Masculino', 'F': 'Feminino', 'o': 'Outro', 'O': 'Outro' }[g] || '—';
        },

        formatDate(d) {
            if (!d) return '—';
            const [y, m, day] = d.split('-');
            return `${day}/${m}/${y}`;
        },

        formatDateTime(d) {
            if (!d) return '—';
            const dt = new Date(d);
            return dt.toLocaleDateString('pt-BR') + ' ' + dt.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        },

        openAdd() {
            this.modAddOpen = true;
            this.addOrigem = 'escopo';
            this.addTipo = '';
            this.addEntidadeId = '';
            this.addDioceseId = '';
            this.addNucleoId = '';
            this.addBusca = '';
            this.addSelecionados = [];
            this.dirigentesDisponiveis = [];
            this.addInscricaoTipoId = '';
            this.addInscricaoOpcaoId = '';
            this.addInscricaoCamisetaTipo = '';
            document.body.style.overflow = 'hidden';
        },

        opcoesDeTipo() {
            const tipo = this.tiposInscricaoData.find(t => Number(t.id) === Number(this.addInscricaoTipoId));
            return tipo ? (tipo.opcoes_ativas || []) : [];
        },

        opcaoSelecionada() {
            return this.opcoesDeTipo().find(o => Number(o.id) === Number(this.addInscricaoOpcaoId));
        },

        camisetasDaOpcao() {
            const op = this.opcaoSelecionada();
            return op ? (op.camisetas_ativas || []) : [];
        },

        labelCamiseta(tipo) {
            return { normal: 'Normal', plus: 'Plus Size', babylook: 'Babylook', oversized: 'Oversized', infantil: 'Infantil' }[tipo] || tipo;
        },

        closeAdd() {
            this.modAddOpen = false;
            document.body.style.overflow = '';
        },

        nucleosDaDiocese() {
            if (!this.addDioceseId) return [];
            return this.nucleosData.filter(n => Number(n.entidade_pai_id) === Number(this.addDioceseId));
        },

        async buscarDirigentes() {
            if (!this.addEntidadeId) { this.dirigentesDisponiveis = []; return; }
            this.addCarregando = true;
            this.dirigentesDisponiveis = [];
            this.addSelecionados = [];
            try {
                const url = `{{ route('eventos.participantes.dirigentes-por-entidade', $evento) }}?entidade_id=${this.addEntidadeId}`;
                const resp = await fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
                this.dirigentesDisponiveis = await resp.json();
                this.todosDirigentes = [...this.dirigentesDisponiveis];
            } catch(e) { console.error(e); }
            finally { this.addCarregando = false; }
        },

        dirigentesFiltrados() {
            if (!this.addBusca.trim()) return this.dirigentesDisponiveis;
            const q = this.addBusca.toLowerCase();
            return this.dirigentesDisponiveis.filter(d => d.nome.toLowerCase().includes(q));
        },

        nomeDirigente(id) {
            const d = this.todosDirigentes.find(x => Number(x.id) === Number(id));
            return d ? d.nome : '—';
        },

        selecionarTodos() {
            this.dirigentesFiltrados().forEach(d => {
                if (!this.addSelecionados.includes(d.id)) this.addSelecionados.push(d.id);
            });
        },

        async salvarParticipantes() {
            if (!this.addSelecionados.length) return;
            this.addSalvando = true;
            try {
                const resp = await fetch('{{ route('eventos.participantes.store-lote', $evento) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({
                        dirigente_ids: this.addSelecionados,
                        inscricao_opcao_id: this.addInscricaoOpcaoId || null,
                        inscricao_camiseta_tipo: this.addInscricaoCamisetaTipo || null,
                    }),
                });
                const data = await resp.json();
                if (data.success) {
                    this.closeAdd();
                    Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message, showConfirmButton: false, timer: 1500 })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro', text: data.message || 'Erro ao salvar' });
                }
            } catch(e) {
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro ao salvar participantes' });
            } finally { this.addSalvando = false; }
        },

        confirmarRemover(event) {
            event.preventDefault();
            Swal.fire({
                icon: 'warning', title: 'Remover participante?', text: 'Esta ação não pode ser desfeita.',
                showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Remover', cancelButtonText: 'Cancelar',
            }).then(r => { if (r.isConfirmed) event.target.closest('form').submit(); });
        },
    };
}
</script>
@endpush
@endsection
