@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Configurar Formulários</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>
        <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Formulário Dirigentes -->
        <div x-data="gerenciadorFormulario('dirigentes', {{ json_encode($formularioDirigentes) }})">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Formulário de Dirigentes</h2>
                    <button @click="abrirModalAdicionarCampo()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                        + Campo
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse ($formularioDirigentes as $campo)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" x-data="{ editando: false }">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900">{{ $campo['nome'] }}</h3>
                                    @if ($campo['descricao'])
                                        <p class="text-sm text-gray-600">{{ $campo['descricao'] }}</p>
                                    @endif
                                    <div class="flex gap-2 mt-2">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                            {{ ucfirst(str_replace('_', ' ', $campo['tipo'])) }}
                                        </span>
                                        @if ($campo['obrigatorio'])
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Obrigatório</span>
                                        @endif
                                    </div>
                                    @if ($campo['opcoes'] && count($campo['opcoes']) > 0)
                                        <div class="mt-2 text-xs text-gray-600">
                                            <strong>Opções:</strong> {{ implode(', ', $campo['opcoes']) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button @click="editarCampo('{{ $campo['id'] }}', {{ json_encode($campo) }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Editar
                                    </button>
                                    <button @click="removerCampo('{{ $campo['id'] }}')" class="text-red-600 hover:text-red-800 text-sm">
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Nenhum campo adicionado ainda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Formulário Participantes -->
        <div x-data="gerenciadorFormulario('participantes', {{ json_encode($formularioParticipantes) }})">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Formulário de Externos</h2>
                    <button @click="abrirModalAdicionarCampo()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                        + Campo
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse ($formularioParticipantes as $campo)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" x-data="{ editando: false }">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900">{{ $campo['nome'] }}</h3>
                                    @if ($campo['descricao'])
                                        <p class="text-sm text-gray-600">{{ $campo['descricao'] }}</p>
                                    @endif
                                    <div class="flex gap-2 mt-2">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                            {{ ucfirst(str_replace('_', ' ', $campo['tipo'])) }}
                                        </span>
                                        @if ($campo['obrigatorio'])
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Obrigatório</span>
                                        @endif
                                    </div>
                                    @if ($campo['opcoes'] && count($campo['opcoes']) > 0)
                                        <div class="mt-2 text-xs text-gray-600">
                                            <strong>Opções:</strong> {{ implode(', ', $campo['opcoes']) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button @click="editarCampo('{{ $campo['id'] }}', {{ json_encode($campo) }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Editar
                                    </button>
                                    <button @click="removerCampo('{{ $campo['id'] }}')" class="text-red-600 hover:text-red-800 text-sm">
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Nenhum campo adicionado ainda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gerenciadorFormulario(tipoFormulario, formularioAtual) {
        return {
            eventoId: {{ $evento->id }},
            tipoFormulario: tipoFormulario,
            formularioAtual: formularioAtual,
            campoEditando: null,

            abrirModalAdicionarCampo() {
                this.campoEditando = null;
                this.abrirModal();
            },

            editarCampo(campoId, campo) {
                this.campoEditando = campo;
                this.abrirModal();
            },

            abrirModal() {
                const campoEditando = this.campoEditando;
                const tipoFormulario = this.tipoFormulario;
                const eventoId = this.eventoId;

                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
                        <h3 class="text-xl font-bold mb-6">${ campoEditando ? 'Editar' : 'Adicionar' } Campo</h3>
                        <form @submit.prevent="salvarCampo($event, modal, '${campoEditando ? campoEditando.id : ''}')">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nome do Campo *</label>
                                        <input
                                            type="text"
                                            name="nome"
                                            value="${campoEditando ? campoEditando.nome : ''}"
                                            required
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tipo de Input *</label>
                                        <select name="tipo" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Selecione...</option>
                                            <option value="text" ${ campoEditando?.tipo === 'text' ? 'selected' : '' }>Texto</option>
                                            <option value="email" ${ campoEditando?.tipo === 'email' ? 'selected' : '' }>Email</option>
                                            <option value="number" ${ campoEditando?.tipo === 'number' ? 'selected' : '' }>Número</option>
                                            <option value="date" ${ campoEditando?.tipo === 'date' ? 'selected' : '' }>Data</option>
                                            <option value="textarea" ${ campoEditando?.tipo === 'textarea' ? 'selected' : '' }>Área de Texto</option>
                                            <option value="select" ${ campoEditando?.tipo === 'select' ? 'selected' : '' }>Seleção (Dropdown)</option>
                                            <option value="radio" ${ campoEditando?.tipo === 'radio' ? 'selected' : '' }>Radio Button</option>
                                            <option value="checkbox" ${ campoEditando?.tipo === 'checkbox' ? 'selected' : '' }>Checkbox</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Descrição (opcional)</label>
                                    <input
                                        type="text"
                                        name="descricao"
                                        value="${campoEditando ? campoEditando.descricao || '' : ''}"
                                        placeholder="Ex: Sua data de nascimento"
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </div>

                                <div id="opcoesContainer" style="display: none;">
                                    <label class="block text-sm font-medium mb-2">Opções (uma por linha)</label>
                                    <textarea
                                        name="opcoes_texto"
                                        rows="4"
                                        placeholder="Opção 1&#10;Opção 2&#10;Opção 3"
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >${ campoEditando && campoEditando.opcoes ? campoEditando.opcoes.join('\\n') : '' }</textarea>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="obrigatorio"
                                        id="obrigatorio"
                                        ${ campoEditando?.obrigatorio ? 'checked' : '' }
                                        class="w-4 h-4 text-blue-600 rounded"
                                    >
                                    <label for="obrigatorio" class="ml-2 text-sm font-medium cursor-pointer">
                                        Campo obrigatório
                                    </label>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-8">
                                <button type="button" onclick="this.closest('div').parentElement.remove()" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                `;

                // Mostrar/ocultar opções baseado no tipo
                const tipoSelect = modal.querySelector('select[name="tipo"]');
                const opcoesContainer = modal.querySelector('#opcoesContainer');
                const atualizarVisibilidadeOpcoes = () => {
                    const tipo = tipoSelect.value;
                    opcoesContainer.style.display = ['select', 'radio', 'checkbox'].includes(tipo) ? 'block' : 'none';
                };
                tipoSelect.addEventListener('change', atualizarVisibilidadeOpcoes);
                atualizarVisibilidadeOpcoes();

                document.body.appendChild(modal);
            },

            salvarCampo(event, modal, campoId) {
                event.preventDefault();
                const formData = new FormData(event.target);

                const opcoesTxt = formData.get('opcoes_texto') || '';
                const opcoes = opcoesTxt ? opcoesTxt.split('\\n').filter(o => o.trim()) : [];

                const data = {
                    formulario_tipo: this.tipoFormulario,
                    nome: formData.get('nome'),
                    descricao: formData.get('descricao'),
                    tipo: formData.get('tipo'),
                    obrigatorio: formData.get('obrigatorio') ? true : false,
                    opcoes: opcoes
                };

                const url = campoId
                    ? `/api/eventos/${this.eventoId}/formularios/${campoId}`
                    : `/api/eventos/${this.eventoId}/formularios`;

                const metodo = campoId ? 'PUT' : 'POST';

                fetch(url, {
                    method: metodo,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(response => {
                    if (response.success) {
                        location.reload();
                    }
                })
                .catch(e => console.error(e));
            },

            removerCampo(campoId) {
                if (!confirm('Remover este campo?')) return;

                fetch(`/api/eventos/${this.eventoId}/formularios/${campoId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        formulario_tipo: this.tipoFormulario
                    })
                })
                .then(r => r.json())
                .then(response => {
                    if (response.success) {
                        location.reload();
                    }
                })
                .catch(e => console.error(e));
            }
        };
    }
</script>
@endsection
