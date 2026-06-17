@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Eventos</h1>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <x-table-enhanced
        title="Eventos"
        :items="$eventos"
        :columns="[
            ['name' => 'nome', 'label' => 'Nome'],
            ['name' => 'tipoEvento.nome', 'label' => 'Tipo'],
            ['name' => 'status', 'label' => 'Status', 'badge' => 'status'],
        ]"
        :actions="['view', 'edit', 'delete']"
        resourceName="eventos"
        :createRoute="route('eventos.create')"
        createLabel="Novo Evento"
        emptyMessage="Nenhum evento encontrado"
        :pagination="true"
    />
</div>
@endsection
