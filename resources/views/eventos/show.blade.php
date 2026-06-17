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
    <div x-data="participantesManager()" class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Participantes Dirigentes</h2>
            <button onclick="openModal('participanteModal', false, { evento_id: {{ $evento->id }}, tipo_participante: 'dirigente' })" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                Adicionar Dirigente
            </button>
        </div>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Nome</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Presença</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Check-in</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Observação</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evento->participantes->where('tipo_participante', 'dirigente') as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $p->dirigente->nome }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm font-semibold @if($p->presenca) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $p->presenca ? 'Presente' : 'Ausente' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $p->checkin_em ? $p->checkin_em->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-4 py-2 text-sm">{{ $p->observacao ?: '-' }}</td>
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
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                        Nenhum dirigente adicionado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
    </script>
</div>
@endsection
