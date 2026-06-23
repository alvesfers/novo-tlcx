@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Grupos de Participantes</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="abrirModalCriarGrupo()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Criar Grupo
            </button>
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar é renderizado automaticamente pelo layout.app -->

        <div class="lg:col-span-3">
            <div class="space-y-6">
                @forelse ($grupos as $grupo)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold">{{ $grupo['nome'] ?? 'Grupo sem nome' }}</h2>
                                @if ($grupo['descricao'] ?? false)
                                    <p class="text-gray-600 mt-1">{{ $grupo['descricao'] }}</p>
                                @endif
                                @if ($grupo['dirigente_id'] ?? false)
                                    @php
                                        $dirigente = $dirigentes->find($grupo['dirigente_id']);
                                    @endphp
                                    @if ($dirigente)
                                        <p class="text-sm text-gray-500 mt-2">
                                            <strong>Responsável:</strong> {{ $dirigente->dirigente->nome ?? 'Desconhecido' }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                            <button @click="deletarGrupo('{{ $grupo['id'] }}')" class="text-red-600 hover:text-red-800 px-3 py-1 text-sm hover:bg-red-50 rounded">
                                Deletar
                            </button>
                        </div>

                        <!-- Participantes no Grupo -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h3 class="font-bold text-gray-900 mb-3">Participantes ({{ count($grupo['participantes'] ?? []) }})</h3>

                            @if (count($grupo['participantes'] ?? []) > 0)
                                <div class="space-y-2">
                                    @foreach ($grupo['participantes'] ?? [] as $participante_id)
                                        @php
                                            $participante = $externos->find($participante_id);
                                        @endphp
                                        @if ($participante)
                                            <div class="bg-blue-50 border border-blue-200 rounded px-3 py-2 flex justify-between items-center">
                                                <span class="text-sm">{{ $participante->participanteExterno->nome ?? 'Participante' }}</span>
                                                <button @click="removerDoGrupo('{{ $grupo['id'] }}', '{{ $participante_id }}')" class="text-red-500 hover:text-red-700 text-sm font-bold">
                                                    Remover
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 text-sm">Nenhum participante no grupo</p>
                            @endif

                            <button @click="abrirModalAdicionarParticipante('{{ $grupo['id'] }}')" class="mt-3 w-full text-sm bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">
                                + Adicionar Participante
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 border border-gray-300 rounded-lg p-8 text-center">
                        <p class="text-gray-600 text-lg">Nenhum grupo criado ainda</p>
                        <p class="text-gray-400 text-sm mt-1">Clique em "Criar Grupo" para começar</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function gerenciadorGrupos() {
        return {
            eventoId: {{ $evento->id }},

            abrirModalCriarGrupo() {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md max-h-screen overflow-y-auto">
                        <h3 class="text-xl font-bold mb-4">Criar Novo Grupo</h3>
                        <form @submit.prevent="criarGrupo($event, modal)">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nome do Grupo *</label>
                                    <input type="text" name="nome" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Responsável (Dirigente)</label>
                                    <select name="dirigente_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Nenhum</option>
                                        @foreach ($dirigentes as $d)
                                            <option value="{{ $d->id }}">{{ $d->dirigente->nome ?? 'Dirigente' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Descrição</label>
                                    <textarea name="descricao" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-6">
                                <button type="button" onclick="this.closest('div').parentElement.remove()" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Criar
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                document.body.appendChild(modal);
            },

            criarGrupo(event, modal) {
                event.preventDefault();
                const formData = new FormData(event.target);
                const data = Object.fromEntries(formData);

                fetch(`/api/eventos/${this.eventoId}/grupos`, {
                    method: 'POST',
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

            abrirModalAdicionarParticipante(grupoId) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <h3 class="text-xl font-bold mb-4">Adicionar Participante</h3>
                        <select id="selectParticipante" class="w-full px-3 py-2 border rounded-lg mb-4">
                            <option value="">Escolha um participante...</option>
                            @foreach ($externos as $p)
                                <option value="{{ $p->id }}">{{ $p->participanteExterno->nome ?? 'Participante' }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2">
                            <button onclick="this.closest('div').parentElement.remove()" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Cancelar
                            </button>
                            <button onclick="window.gruposGerenciador.adicionarParticipante('${grupoId}', document.getElementById('selectParticipante').value); this.closest('div').parentElement.remove();" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Adicionar
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            },

            adicionarParticipante(grupoId, participanteId) {
                if (!participanteId) {
                    alert('Selecione um participante');
                    return;
                }

                fetch(`/api/eventos/${this.eventoId}/grupos/participante`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        grupo_id: grupoId,
                        participante_id: participanteId
                    })
                })
                .then(r => r.json())
                .then(response => {
                    if (response.success) {
                        location.reload();
                    }
                })
                .catch(e => console.error(e));
            },

            removerDoGrupo(grupoId, participanteId) {
                if (!confirm('Remover este participante do grupo?')) return;

                fetch(`/api/eventos/${this.eventoId}/grupos/participante`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        grupo_id: grupoId,
                        participante_id: participanteId
                    })
                })
                .then(r => r.json())
                .then(response => {
                    if (response.success) {
                        location.reload();
                    }
                })
                .catch(e => console.error(e));
            },

            deletarGrupo(grupoId) {
                if (!confirm('Deletar este grupo?')) return;

                fetch(`/api/eventos/${this.eventoId}/grupos/${grupoId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
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

    window.gruposGerenciador = gerenciadorGrupos();
</script>
@endsection
