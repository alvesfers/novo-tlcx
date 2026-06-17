@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Editar Núcleo</h1>

    <form action="{{ route('nucleos.update', $nucleo) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-2">Diocese</label>
            <select name="entidade_pai_id" class="w-full px-4 py-2 border rounded-lg @error('entidade_pai_id') border-red-500 @enderror" required>
                <option value="">Selecione uma diocese...</option>
                @foreach ($dioceses as $diocese)
                    <option value="{{ $diocese->id }}" @selected(old('entidade_pai_id', $nucleo->entidade_pai_id) == $diocese->id)>{{ $diocese->nome }}</option>
                @endforeach
            </select>
            @error('entidade_pai_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome</label>
            <input type="text" name="nome" value="{{ old('nome', $nucleo->nome) }}" class="w-full px-4 py-2 border rounded-lg @error('nome') border-red-500 @enderror" required>
            @error('nome')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $nucleo->email) }}" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror">
            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Ativo</label>
            <label class="flex items-center">
                <input type="checkbox" name="ativo" value="1" @if(old('ativo', $nucleo->ativo)) checked @endif class="rounded">
                <span class="ml-2">Núcleo ativo no sistema</span>
            </label>
            @error('ativo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Atualizar
            </button>
            <a href="{{ route('nucleos.show', $nucleo) }}" class="ml-2 px-6 py-2 text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
