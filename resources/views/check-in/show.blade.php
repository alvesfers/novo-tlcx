@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Check-in: {{ $evento->nome }}</h1>
        <p class="text-gray-600 mt-2">{{ $evento->data_inicio->format('d/m/Y H:i') }} - {{ $evento->data_fim->format('d/m/Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Formulário de Check-in -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Processar Check-in</h2>
            <form id="checkinForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">UUID do Dirigente (ou escanear QR)</label>
                    <input
                        type="text"
                        id="dirigente_uuid"
                        name="dirigente_uuid"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md text-lg"
                        placeholder="Cole o UUID ou escanear QR code"
                        autofocus
                    >
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Registrar Presença
                </button>
            </form>

            <div id="status" class="mt-4 p-4 rounded hidden">
                <p id="statusText"></p>
            </div>
        </div>

        <!-- Resumo de Presença -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumo</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-600">Inscritos</dt>
                    <dd class="text-2xl font-bold text-blue-600">{{ $evento->participantes->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Presentes</dt>
                    <dd class="text-2xl font-bold text-green-600">{{ $evento->participantes->where('presenca', 'confirmado')->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Taxa de Presença</dt>
                    <dd class="text-2xl font-bold text-purple-600">
                        {{ $evento->participantes->count() > 0 ? number_format(($evento->participantes->where('presenca', 'confirmado')->count() / $evento->participantes->count()) * 100, 1) : 0 }}%
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Pendentes</dt>
                    <dd class="text-2xl font-bold text-orange-600">{{ $evento->participantes->where('presenca', 'pendente')->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Lista de Participantes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Participantes</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Presença</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Check-in</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evento->participantes as $participante)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participante->dirigente->nome ?? 'Externo' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs font-medium
                            @if($participante->presenca === 'confirmado') bg-green-500
                            @elseif($participante->presenca === 'pendente') bg-orange-500
                            @else bg-red-500
                            @endif">
                            {{ ucfirst($participante->presenca) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $participante->checkin_em ? $participante->checkin_em->format('d/m/Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Nenhum participante inscrito</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('checkinForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const uuid = document.getElementById('dirigente_uuid').value.trim();
    const statusDiv = document.getElementById('status');
    const statusText = document.getElementById('statusText');

    if (!uuid) {
        statusDiv.classList.remove('hidden', 'bg-green-100', 'text-green-800');
        statusDiv.classList.add('bg-red-100', 'text-red-800');
        statusText.textContent = 'Por favor, insira o UUID do dirigente';
        return;
    }

    try {
        const response = await fetch('{{ route("check-in.processar", $evento) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ dirigente_uuid: uuid })
        });

        const data = await response.json();

        statusDiv.classList.remove('hidden', 'bg-red-100', 'text-red-800');

        if (response.ok) {
            statusDiv.classList.add('bg-green-100', 'text-green-800');
            statusText.textContent = data.message;
            document.getElementById('dirigente_uuid').value = '';

            // Reload page after 2 seconds
            setTimeout(() => location.reload(), 2000);
        } else {
            statusDiv.classList.add('bg-red-100', 'text-red-800');
            statusText.textContent = data.message || 'Erro ao processar check-in';
        }
    } catch (error) {
        statusDiv.classList.remove('hidden', 'bg-green-100', 'text-green-800');
        statusDiv.classList.add('bg-red-100', 'text-red-800');
        statusText.textContent = 'Erro ao processar check-in: ' + error.message;
    }
});
</script>
@endsection
