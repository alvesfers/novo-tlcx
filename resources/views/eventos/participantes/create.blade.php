@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Adicionar Participante</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('eventos.participantes.store', $evento) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-4">
            <label for="tipo_participante" class="block text-sm font-semibold mb-2">Tipo de Participante *</label>
            <select name="tipo_participante" id="tipo_participante" class="w-full border rounded px-3 py-2" required onchange="toggleParticipanteType()">
                <option value="">Selecione...</option>
                <option value="dirigente" @selected(old('tipo_participante') == 'dirigente')>Dirigente</option>
                <option value="externo" @selected(old('tipo_participante') == 'externo')>Externo</option>
            </select>
            @error('tipo_participante') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div id="dirigente-select" class="mb-4" style="display: none;">
            <label for="dirigente_id" class="block text-sm font-semibold mb-2">Dirigente *</label>
            <select name="dirigente_id" id="dirigente_id" class="w-full border rounded px-3 py-2">
                <option value="">Selecione um dirigente...</option>
                @foreach ($dirigentes as $dirigente)
                <option value="{{ $dirigente->id }}" @selected(old('dirigente_id') == $dirigente->id)>
                    {{ $dirigente->nome }}
                </option>
                @endforeach
            </select>
            @error('dirigente_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div id="externo-select" class="mb-4" style="display: none;">
            <label for="participante_externo_id" class="block text-sm font-semibold mb-2">Participante Externo *</label>
            <select name="participante_externo_id" id="participante_externo_id" class="w-full border rounded px-3 py-2">
                <option value="">Selecione um participante...</option>
                @foreach ($externos as $externo)
                <option value="{{ $externo->id }}" @selected(old('participante_externo_id') == $externo->id)>
                    {{ $externo->nome }}
                </option>
                @endforeach
            </select>
            @error('participante_externo_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="observacao" class="block text-sm font-semibold mb-2">Observação</label>
            <textarea name="observacao" id="observacao" rows="3" class="w-full border rounded px-3 py-2">{{ old('observacao') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Adicionar Participante
            </button>
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function toggleParticipanteType() {
    const tipo = document.getElementById('tipo_participante').value;
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

document.addEventListener('DOMContentLoaded', toggleParticipanteType);
</script>
@endsection
