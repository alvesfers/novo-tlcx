@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Criar Novo Núcleo</h1>

    <form action="{{ route('nucleos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">Diocese</label>
            <select name="entidade_pai_id" class="w-full px-4 py-2 border rounded-lg @error('entidade_pai_id') border-red-500 @enderror" required>
                <option value="">Selecione uma diocese...</option>
                @foreach ($dioceses as $diocese)
                    <option value="{{ $diocese->id }}" @selected(old('entidade_pai_id') == $diocese->id)>{{ $diocese->nome }}</option>
                @endforeach
            </select>
            @error('entidade_pai_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome</label>
            <input type="text" name="nome" class="w-full px-4 py-2 border rounded-lg @error('nome') border-red-500 @enderror" required value="{{ old('nome') }}">
            @error('nome')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror" value="{{ old('email') }}">
            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Criar Núcleo
            </button>
            <a href="{{ route('nucleos.index') }}" class="ml-2 px-6 py-2 text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
