@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Filtros -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('documentos.index', ['filter' => 'todos']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'todos') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Todos
        </a>
        <a href="{{ route('documentos.index', ['filter' => 'publicos']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'publicos') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Públicos
        </a>
        <a href="{{ route('documentos.index', ['filter' => 'privados']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'privados') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white @endif">
            Privados
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Documentos
                </h3>
            </div>
            <button onclick="openModal('documentoModal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Novo Documento
            </button>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Título</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Visibilidade</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tamanho</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $documento)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="px-6 py-3.5 text-sm font-medium text-gray-800 dark:text-gray-100">{{ $documento->titulo }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $documento->tipo_documento->label() }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                @if($documento->visibilidade->value === 'publico') class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                @else class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200" @endif>
                                {{ $documento->visibilidade->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ number_format($documento->arquivo_tamanho / 1024 / 1024, 2) }} MB</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('documentos.download', $documento) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                   title="Download">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <button onclick="openModal('documentoModal')"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza?')"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                       title="Deletar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-sm">Nenhum documento encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $documentos->links() }}
</div>

<x-modal-form
    id="documentoModal"
    title="Novo Documento"
    resource="documentos"
    size="lg"
>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
            <input type="text" name="titulo" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
            <textarea name="descricao" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo</label>
            <input type="file" name="arquivo" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="tipo_documento" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    <option value="geral">Geral</option>
                    <option value="ata">Ata</option>
                    <option value="financeiro">Financeiro</option>
                    <option value="evento">Evento</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visibilidade</label>
                <select name="visibilidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    <option value="privado">Privado</option>
                    <option value="publico">Público</option>
                </select>
            </div>
        </div>
        <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
    </div>
</x-modal-form>
@endsection
