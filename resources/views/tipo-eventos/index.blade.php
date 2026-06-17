@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tipos de Eventos</h1>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <x-table-enhanced
        title="Tipos de Eventos"
        :items="$tiposEvento"
        :columns="[
            ['name' => 'nome', 'label' => 'Nome'],
        ]"
        :actions="['edit', 'delete']"
        resourceName="tipo-eventos"
        :createRoute="route('tipo-eventos.create')"
        createLabel="Novo Tipo"
        emptyMessage="Nenhum tipo de evento encontrado"
        :pagination="true"
    />
</div>
@endsection
