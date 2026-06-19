@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Editar Preço</h1>
        <p class="text-gray-600 dark:text-gray-400">Atualizar valor para {{ $evento->nome }}</p>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-8 dark:border-white/[0.05] dark:bg-white/[0.03] max-w-2xl">
        <form action="{{ route('evento-valores.update', [$evento, $valor]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tipo Valor -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipo de Valor <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="tipo_valor"
                    value="{{ old('tipo_valor', $valor->tipo_valor) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipo_valor') border-red-500 @enderror"
                    placeholder="Ex: Inteira, Meia Entrada, VIP"
                    required
                >
                @error('tipo_valor')
                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valor -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Valor (R$) <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    name="valor"
                    value="{{ old('valor', $valor->valor) }}"
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('valor') border-red-500 @enderror"
                    placeholder="0.00"
                    required
                >
                @error('valor')
                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Descrição
                </label>
                <textarea
                    name="descricao"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('descricao') border-red-500 @enderror"
                    placeholder="Descrição ou detalhes adicionais"
                >{{ old('descricao', $valor->descricao) }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ativo -->
            <div class="mb-8">
                <label class="flex items-center gap-3 dark:text-gray-300">
                    <input
                        type="checkbox"
                        name="ativo"
                        value="1"
                        @checked(old('ativo', $valor->ativo))
                        class="rounded dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span>Preço ativo no sistema</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-white/[0.05]">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-6 py-2 font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Atualizar Preço
                </button>
                <a
                    href="{{ route('evento-valores.index', $evento) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-6 py-2 font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
