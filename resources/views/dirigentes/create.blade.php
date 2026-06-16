@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Novo Dirigente</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('dirigentes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Nome *</label>
                <input type="text" name="nome" value="{{ old('nome') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('nome') border-red-500 @enderror"
                    placeholder="Nome completo do dirigente">
                @error('nome')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="(11) 99999-9999">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Gênero</label>
                <select name="genero" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Selecione</option>
                    <option value="m" @selected(old('genero') === 'm')>Masculino</option>
                    <option value="f" @selected(old('genero') === 'f')>Feminino</option>
                    <option value="outro" @selected(old('genero') === 'outro')>Outro</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Data de Nascimento</label>
                <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Foto URL</label>
                <input type="url" name="foto_url" value="{{ old('foto_url') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="https://example.com/foto.jpg">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Núcleo Principal *</label>
                <select name="entidade_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('entidade_id') border-red-500 @enderror">
                    <option value="">Selecione um núcleo</option>
                    @foreach ($nucleos as $nucleo)
                        <option value="{{ $nucleo->id }}" @selected(old('entidade_id') == $nucleo->id)>
                            {{ $nucleo->nome }} ({{ $nucleo->entidadePai?->nome }})
                        </option>
                    @endforeach
                </select>
                @error('entidade_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Criar Dirigente
                </button>
                <a href="{{ route('dirigentes.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
