@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Entidades</h1>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <x-table-enhanced
        title="Entidades"
        :items="$entidades"
        :columns="[
            ['name' => 'nome', 'label' => 'Nome'],
            ['name' => 'tipo_entidade', 'label' => 'Tipo'],
            ['name' => 'email', 'label' => 'Email'],
            ['name' => 'ativo', 'label' => 'Status', 'badge' => 'status'],
        ]"
        :actions="['view', 'edit', 'delete']"
        resourceName="entidades"
        :createRoute="route('entidades.create')"
        createLabel="Nova Entidade"
        emptyMessage="Nenhuma entidade encontrada"
        :pagination="false"
    />
</div>
@endsection
