@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Dirigentes</h1>
    </div>

    <!-- Filtros por Escopo -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('dirigentes.index', ['filter' => 'todos']) }}"
           class="px-4 py-2 rounded-lg @if($filter === 'todos') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
            Todos
        </a>
        @if(auth()->user()->isDiocese() || auth()->user()->isNucleo() || auth()->user()->isSecretaria())
            @if(auth()->user()->isDiocese())
                <a href="{{ route('dirigentes.index', ['filter' => 'minha_diocese']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'minha_diocese') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Minha Diocese
                </a>
            @endif
            @if(auth()->user()->isNucleo())
                <a href="{{ route('dirigentes.index', ['filter' => 'meu_nucleo']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'meu_nucleo') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Meu Núcleo
                </a>
            @endif
            @if(auth()->user()->isSecretaria())
                <a href="{{ route('dirigentes.index', ['filter' => 'minha_secretaria']) }}"
                   class="px-4 py-2 rounded-lg @if($filter === 'minha_secretaria') bg-blue-600 text-white @else bg-gray-200 text-gray-800 hover:bg-gray-300 @endif">
                    Minha Secretaria
                </a>
            @endif
        @endif
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <x-table-enhanced
        title="Dirigentes"
        :items="$dirigentes"
        :columns="[
            ['name' => 'nome', 'label' => 'Nome'],
            ['name' => 'telefone', 'label' => 'Telefone'],
            ['name' => 'ativo', 'label' => 'Status', 'badge' => 'status'],
        ]"
        :actions="['view', 'edit', 'delete']"
        resourceName="dirigentes"
        :createRoute="route('dirigentes.create')"
        createLabel="Novo Dirigente"
        emptyMessage="Nenhum dirigente encontrado"
        :pagination="true"
    />
</div>
@endsection
