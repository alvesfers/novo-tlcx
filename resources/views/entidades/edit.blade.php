@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Editar Entidade</h1>

    <form action="{{ route('entidades.update', $entidade) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Tipo</label>
            <p class="px-4 py-2 bg-gray-100 rounded-lg">
                <strong>{{ $entidade->tipo_entidade->label() }}</strong>
                <small class="text-gray-600">(não pode ser alterado)</small>
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Hierarquia</label>
            <p class="px-4 py-2 bg-gray-100 rounded-lg">{{ $entidade->getHierarquiaCompleta() }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome</label>
            <input type="text" name="nome" value="{{ $entidade->nome }}" class="w-full px-4 py-2 border rounded-lg @error('nome') border-red-500 @enderror" required>
            @error('nome')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ $entidade->email }}" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror">
            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        @if ($entidade->isSecretaria())
        <div>
            <label class="block text-sm font-medium mb-2">Tipo de Secretaria</label>
            <select name="tipo_secretaria" class="w-full px-4 py-2 border rounded-lg @error('tipo_secretaria') border-red-500 @enderror">
                <option value="">Selecione...</option>
                <option value="aberta" @if($entidade->tipo_secretaria === 'aberta') selected @endif>Aberta</option>
                <option value="fechada" @if($entidade->tipo_secretaria === 'fechada') selected @endif>Fechada</option>
            </select>
            @error('tipo_secretaria')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium mb-2">Ativo</label>
            <label class="flex items-center">
                <input type="checkbox" name="ativo" value="1" @if($entidade->ativo) checked @endif class="rounded">
                <span class="ml-2">Entidade ativa no sistema</span>
            </label>
            @error('ativo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Atualizar
            </button>
            <a href="{{ route('entidades.show', $entidade) }}" class="ml-2 px-6 py-2 text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
