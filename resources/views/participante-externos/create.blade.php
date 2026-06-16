@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Criar Participante Externo</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('participante-externos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-4">
            <label for="nome" class="block text-sm font-semibold mb-2">Nome *</label>
            <input type="text" name="nome" id="nome" class="w-full border rounded px-3 py-2 @error('nome') border-red-500 @enderror" value="{{ old('nome') }}" required>
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="telefone" class="block text-sm font-semibold mb-2">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="w-full border rounded px-3 py-2" value="{{ old('telefone') }}">
        </div>

        <div class="mb-4">
            <label for="documento" class="block text-sm font-semibold mb-2">Documento (CPF/RG)</label>
            <input type="text" name="documento" id="documento" class="w-full border rounded px-3 py-2" value="{{ old('documento') }}">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="genero" class="block text-sm font-semibold mb-2">Gênero</label>
                <select name="genero" id="genero" class="w-full border rounded px-3 py-2">
                    <option value="">Selecione...</option>
                    <option value="m" @selected(old('genero') == 'm')>Masculino</option>
                    <option value="f" @selected(old('genero') == 'f')>Feminino</option>
                    <option value="outro" @selected(old('genero') == 'outro')>Outro</option>
                </select>
            </div>

            <div>
                <label for="data_nascimento" class="block text-sm font-semibold mb-2">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="w-full border rounded px-3 py-2" value="{{ old('data_nascimento') }}">
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Criar Participante
            </button>
            <a href="{{ route('participante-externos.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
