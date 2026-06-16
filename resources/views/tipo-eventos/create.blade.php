@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Criar Tipo de Evento</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('tipo-eventos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-4">
            <label for="nome" class="block text-sm font-semibold mb-2">Nome *</label>
            <input type="text" name="nome" id="nome" class="w-full border rounded px-3 py-2 @error('nome') border-red-500 @enderror" value="{{ old('nome') }}" required>
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="descricao" class="block text-sm font-semibold mb-2">Descrição</label>
            <textarea name="descricao" id="descricao" rows="4" class="w-full border rounded px-3 py-2">{{ old('descricao') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Criar Tipo
            </button>
            <a href="{{ route('tipo-eventos.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
