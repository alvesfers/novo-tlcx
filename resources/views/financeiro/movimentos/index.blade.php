@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Movimentações Financeiras</h1>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('financeiro-movimentos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="data_inicio" class="block text-sm font-semibold mb-2">Data Inicial</label>
                <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label for="data_fim" class="block text-sm font-semibold mb-2">Data Final</label>
                <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label for="tipo" class="block text-sm font-semibold mb-2">Tipo</label>
                <select id="tipo" name="tipo" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Todos</option>
                    <option value="entrada" @selected(request('tipo') === 'entrada')>Entrada</option>
                    <option value="saida" @selected(request('tipo') === 'saida')>Saída</option>
                </select>
            </div>
            <div>
                <label for="categoria_id" class="block text-sm font-semibold mb-2">Categoria</label>
                <select id="categoria_id" name="categoria_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Todas</option>
                    @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" @selected(request('categoria_id') === (string)$cat->id)>{{ $cat->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filtrar
                </button>
                <a href="{{ route('financeiro-movimentos.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <x-table-enhanced
        title="Movimentações Financeiras"
        :items="$movimentos"
        :columns="[
            ['name' => 'descricao', 'label' => 'Descrição'],
            ['name' => 'tipo', 'label' => 'Tipo', 'badge' => 'tipo'],
            ['name' => 'valor', 'label' => 'Valor'],
            ['name' => 'data_movimento', 'label' => 'Data'],
        ]"
        :actions="['edit', 'delete']"
        resourceName="financeiro-movimentos"
        :createRoute="route('financeiro-movimentos.create')"
        createLabel="Novo Movimento"
        emptyMessage="Nenhuma movimentação encontrada"
        :pagination="true"
    />
</div>
@endsection
