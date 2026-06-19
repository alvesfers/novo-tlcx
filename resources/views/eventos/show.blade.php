@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
        <div>
            <a href="{{ route('eventos.edit', $evento) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mr-2">
                Editar
            </a>
            <a href="{{ route('eventos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Informações do Evento -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Tipo</p>
                <p class="text-lg">{{ $evento->tipoEvento->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Entidade Criadora</p>
                <p class="text-lg">{{ $evento->entidadeCriadora->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Data Início</p>
                <p class="text-lg">{{ $evento->data_inicio->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Data Fim</p>
                <p class="text-lg">{{ $evento->data_fim ? $evento->data_fim->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Status</p>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-sm font-semibold
                        @if ($evento->isPublicado()) bg-blue-100 text-blue-800
                        @elseif ($evento->isRascunho()) bg-yellow-100 text-yellow-800
                        @elseif ($evento->isEncerrado()) bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">
                        {{ $evento->status->label() }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Escopo</p>
                <p class="text-lg">{{ $evento->escopo->label() }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Local</p>
                <p class="text-lg">{{ $evento->local ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Ativo</p>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-sm font-semibold @if($evento->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ $evento->ativo ? 'Sim' : 'Não' }}
                    </span>
                </p>
            </div>
        </div>
        @if ($evento->descricao)
        <div class="mt-4 pt-4 border-t">
            <p class="text-gray-600 text-sm font-semibold">Descrição</p>
            <p class="text-lg">{{ $evento->descricao }}</p>
        </div>
        @endif
    </div>

    <!-- Entidades Envolvidas -->
    <div x-data="entidadesManager()" class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Entidades Envolvidas</h2>
            <button onclick="openModal('entidadeModal', false, { evento_id: {{ $evento->id }} })" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
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

    <!-- Participantes Dirigentes -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Participantes Dirigentes</h2>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('eventos.participantes.todos-escopo', $evento) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm" title="Adiciona todos os dirigentes do escopo: {{ $evento->escopo->label() }}">
                        + Todos do Escopo
                    </button>
                </form>
                <button type="button" onclick="openModal('participanteModal', false, { evento_id: {{ $evento->id }}, tipo_participante: 'dirigente' })" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                    + Adicionar Dirigente
                </button>
            </div>
        </div>

        <div x-data="dirigentesBulkManager()">
            <!-- Pesquisa e Ações em Massa -->
            <div class="mb-4 flex justify-between items-center gap-4">
                <input
                    type="text"
                    x-model="search"
                    @input="filterDirigentes()"
                    placeholder="Pesquisar por nome..."
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />

                <!-- Botões de Ação em Massa -->
                <div class="flex gap-2" x-show="selectedCount > 0">
                    <button
                        @click="marcarPresencaSelecionados()"
                        class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700"
                    >
                        ✓ Marcar Presença (<span x-text="selectedCount"></span>)
                    </button>
                    <button
                        @click="removerSelecionados()"
                        class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700"
                    >
                        🗑 Remover (<span x-text="selectedCount"></span>)
                    </button>
                </div>
            </div>

            <table class="w-full border-collapse">
                <thead class="bg-gray-100 border-b-2 border-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nome</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Função</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Presença</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Check-in</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Observação</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="p in filteredDirigentes" :key="p.id">
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="px-4 py-3">
                                <input type="checkbox" :value="p.id" x-model="selected" @change="updateSelectedCount()" class="rounded">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900" x-text="p.dirigente.nome"></td>
                            <td class="px-4 py-3">
                                <span
                                    x-show="p.funcao_dirigente"
                                    class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800"
                                    x-text="p.funcao_dirigente ? p.funcao_dirigente.nome : '-'"
                                ></span>
                                <span x-show="!p.funcao_dirigente" class="text-gray-500">-</span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    :class="p.presenca ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                    class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
                                    x-text="p.presenca ? '✓ Presente' : '✗ Ausente'"
                                ></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="p.checkin_em ? new Date(p.checkin_em).toLocaleString('pt-BR') : '-'"></td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="p.observacao || '-'"></td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">
                                    <template x-if="!p.presenca">
                                        <button
                                            @click="marcarPresencaUnico(p.id)"
                                            title="Marcar presença"
                                            class="text-green-600 hover:text-green-800 hover:bg-green-100 p-2 rounded transition"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </template>
                                    <button
                                        @click="removerUnico(p.id)"
                                        title="Remover participante"
                                        class="text-red-600 hover:text-red-800 hover:bg-red-100 p-2 rounded transition"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredDirigentes.length === 0">
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <p class="text-lg">Nenhum dirigente adicionado</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    <!-- Tipos de Camiseta -->
    @if ($evento->tiposCamiseta->count())
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Tipos de Camiseta Disponíveis</h2>

        <div x-data="{ activeType: null }" class="space-y-3">
            @foreach ($evento->tiposCamiseta as $tipo)
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button
                    @click="activeType = activeType === {{ $tipo->id }} ? null : {{ $tipo->id }}"
                    class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
                >
                    <div class="text-left">
                        <p class="font-semibold text-gray-900">{{ $tipo->fornecedor->nome }}</p>
                        <p class="text-sm text-gray-600">Fornecedor de Camisetas</p>
                    </div>
                    <svg :class="activeType === {{ $tipo->id }} ? 'rotate-180' : ''" class="w-5 h-5 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </button>

                <div x-show="activeType === {{ $tipo->id }}" class="px-4 py-3 bg-white border-t border-gray-200">
                    <div class="space-y-4">
                        @forelse ($tipo->fornecedor->tipos as $tipoF)
                            @if ($tipoF->ativo)
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="font-semibold text-gray-900 mb-2">{{ $tipoF->tipo_camiseta }}</p>

                                @if ($tipoF->tamanhos->count())
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($tipoF->tamanhos as $tamanho)
                                    <div class="text-sm bg-white border border-gray-200 rounded p-2">
                                        <p class="font-medium text-gray-900">{{ $tamanho->tamanho }}</p>
                                        @if($tamanho->medidas)
                                        <div class="text-xs text-gray-600 mt-1 space-y-0.5">
                                            @foreach (is_array($tamanho->medidas) ? $tamanho->medidas : json_decode($tamanho->medidas, true) as $medida => $valor)
                                                <p>{{ $medida }}: {{ $valor }}</p>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Nenhum tamanho cadastrado</p>
                                @endif
                            </div>
                            @endif
                        @empty
                            <p class="text-sm text-gray-500">Nenhum tipo disponível</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabela de Preços -->
    @if ($evento->valores->count())
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Tabela de Preços</h2>

        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Tipo</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Valor</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evento->valores as $valor)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $valor->tipo_valor }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 font-semibold">R$ {{ number_format($valor->valor, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $valor->descricao ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Barzinhos do Evento -->
    @if ($evento->barzinhos && $evento->barzinhos->count())
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Barzinhos do Evento</h2>
        </div>

        <div class="space-y-3">
            @foreach ($evento->barzinhos as $barzinho)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $barzinho->nome }}</p>
                        @if($barzinho->descricao)
                            <p class="text-sm text-gray-600 mt-1">{{ $barzinho->descricao }}</p>
                        @endif
                        <div class="flex gap-4 mt-2 text-sm text-gray-600">
                            <span>Produtos: {{ $barzinho->produtos->count() }}</span>
                            <span>Vendas: {{ $barzinho->vendas->count() }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($barzinho->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $barzinho->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Participantes Externos -->
    <div x-data="participantesManager()" class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Participantes Externos</h2>
            <button onclick="openModal('participanteModal', false, { evento_id: {{ $evento->id }}, tipo_participante: 'externo' })" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                Adicionar Externo
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

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Participação *</label>
            <select
                name="tipo_participacao"
                id="entidadeModaltipo_participacao"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >
                <option value="">Selecione...</option>
                <option value="participante">Participante</option>
                <option value="apoio">Apoio</option>
            </select>
            <span class="text-red-500 text-sm" id="entidadeModaltipo_participacaoError"></span>
        </div>
    </x-modal-form>

    <!-- Modal for Participantes -->
    <x-modal-form
        id="participanteModal"
        title="Adicionar Participante"
        :resource="'eventos.participantes'"
        size="md"
        :nested="true"
        :nestedPath="'evento'"
    >
        <input type="hidden" name="evento_id" id="participanteModalevento_id">

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo de Participante *</label>
            <select
                name="tipo_participante"
                id="participanteModaltipo_participante"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
                @change="toggleParticipanteType()"
            >
                <option value="">Selecione...</option>
                <option value="dirigente">Dirigente</option>
                <option value="externo">Externo</option>
            </select>
            <span class="text-red-500 text-sm" id="participanteModaltipo_participanteError"></span>
        </div>

        <div id="dirigente-select" style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Dirigente *</label>
            <select
                name="dirigente_id"
                id="participanteModaldirigente_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">Selecione um dirigente...</option>
            </select>
            <span class="text-red-500 text-sm" id="participanteModaldirigente_idError"></span>
        </div>

        <div id="externo-select" style="display: none;">
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Participante Externo *</label>
            <select
                name="participante_externo_id"
                id="participanteModalparticipante_externo_id"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">Selecione um participante...</option>
            </select>
            <span class="text-red-500 text-sm" id="participanteModalparticipante_externo_idError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Observação</label>
            <textarea
                name="observacao"
                id="participanteModalobservacao"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            ></textarea>
            <span class="text-red-500 text-sm" id="participanteModalobservacaoError"></span>
        </div>
    </x-modal-form>

    <script>
        function entidadesManager() {
            let entidadesData = [];

            async function loadEntidades() {
                try {
                    const response = await fetch('{{ route("eventos.entidades.create", $evento) }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    entidadesData = data.entidades || [];
                    populateEntidadesSelect();
                } catch (error) {
                    console.error('Error loading entidades:', error);
                }
            }

            function populateEntidadesSelect() {
                const select = document.getElementById('entidadeModalentidade_id');
                select.innerHTML = '<option value="">Selecione uma entidade...</option>';
                entidadesData.forEach(entidade => {
                    const option = document.createElement('option');
                    option.value = entidade.id;
                    option.textContent = entidade.nome;
                    select.appendChild(option);
                });
            }

            loadEntidades();

            return {
                deleteItem(event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja remover esta entidade?',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Remover',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.closest('form').submit();
                        }
                    });
                }
            };
        }

        function participantesManager() {
            let dirigentesData = [];
            let externosData = [];

            async function loadParticipantes() {
                try {
                    const response = await fetch('{{ route("eventos.participantes.create", $evento) }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    dirigentesData = data.dirigentes || [];
                    externosData = data.externos || [];
                    populateParticipantesSelects();
                } catch (error) {
                    console.error('Error loading participantes:', error);
                }
            }

            function populateParticipantesSelects() {
                const dirigenteSelect = document.getElementById('participanteModaldirigente_id');
                dirigenteSelect.innerHTML = '<option value="">Selecione um dirigente...</option>';
                dirigentesData.forEach(dirigente => {
                    const option = document.createElement('option');
                    option.value = dirigente.id;
                    option.textContent = dirigente.nome;
                    dirigenteSelect.appendChild(option);
                });

                const externoSelect = document.getElementById('participanteModalparticipante_externo_id');
                externoSelect.innerHTML = '<option value="">Selecione um participante...</option>';
                externosData.forEach(externo => {
                    const option = document.createElement('option');
                    option.value = externo.id;
                    option.textContent = externo.nome;
                    externoSelect.appendChild(option);
                });
            }

            loadParticipantes();

            return {
                deleteItem(event) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmar exclusão',
                        text: 'Tem certeza que deseja remover este participante?',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Remover',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.closest('form').submit();
                        }
                    });
                }
            };
        }

        function toggleParticipanteType() {
            const tipo = document.getElementById('participanteModaltipo_participante').value;
            const dirigenteSelect = document.getElementById('dirigente-select');
            const externoSelect = document.getElementById('externo-select');

            if (tipo === 'dirigente') {
                dirigenteSelect.style.display = 'block';
                externoSelect.style.display = 'none';
            } else if (tipo === 'externo') {
                dirigenteSelect.style.display = 'none';
                externoSelect.style.display = 'block';
            } else {
                dirigenteSelect.style.display = 'none';
                externoSelect.style.display = 'none';
            }
        }

        function dirigentesBulkManager() {
            const eventoId = {{ $evento->id }};
            const dirigentes = @json($evento->participantes
                ->where('tipo_participante', 'dirigente')
                ->map(function($p) {
                    return array_merge($p->toArray(), [
                        'funcao_dirigente' => $p->funcaoDirigente ? $p->funcaoDirigente->toArray() : null
                    ]);
                })
                ->values());

            return {
                search: '',
                selected: [],
                selectAll: false,
                selectedCount: 0,
                filteredDirigentes: dirigentes,

                filterDirigentes() {
                    const searchLower = this.search.toLowerCase();
                    this.filteredDirigentes = dirigentes.filter(d =>
                        d.dirigente.nome.toLowerCase().includes(searchLower)
                    );
                    this.selectAll = false;
                    this.selected = [];
                    this.selectedCount = 0;
                },

                toggleAll() {
                    if (this.selectAll) {
                        this.selected = this.filteredDirigentes.map(d => d.id.toString());
                    } else {
                        this.selected = [];
                    }
                    this.updateSelectedCount();
                },

                updateSelectedCount() {
                    this.selectedCount = this.selected.length;
                },

                marcarPresencaSelecionados() {
                    if (this.selected.length === 0) return;

                    Swal.fire({
                        title: 'Marcar presença?',
                        text: `Marcar ${this.selected.length} dirigente(s) como presente?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route("eventos.participantes.marcar-presenca-lote", $evento) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ ids: this.selected })
                            })
                            .then(r => r.json())
                            .then(data => {
                                Swal.fire('Sucesso!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            })
                            .catch(() => Swal.fire('Erro', 'Erro ao marcar presença', 'error'));
                        }
                    });
                },

                removerSelecionados() {
                    if (this.selected.length === 0) return;

                    Swal.fire({
                        title: 'Remover?',
                        text: `Remover ${this.selected.length} dirigente(s)?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Remover',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route("eventos.participantes.remover-lote", $evento) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ ids: this.selected })
                            })
                            .then(r => r.json())
                            .then(data => {
                                Swal.fire('Sucesso!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            })
                            .catch(() => Swal.fire('Erro', 'Erro ao remover', 'error'));
                        }
                    });
                },

                marcarPresencaUnico(id) {
                    Swal.fire({
                        title: 'Marcar presença?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route("eventos.participantes.marcar-presenca-lote", $evento) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ ids: [id] })
                            })
                            .then(r => r.json())
                            .then(() => location.reload())
                            .catch(() => Swal.fire('Erro', 'Erro ao marcar presença', 'error'));
                        }
                    });
                },

                removerUnico(id) {
                    Swal.fire({
                        title: 'Remover?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Remover'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route("eventos.participantes.remover-lote", $evento) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ ids: [id] })
                            })
                            .then(r => r.json())
                            .then(() => location.reload())
                            .catch(() => Swal.fire('Erro', 'Erro ao remover', 'error'));
                        }
                    });
                }
            };
        }
    </script>
</div>
@endsection
