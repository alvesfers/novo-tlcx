@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Editar Vínculo</h1>
    <p class="text-gray-600 mb-6">
        Dirigente: <strong>{{ $dirigente->nome }}</strong> - Entidade: <strong>{{ $vinculo->entidade->nome }}</strong>
    </p>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('dirigentes.vinculos.update', [$dirigente, $vinculo]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Tipo de Vínculo</label>
                <p class="px-4 py-2 bg-gray-100 rounded-lg capitalize">{{ $vinculo->tipo_vinculo }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Entidade</label>
                <p class="px-4 py-2 bg-gray-100 rounded-lg">
                    {{ $vinculo->entidade->nome }}<br>
                    <small class="text-gray-600">{{ $vinculo->entidade->getHierarquiaCompleta() }}</small>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Cargo *</label>
                <select name="cargo"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('cargo') border-red-500 @enderror">
                    <option value="">Selecione</option>
                    <option value="dirigente" @selected(old('cargo', $vinculo->cargo) === 'dirigente')>Dirigente</option>
                    <option value="coordenador" @selected(old('cargo', $vinculo->cargo) === 'coordenador')>Coordenador</option>
                </select>
                @error('cargo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Papel</label>
                <input type="text" name="papel" value="{{ old('papel', $vinculo->papel) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Ex: Líder de Jovens, Tesoureiro">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Data de Início</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio', $vinculo->data_inicio?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Data de Fim</label>
                <input type="date" name="data_fim" value="{{ old('data_fim', $vinculo->data_fim?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">
                    <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $vinculo->ativo))>
                    Ativo
                </label>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Atualizar Vínculo
                </button>
                <a href="{{ route('dirigentes.show', $dirigente) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
