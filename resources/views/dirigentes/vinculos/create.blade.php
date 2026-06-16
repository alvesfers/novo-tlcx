@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Adicionar Vínculo</h1>
    <p class="text-gray-600 mb-6">Dirigente: <strong>{{ $dirigente->nome }}</strong></p>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('dirigentes.vinculos.store', $dirigente) }}" method="POST">
            @csrf

            <input type="hidden" name="dirigente_id" value="{{ $dirigente->id }}">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Tipo de Vínculo *</label>
                <select name="tipo_vinculo"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_vinculo') border-red-500 @enderror">
                    <option value="">Selecione</option>
                    <option value="adicional" @selected(old('tipo_vinculo') === 'adicional')>Adicional</option>
                    <option value="coordenacao" @selected(old('tipo_vinculo') === 'coordenacao')>Coordenação</option>
                </select>
                @error('tipo_vinculo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Entidade *</label>
                <select name="entidade_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('entidade_id') border-red-500 @enderror">
                    <option value="">Selecione uma entidade</option>

                    <optgroup label="Núcleos">
                        @foreach ($nucleos as $nucleo)
                            <option value="{{ $nucleo->id }}" @selected(old('entidade_id') == $nucleo->id)>
                                {{ $nucleo->nome }}
                            </option>
                        @endforeach
                    </optgroup>

                    <optgroup label="Secretarias">
                        @foreach ($secretarias as $secretaria)
                            <option value="{{ $secretaria->id }}" @selected(old('entidade_id') == $secretaria->id)>
                                {{ $secretaria->nome }}
                            </option>
                        @endforeach
                    </optgroup>

                    <optgroup label="Dioceses">
                        @foreach ($dioceses as $diocese)
                            <option value="{{ $diocese->id }}" @selected(old('entidade_id') == $diocese->id)>
                                {{ $diocese->nome }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
                @error('entidade_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Cargo *</label>
                <select name="cargo"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('cargo') border-red-500 @enderror">
                    <option value="">Selecione</option>
                    <option value="dirigente" @selected(old('cargo') === 'dirigente')>Dirigente</option>
                    <option value="coordenador" @selected(old('cargo') === 'coordenador')>Coordenador</option>
                </select>
                @error('cargo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Papel</label>
                <input type="text" name="papel" value="{{ old('papel') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Ex: Líder de Jovens, Tesoureiro">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Data de Início</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Data de Fim</label>
                <input type="date" name="data_fim" value="{{ old('data_fim') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Criar Vínculo
                </button>
                <a href="{{ route('dirigentes.show', $dirigente) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
