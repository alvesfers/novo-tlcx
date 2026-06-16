@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Adicionar Entidade ao Evento</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('eventos.entidades.store', $evento) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-4">
            <label for="entidade_id" class="block text-sm font-semibold mb-2">Entidade *</label>
            <select name="entidade_id" id="entidade_id" class="w-full border rounded px-3 py-2 @error('entidade_id') border-red-500 @enderror" required>
                <option value="">Selecione uma entidade...</option>
                @foreach ($entidades as $entidade)
                <option value="{{ $entidade->id }}" @selected(old('entidade_id') == $entidade->id)>
                    {{ $entidade->nome }} ({{ $entidade->tipo_entidade->label() }})
                </option>
                @endforeach
            </select>
            @error('entidade_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="tipo_participacao" class="block text-sm font-semibold mb-2">Tipo de Participação *</label>
            <select name="tipo_participacao" id="tipo_participacao" class="w-full border rounded px-3 py-2 @error('tipo_participacao') border-red-500 @enderror" required>
                <option value="">Selecione...</option>
                <option value="participante" @selected(old('tipo_participacao') == 'participante')>Participante</option>
                <option value="apoio" @selected(old('tipo_participacao') == 'apoio')>Apoio</option>
            </select>
            @error('tipo_participacao') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Adicionar Entidade
            </button>
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
