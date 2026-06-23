@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold">Alocação de Quartos</h1>
            <p class="text-gray-600 mt-1">{{ $evento->nome }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar é renderizado automaticamente pelo layout.app -->

        <div class="lg:col-span-3">
            @if (!$evento->id_casa)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <p class="text-yellow-800">
                        ⚠️ <strong>Casa de Retiro não configurada.</strong> Primeiro, configure uma casa de retiro nas <a href="{{ route('eventos.configuracao.show', $evento) }}" class="font-bold underline">configurações do evento</a>.
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($alas as $ala)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-2xl font-bold mb-4">{{ $ala->nome_ala ?? 'Ala ' . $ala->id_ala }}</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($ala->quartos ?? [] as $quarto)
                                    <div class="border border-gray-300 rounded-lg p-4 hover:shadow-md transition">
                                        <h3 class="font-bold text-gray-900 mb-3">
                                            Quarto {{ $quarto->numero_quarto ?? $quarto->id_quarto }}
                                        </h3>

                                        <div class="space-y-2 mb-3">
                                            @php
                                                $alocadosQuarto = $alocacoes[$ala->id_ala][$quarto->id_quarto] ?? [];
                                            @endphp

                                            @if (count($alocadosQuarto) > 0)
                                                @foreach ($alocadosQuarto as $participante_id)
                                                    @php
                                                        $participante = $participantes->find($participante_id);
                                                    @endphp
                                                    @if ($participante)
                                                        <div class="bg-blue-50 border border-blue-200 rounded px-2 py-1 text-sm flex justify-between items-center">
                                                            <span>{{ $participante->dirigente->nome ?? $participante->participanteExterno->nome ?? 'Participante' }}</span>
                                                            <button @click="removerQuarto({{ $ala->id_ala }}, {{ $quarto->id_quarto }}, '{{ $participante_id }}')" class="text-red-500 hover:text-red-700 text-xs font-bold">×</button>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p class="text-gray-400 text-sm">Vazio</p>
                                            @endif
                                        </div>

                                        <button @click="abrirModalQuarto({{ $ala->id_ala }}, {{ $quarto->id_quarto }})" class="w-full text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                            + Adicionar
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if (count($alas) === 0)
                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-6 text-center">
                            <p class="text-gray-600">A casa de retiro não possui alas cadastradas.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function gerenciadorQuartos() {
        return {
            eventoId: {{ $evento->id }},

            abrirModalQuarto(alaId, quartoId) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <h3 class="text-xl font-bold mb-4">Adicionar Participante ao Quarto</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-2">Selecione um participante:</label>
                                <select id="selectParticipante" class="w-full px-3 py-2 border rounded-lg">
                                    <option value="">Escolha um participante...</option>
                                    @foreach ($participantes as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->dirigente->nome ?? $p->participanteExterno->nome ?? 'Participante' }}
                                            ({{ $p->tipo_participante }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-6">
                            <button onclick="this.closest('div').parentElement.remove()" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Cancelar
                            </button>
                            <button onclick="window.gerenciador.adicionarQuarto(${alaId}, ${quartoId}, document.getElementById('selectParticipante').value); this.closest('div').parentElement.remove();" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Adicionar
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            },

            adicionarQuarto(alaId, quartoId, participanteId) {
                if (!participanteId) {
                    alert('Selecione um participante');
                    return;
                }

                fetch(`/api/eventos/${this.eventoId}/quartos/adicionar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        ala_id: alaId,
                        quarto_id: quartoId,
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

            removerQuarto(alaId, quartoId, participanteId) {
                if (!confirm('Remover este participante do quarto?')) return;

                fetch(`/api/eventos/${this.eventoId}/quartos/remover`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        ala_id: alaId,
                        quarto_id: quartoId,
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
            }
        };
    }

    window.gerenciador = gerenciadorQuartos();
</script>
@endsection
