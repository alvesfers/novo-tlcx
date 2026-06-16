@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Criar Nova Entidade</h1>

    <form action="{{ route('entidades.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">Tipo de Entidade</label>
            <select name="tipo_entidade" class="w-full px-4 py-2 border rounded-lg @error('tipo_entidade') border-red-500 @enderror" required>
                <option value="">Selecione...</option>
                <option value="diocese">Diocese</option>
                <option value="nucleo">Núcleo</option>
                <option value="secretaria">Secretaria</option>
            </select>
            @error('tipo_entidade')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome</label>
            <input type="text" name="nome" class="w-full px-4 py-2 border rounded-lg @error('nome') border-red-500 @enderror" required>
            @error('nome')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror">
            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tipo de Secretaria (apenas para Secretarias)</label>
            <select name="tipo_secretaria" class="w-full px-4 py-2 border rounded-lg @error('tipo_secretaria') border-red-500 @enderror">
                <option value="">Não aplicável</option>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </select>
            @error('tipo_secretaria')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Criar Entidade
            </button>
            <a href="{{ route('entidades.index') }}" class="ml-2 px-6 py-2 text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
