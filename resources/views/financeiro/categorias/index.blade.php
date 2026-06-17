@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Categorias Financeiras</h1>
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

    <x-table-enhanced
        title="Categorias Financeiras"
        :items="$categorias"
        :columns="[
            ['name' => 'nome', 'label' => 'Nome'],
        ]"
        :actions="['edit', 'delete']"
        resourceName="financeiro-categorias"
        :createRoute="route('financeiro-categorias.create')"
        createLabel="Nova Categoria"
        emptyMessage="Nenhuma categoria encontrada"
        :pagination="true"
    />
</div>
@endsection
