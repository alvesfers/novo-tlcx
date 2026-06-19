@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Novo Barzinho</h1>
        <a href="{{ route('barzinhos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('barzinhos.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Evento *</label>
                <select
                    name="evento_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('evento_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Selecione um evento...</option>
                    @foreach(\App\Models\Evento::ativos()->get() as $evento)
                        <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                            {{ $evento->nome }}
                        </option>
                    @endforeach
                </select>
                @error('evento_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nome *</label>
                <input
                    type="text"
                    name="nome"
                    value="{{ old('nome') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                    required
                >
                @error('nome')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
                <textarea
                    name="descricao"
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="ativo"
                        value="1"
                        {{ old('ativo', true) ? 'checked' : '' }}
                        class="rounded"
                    >
                    <span class="text-sm font-semibold text-gray-700">Ativo</span>
                </label>
                @error('ativo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                >
                    Salvar
                </button>
                <a
                    href="{{ route('barzinhos.index') }}"
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
