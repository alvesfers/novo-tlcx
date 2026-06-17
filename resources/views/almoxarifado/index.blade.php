@extends('layouts.app')

@section('content')
<div class="mb-4 sm:mb-6">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Almoxarifado</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-semibold">Total de Itens</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $resumo['total_itens'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-semibold">Itens Esgotados</h3>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $resumo['itens_esgotados'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-semibold">Abaixo do Mínimo</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $resumo['itens_abaixo_minimo'] ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-semibold">Últimas Movimentações</h3>
        <p class="text-3xl font-bold text-gray-600 dark:text-gray-400 mt-2">{{ $ultimosMovimentos ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('almoxarifado-itens.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2">Itens</h3>
        <p>Gerenciar itens de estoque</p>
    </a>
    <a href="{{ route('almoxarifado-categorias.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2">Categorias</h3>
        <p>Gerenciar categorias</p>
    </a>
    <a href="{{ route('almoxarifado-movimentos.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
        <h3 class="text-xl font-bold mb-2">Movimentações</h3>
        <p>Ver movimentações</p>
    </a>
</div>
@endsection
