@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h1 class="text-4xl font-bold">Configurações do Evento</h1>
            </div>
            <p class="text-gray-600 mt-2 ml-11">Customize quais módulos deseja utilizar</p>
        </div>
        <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
    </div>

    <!-- Evento Info Card -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ $evento->nome }}</h2>
        <p class="text-gray-700 mt-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $evento->entidadeCriadora->nome }}
        </p>
    </div>

    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
        ✅ {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('eventos.configuracao.update', $evento) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Dados do Evento Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="bg-gray-50 border-b px-6 py-4 flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Informações do Evento</h3>
                    <p class="text-sm text-gray-600 mt-1">Edite os dados básicos do seu evento</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Nome -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Nome do Evento *</label>
                    <input
                        type="text"
                        name="nome"
                        value="{{ $evento->nome }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                    >
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Descrição</label>
                    <textarea
                        name="descricao"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                    >{{ $evento->descricao }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tema -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Tema</label>
                    <input
                        type="text"
                        name="tema"
                        value="{{ $evento->tema }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tema') border-red-500 @enderror"
                    >
                    @error('tema')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Local -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Local</label>
                    <input
                        type="text"
                        name="local"
                        value="{{ $evento->local }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('local') border-red-500 @enderror"
                    >
                    @error('local')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Datas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Data de Início *</label>
                        <input
                            type="datetime-local"
                            name="data_inicio"
                            value="{{ $evento->data_inicio->format('Y-m-d\TH:i') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('data_inicio') border-red-500 @enderror"
                        >
                        @error('data_inicio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Data de Término</label>
                        <input
                            type="datetime-local"
                            name="data_fim"
                            value="{{ $evento->data_fim ? $evento->data_fim->format('Y-m-d\TH:i') : '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('data_fim') border-red-500 @enderror"
                        >
                        @error('data_fim')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Status *</label>
                    <select
                        name="status"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    >
                        <option value="">Selecione um status...</option>
                        @foreach(\App\Enums\StatusEvento::cases() as $status)
                            <option value="{{ $status->value }}" {{ $evento->status->value === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Casa de Retiro e Fornecedor Camisetas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Casa de Retiro</label>
                        <select
                            name="id_casa"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_casa') border-red-500 @enderror"
                        >
                            <option value="">Nenhuma</option>
                            @foreach(\App\Models\CasasDeRetiro::where('ativa', true)->get() as $casa)
                                <option value="{{ $casa->id_casa }}" {{ $evento->id_casa == $casa->id_casa ? 'selected' : '' }}>
                                    {{ $casa->nome_casa }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_casa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Fornecedor de Camisetas</label>
                        <select
                            name="fornecedores_camisetas_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('fornecedores_camisetas_id') border-red-500 @enderror"
                        >
                            <option value="">Nenhum</option>
                            @foreach(\App\Models\FornecedorCamiseta::where('ativo', true)->get() as $fornecedor)
                                <option value="{{ $fornecedor->id }}" {{ $evento->fornecedores_camisetas_id == $fornecedor->id ? 'selected' : '' }}>
                                    {{ $fornecedor->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('fornecedores_camisetas_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Ativo -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="ativo"
                        value="1"
                        {{ $evento->ativo ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                    >
                    <label class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
                        Evento ativo
                    </label>
                </div>
            </div>
        </div>

        <!-- Módulos Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="bg-gray-50 border-b px-6 py-4 flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.5a2 2 0 00-1 3.75A2 2 0 0010 15H6"/>
                </svg>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Módulos Disponíveis</h3>
                    <p class="text-sm text-gray-600 mt-1">Selecione quais funcionalidades deseja habilitar neste evento</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($modulos as $modulo)
                        @php
                            $isHabilitado = $modulosHabilitados[$modulo->value] ?? false;
                            $descricoes = [
                                'detalhes' => 'Informações básicas do evento',
                                'entidades' => 'Entidades participantes e apoiadoras',
                                'participantes_dirigentes' => 'Gerenciar participantes dirigentes',
                                'participantes_externos' => 'Gerenciar participantes externos',
                                'tipos_camiseta' => 'Tipos de camiseta disponíveis',
                                'precos' => 'Tabela de preços do evento',
                                'barzinho' => 'Sistema de barzinho/vendas',
                                'cronograma' => 'Cronograma de atividades',
                                'checkin' => 'Check-in de participantes',
                            ];
                        @endphp
                        <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition">
                            <input
                                type="checkbox"
                                name="modulos[]"
                                value="{{ $modulo->value }}"
                                {{ $isHabilitado ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                            >
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-900">
                                    {{ $modulo->label() }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $descricoes[$modulo->value] ?? '' }}
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium transition">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Salvar Configurações
            </button>
        </div>
    </form>
</div>
@endsection
