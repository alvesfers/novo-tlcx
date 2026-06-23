@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Cronograma de Atividades</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>
        <div class="flex gap-2">
            <button @click="openAdicionarModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Adicionar Atividade
            </button>
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar é renderizado automaticamente pelo layout.app -->

        <div class="lg:col-span-3">
            <div x-data="cronogramaManager()" class="bg-white rounded-lg shadow p-6">
                <div id="cronograma-container" class="space-y-4">
                    @forelse ($evento->getCronogramaOrdenado() as $atividade)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                                            Dia {{ $atividade['dia'] }}
                                        </span>
                                        <span class="text-gray-900 font-semibold text-lg">{{ $atividade['horario'] }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $atividade['titulo'] }}</h3>
                                    @if($atividade['descricao'])
                                        <p class="text-gray-600 mb-3">{{ $atividade['descricao'] }}</p>
                                    @endif
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                        @if($atividade['local'])
                                            <span><strong>📍 Local:</strong> {{ $atividade['local'] }}</span>
                                        @endif
                                        @if($atividade['responsavel'])
                                            <span><strong>👤 Responsável:</strong> {{ $atividade['responsavel'] }}</span>
                                        @endif
                                        @if($atividade['duracao_minutos'])
                                            <span><strong>⏱️ Duração:</strong> {{ $atividade['duracao_minutos'] }}min</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <button @click="editarAtividade('{{ $atividade['id'] }}')" class="text-blue-600 hover:text-blue-800 text-sm px-3 py-1 hover:bg-blue-50 rounded">
                                        Editar
                                    </button>
                                    <button @click="removerAtividade('{{ $atividade['id'] }}')" class="text-red-600 hover:text-red-800 text-sm px-3 py-1 hover:bg-red-50 rounded">
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">Nenhuma atividade no cronograma</p>
                            <p class="text-gray-400 text-sm mt-2">Clique em "Adicionar Atividade" para começar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cronogramaManager() {
        return {
            eventoId: {{ $evento->id }},

            openAdicionarModal() {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md max-h-screen overflow-y-auto">
                        <h3 class="text-xl font-bold mb-4">Adicionar Atividade</h3>
                        <form @submit.prevent="adicionarAtividade($event, modal)">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Dia *</label>
                                    <input type="number" name="dia" min="1" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Horário (HH:mm) *</label>
                                    <input type="time" name="horario" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Título *</label>
                                    <input type="text" name="titulo" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Descrição</label>
                                    <textarea name="descricao" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Local</label>
                                    <input type="text" name="local" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Responsável</label>
                                    <input type="text" name="responsavel" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Duração (minutos)</label>
                                    <input type="number" name="duracao_minutos" min="1" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="flex gap-2 mt-6">
                                <button type="button" @click="modal.remove()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Adicionar
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                document.body.appendChild(modal);
            },

            adicionarAtividade(event, modal) {
                event.preventDefault();
                const formData = new FormData(event.target);
                const data = Object.fromEntries(formData);

                fetch(`/api/eventos/${this.eventoId}/cronograma`, {
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
                        modal.remove();
                        location.reload();
                    }
                })
                .catch(e => console.error(e));
            },

            editarAtividade(id) {
                alert('Editar: ' + id + ' (implementar)');
            },

            removerAtividade(id) {
                if (confirm('Tem certeza que deseja remover esta atividade?')) {
                    fetch(`/api/eventos/${this.eventoId}/cronograma/${id}`, {
                        method: 'DELETE',
                        headers: {
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
            }
        };
    }
</script>
@endsection
