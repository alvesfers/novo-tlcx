@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Categorias de Tarefas</h1>
        <button onclick="openModal('categoriaModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Nova Categoria
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @forelse($categorias as $categoria)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4" style="border-left-color: {{ $categoria->cor ?? '#3b82f6' }}">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $categoria->nome }}</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ Str::limit($categoria->descricao, 50) }}</p>
        <div class="flex gap-2">
            <button onclick="openModal('categoriaModal')" class="text-blue-600 hover:underline text-sm">Editar</button>
            <form action="{{ route('tarefa-categorias.destroy', $categoria) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Tem certeza?')">Deletar</button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center text-gray-500 py-8">Nenhuma categoria encontrada</div>
    @endforelse
</div>

<x-modal-form
    id="categoriaModal"
    title="Nova Categoria"
    resource="tarefa-categorias"
    size="md"
>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
            <input type="text" name="nome" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
            <textarea name="descricao" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cor</label>
            <input type="color" name="cor" value="#3b82f6" class="w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 h-10">
        </div>
        <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
    </div>
</x-modal-form>
@endsection
