@extends('layouts.app')

@section('content')
<div x-data="inscricoesPage()" class="container mx-auto px-4 py-8">

    {{-- ===== MODAL: NOVO TIPO ===== --}}
    <div x-show="modTipoOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm" @click="fecharTipo()"></div>
    <div x-show="modTipoOpen" x-cloak x-transition class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h2 class="text-base font-semibold text-gray-800" x-text="tipoEditando ? 'Editar Tipo' : 'Novo Tipo de Inscrição'"></h2>
                <button @click="fecharTipo()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="tipoEditando ? `{{ url('/eventos/'.request()->route('evento')->id.'/inscricoes/tipos') }}/${tipoEditando}` : '{{ route('eventos.inscricoes.tipos.store', $evento) }}'"
                  method="POST" class="px-6 pb-6 space-y-4">
                @csrf
                <span x-show="tipoEditando" hidden><input type="hidden" name="_method" value="PUT"></span>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" x-model="tipoForm.nome" required
                        placeholder="Ex.: Dirigente Interno"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Público <span class="text-red-500">*</span></label>
                    <select name="publico" x-model="tipoForm.publico" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        <option value="dirigente_interno">Dirigente Interno</option>
                        <option value="dirigente_externo">Dirigente Externo</option>
                        <option value="externo">Participante Externo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Descrição</label>
                    <textarea name="descricao" x-model="tipoForm.descricao" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="fecharTipo()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg min-w-[80px]">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL: NOVA OPÇÃO ===== --}}
    <div x-show="modOpcaoOpen" x-cloak x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm" @click="fecharOpcao()"></div>
    <div x-show="modOpcaoOpen" x-cloak x-transition class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-800" x-text="opcaoEditando ? 'Editar Opção' : 'Nova Opção'"></h2>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="'Tipo: ' + (tipoAtualNome || '')"></p>
                </div>
                <button @click="fecharOpcao()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="opcaoEditando
                    ? `{{ url('/eventos/'.request()->route('evento')->id.'/inscricoes/tipos') }}/${tipoAtualId}/opcoes/${opcaoEditando}`
                    : `{{ url('/eventos/'.request()->route('evento')->id.'/inscricoes/tipos') }}/${tipoAtualId}/opcoes`"
                  method="POST" class="overflow-y-auto px-6 pb-6 flex-1 space-y-4">
                @csrf
                <span x-show="opcaoEditando" hidden><input type="hidden" name="_method" value="PUT"></span>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nome da opção <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" x-model="opcaoForm.nome" required
                        placeholder="Ex.: Só inscrição, Com camiseta..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Valor base (R$) <span class="text-red-500">*</span></label>
                    <input type="number" name="valor_base" x-model="opcaoForm.valor_base" min="0" step="0.01" required
                        placeholder="0,00"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Descrição</label>
                    <textarea name="descricao" x-model="opcaoForm.descricao" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="inclui_camiseta" value="0">
                    <input type="checkbox" name="inclui_camiseta" value="1" id="incCam" x-model="opcaoForm.inclui_camiseta"
                        class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                    <label for="incCam" class="text-sm font-medium text-gray-700 cursor-pointer">Esta opção inclui camiseta</label>
                </div>

                {{-- Tipos de camiseta disponíveis --}}
                <div x-show="opcaoForm.inclui_camiseta" x-transition class="border border-gray-100 rounded-xl p-4 space-y-3">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tipos de camiseta disponíveis</p>
                    @foreach(['normal' => 'Normal', 'plus' => 'Plus Size', 'babylook' => 'Babylook', 'oversized' => 'Oversized', 'infantil' => 'Infantil'] as $tipo => $label)
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="camisetas[{{ $tipo }}][selecionado]" value="0">
                        <input type="checkbox" name="camisetas[{{ $tipo }}][selecionado]" value="1"
                            id="cam_{{ $tipo }}" x-model="opcaoForm.camisetas.{{ $tipo }}.sel"
                            class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="cam_{{ $tipo }}" class="text-sm text-gray-700 w-28 cursor-pointer">{{ $label }}</label>
                        <div x-show="opcaoForm.camisetas.{{ $tipo }}.sel" x-transition class="flex items-center gap-2 flex-1">
                            <span class="text-xs text-gray-400">Valor adicional R$</span>
                            <input type="number" name="camisetas[{{ $tipo }}][valor_adicional]"
                                x-model="opcaoForm.camisetas.{{ $tipo }}.valor"
                                min="0" step="0.01" placeholder="0,00"
                                class="w-24 border border-gray-200 rounded-lg px-2 py-1 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="fecharOpcao()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg min-w-[80px]">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inscrições</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $evento->nome }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="abrirTipo()"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Tipo
            </button>
            <a href="{{ route('eventos.show', $evento) }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-3 text-sm">{{ session('success') }}</div>
    @endif

    @if($tipos->isEmpty())
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center">
        <div class="text-4xl mb-3">🎫</div>
        <p class="text-gray-500 text-sm mb-4">Nenhum tipo de inscrição configurado ainda.</p>
        <button @click="abrirTipo()"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-blue-700">
            + Criar primeiro tipo
        </button>
    </div>
    @endif

    {{-- ===== TIPOS E OPÇÕES ===== --}}
    <div class="space-y-6">
        @foreach($tipos as $tipo)
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            {{-- Tipo header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $tipo->publicoBadgeClass() }}">
                        {{ $tipo->publicoLabel() }}
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">{{ $tipo->nome }}</h3>
                        @if($tipo->descricao)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tipo->descricao }}</p>
                        @endif
                    </div>
                    @if(!$tipo->ativo)
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">inativo</span>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    <button @click="abrirOpcao({{ $tipo->id }}, '{{ addslashes($tipo->nome) }}')"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nova Opção
                    </button>
                    <button @click="abrirTipo({{ $tipo->id }}, '{{ addslashes($tipo->nome) }}', '{{ $tipo->publico }}', '{{ addslashes($tipo->descricao ?? '') }}')"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <form method="POST" action="{{ route('eventos.inscricoes.tipos.destroy', [$evento, $tipo]) }}" class="inline"
                          @submit.prevent="confirmarExcluir($event, 'tipo de inscrição')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Opções --}}
            @if($tipo->opcoes->isEmpty())
            <div class="px-6 py-8 text-center text-gray-400 text-sm">
                Nenhuma opção criada. Clique em "Nova Opção" para começar.
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($tipo->opcoes as $opcao)
                <div class="px-6 py-4 flex items-start justify-between gap-4 hover:bg-gray-50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-medium text-gray-800">{{ $opcao->nome }}</p>
                            @if(!$opcao->ativo)
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">inativo</span>
                            @endif
                            @if($opcao->inclui_camiseta)
                            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">inclui camiseta</span>
                            @endif
                        </div>
                        @if($opcao->descricao)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $opcao->descricao }}</p>
                        @endif
                        {{-- Camisetas disponíveis --}}
                        @if($opcao->camisetas->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach($opcao->camisetas as $cam)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full">
                                {{ $cam->tipoLabel() }}
                                @if($cam->valor_adicional > 0)
                                <span class="text-gray-400">+R${{ number_format($cam->valor_adicional, 2, ',', '.') }}</span>
                                @else
                                <span class="text-green-600">incluso</span>
                                @endif
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <p class="text-base font-bold text-gray-900">R$ {{ number_format($opcao->valor_base, 2, ',', '.') }}</p>
                        <div class="flex items-center gap-0.5">
                            <button @click="abrirOpcaoEditar(
                                {{ $tipo->id }}, '{{ addslashes($tipo->nome) }}',
                                {{ $opcao->id }},
                                {{ json_encode(['nome' => $opcao->nome, 'valor_base' => $opcao->valor_base, 'descricao' => $opcao->descricao, 'inclui_camiseta' => $opcao->inclui_camiseta]) }},
                                {{ json_encode($opcao->camisetas->pluck('valor_adicional', 'tipo_camiseta')) }}
                            )"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form method="POST"
                                action="{{ route('eventos.inscricoes.opcoes.destroy', [$evento, $tipo, $opcao]) }}"
                                class="inline" @submit.prevent="confirmarExcluir($event, 'opção')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function inscricoesPage() {
    return {
        // Tipo modal
        modTipoOpen: false,
        tipoEditando: null,
        tipoForm: { nome: '', publico: '', descricao: '' },

        // Opcao modal
        modOpcaoOpen: false,
        tipoAtualId: null,
        tipoAtualNome: '',
        opcaoEditando: null,
        opcaoForm: {
            nome: '', valor_base: '', descricao: '', inclui_camiseta: false,
            camisetas: {
                normal:    { sel: false, valor: 0 },
                plus:      { sel: false, valor: 0 },
                babylook:  { sel: false, valor: 0 },
                oversized: { sel: false, valor: 0 },
                infantil:  { sel: false, valor: 0 },
            }
        },

        abrirTipo(id = null, nome = '', publico = '', descricao = '') {
            this.tipoEditando = id;
            this.tipoForm = { nome, publico, descricao };
            this.modTipoOpen = true;
            document.body.style.overflow = 'hidden';
        },
        fecharTipo() { this.modTipoOpen = false; document.body.style.overflow = ''; },

        abrirOpcao(tipoId, tipoNome) {
            this.tipoAtualId = tipoId;
            this.tipoAtualNome = tipoNome;
            this.opcaoEditando = null;
            this.opcaoForm = {
                nome: '', valor_base: '', descricao: '', inclui_camiseta: false,
                camisetas: {
                    normal:    { sel: false, valor: 0 },
                    plus:      { sel: false, valor: 0 },
                    babylook:  { sel: false, valor: 0 },
                    oversized: { sel: false, valor: 0 },
                    infantil:  { sel: false, valor: 0 },
                }
            };
            this.modOpcaoOpen = true;
            document.body.style.overflow = 'hidden';
        },

        abrirOpcaoEditar(tipoId, tipoNome, opcaoId, dados, camisetasExistentes) {
            this.tipoAtualId = tipoId;
            this.tipoAtualNome = tipoNome;
            this.opcaoEditando = opcaoId;
            const tipos = ['normal', 'plus', 'babylook', 'oversized', 'infantil'];
            const cams = {};
            tipos.forEach(t => {
                cams[t] = {
                    sel: camisetasExistentes.hasOwnProperty(t),
                    valor: camisetasExistentes[t] ?? 0
                };
            });
            this.opcaoForm = { ...dados, camisetas: cams };
            this.modOpcaoOpen = true;
            document.body.style.overflow = 'hidden';
        },

        fecharOpcao() { this.modOpcaoOpen = false; document.body.style.overflow = ''; },

        confirmarExcluir(event, label) {
            event.preventDefault();
            Swal.fire({
                icon: 'warning', title: `Excluir ${label}?`,
                text: 'Esta ação não pode ser desfeita.',
                showCancelButton: true, confirmButtonColor: '#dc2626',
                confirmButtonText: 'Excluir', cancelButtonText: 'Cancelar',
            }).then(r => { if (r.isConfirmed) event.target.closest('form').submit(); });
        },
    };
}
</script>
@endpush
@endsection
