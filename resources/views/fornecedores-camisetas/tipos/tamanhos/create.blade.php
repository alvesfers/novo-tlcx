@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Adicionar Tamanho</h1>
        <p class="text-gray-600 dark:text-gray-400">Criar novo tamanho para {{ $tipo->tipo_camiseta }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-8 dark:border-white/[0.05] dark:bg-white/[0.03] max-w-2xl">
        <form action="{{ route('fornecedores-camisetas.tipos.tamanhos.store', [$fornecedor, $tipo]) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tamanho <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="tamanho"
                    value="{{ old('tamanho') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tamanho') border-red-500 @enderror"
                    placeholder="Ex: P, M, G, GG"
                    required
                >
                @error('tamanho')
                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Medidas (JSON)
                </label>
                <textarea
                    name="medidas"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('medidas') border-red-500 @enderror font-mono text-sm"
                    placeholder='{"comprimento": "70cm", "largura": "50cm", "manga": "25cm"}'
                >{{ old('medidas') }}</textarea>
                @error('medidas')
                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Insira as medidas em formato JSON, ex: {"comprimento": "valor", "largura": "valor"}
                </p>
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-3 dark:text-gray-300">
                    <input
                        type="checkbox"
                        name="ativo"
                        value="1"
                        @checked(old('ativo', true))
                        class="rounded dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span>Tamanho ativo no sistema</span>
                </label>
            </div>

            <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-white/[0.05]">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-6 py-2 font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Criar Tamanho
                </button>
                <a
                    href="{{ route('fornecedores-camisetas.tipos.tamanhos.index', [$fornecedor, $tipo]) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-6 py-2 font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
