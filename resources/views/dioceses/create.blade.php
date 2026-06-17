@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Criar Nova Diocese</h1>

    <form action="{{ route('dioceses.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf

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
                Criar Diocese
            </button>
            <a href="{{ route('dioceses.index') }}" class="ml-2 px-6 py-2 text-gray-600 hover:text-gray-800">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
