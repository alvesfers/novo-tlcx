@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Nova Categoria Financeira</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-md">
        <form method="POST" action="{{ route('financeiro-categorias.store') }}">
            @csrf

            <div class="mb-4">
                <label for="nome" class="block text-sm font-semibold mb-2">Nome *</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="w-full border rounded-lg px-3 py-2 @error('nome') border-red-500 @enderror" required>
                @error('nome')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tipo" class="block text-sm font-semibold mb-2">Tipo *</label>
                <select id="tipo" name="tipo" class="w-full border rounded-lg px-3 py-2 @error('tipo') border-red-500 @enderror" required>
                    <option value="">Selecione...</option>
                    <option value="entrada" @selected(old('tipo') === 'entrada')>Entrada (Receita)</option>
                    <option value="saida" @selected(old('tipo') === 'saida')>Saída (Despesa)</option>
                </select>
                @error('tipo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="ativo" class="flex items-center">
                    <input type="checkbox" id="ativo" name="ativo" value="1" @checked(old('ativo', true)) class="mr-2">
                    <span class="text-sm font-semibold">Ativa</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Criar
                </button>
                <a href="{{ route('financeiro-categorias.index') }}" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
