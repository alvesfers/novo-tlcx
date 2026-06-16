@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Editar Evento</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('eventos.update', $evento) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="tipo_evento_id" class="block text-sm font-semibold mb-2">Tipo de Evento *</label>
            <select name="tipo_evento_id" id="tipo_evento_id" class="w-full border rounded px-3 py-2 @error('tipo_evento_id') border-red-500 @enderror" required>
                <option value="">Selecione um tipo...</option>
                @foreach ($tiposEvento as $tipo)
                <option value="{{ $tipo->id }}" @selected(old('tipo_evento_id', $evento->tipo_evento_id) == $tipo->id)>
                    {{ $tipo->nome }}
                </option>
                @endforeach
            </select>
            @error('tipo_evento_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Entidade Criadora</label>
            <p class="text-gray-700">{{ $evento->entidadeCriadora->nome }}</p>
        </div>

        <div class="mb-4">
            <label for="nome" class="block text-sm font-semibold mb-2">Nome *</label>
            <input type="text" name="nome" id="nome" class="w-full border rounded px-3 py-2 @error('nome') border-red-500 @enderror" value="{{ old('nome', $evento->nome) }}" required>
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="descricao" class="block text-sm font-semibold mb-2">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" class="w-full border rounded px-3 py-2">{{ old('descricao', $evento->descricao) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="data_inicio" class="block text-sm font-semibold mb-2">Data/Hora Início *</label>
                <input type="datetime-local" name="data_inicio" id="data_inicio" class="w-full border rounded px-3 py-2 @error('data_inicio') border-red-500 @enderror" value="{{ old('data_inicio', $evento->data_inicio->format('Y-m-d\TH:i')) }}" required>
                @error('data_inicio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="data_fim" class="block text-sm font-semibold mb-2">Data/Hora Fim</label>
                <input type="datetime-local" name="data_fim" id="data_fim" class="w-full border rounded px-3 py-2 @error('data_fim') border-red-500 @enderror" value="{{ old('data_fim', $evento->data_fim?->format('Y-m-d\TH:i')) }}">
                @error('data_fim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="local" class="block text-sm font-semibold mb-2">Local</label>
            <input type="text" name="local" id="local" class="w-full border rounded px-3 py-2" value="{{ old('local', $evento->local) }}">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="escopo" class="block text-sm font-semibold mb-2">Escopo *</label>
                <select name="escopo" id="escopo" class="w-full border rounded px-3 py-2 @error('escopo') border-red-500 @enderror" required>
                    <option value="coordenadores" @selected(old('escopo', $evento->escopo->value) == 'coordenadores')>Coordenadores</option>
                    <option value="dirigentes" @selected(old('escopo', $evento->escopo->value) == 'dirigentes')>Dirigentes</option>
                    <option value="ambos" @selected(old('escopo', $evento->escopo->value) == 'ambos')>Ambos (Coordenadores e Dirigentes)</option>
                    <option value="externos" @selected(old('escopo', $evento->escopo->value) == 'externos')>Externos</option>
                    <option value="publico" @selected(old('escopo', $evento->escopo->value) == 'publico')>Público</option>
                </select>
                @error('escopo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold mb-2">Status *</label>
                <select name="status" id="status" class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" required>
                    <option value="rascunho" @selected(old('status', $evento->status->value) == 'rascunho')>Rascunho</option>
                    <option value="publicado" @selected(old('status', $evento->status->value) == 'publicado')>Publicado</option>
                    <option value="encerrado" @selected(old('status', $evento->status->value) == 'encerrado')>Encerrado</option>
                    <option value="cancelado" @selected(old('status', $evento->status->value) == 'cancelado')>Cancelado</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="ativo" class="flex items-center">
                <input type="checkbox" name="ativo" id="ativo" value="1" @checked(old('ativo', $evento->ativo)) class="mr-2">
                <span class="text-sm font-semibold">Ativo</span>
            </label>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Atualizar Evento
            </button>
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
