@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $barzinho->nome }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('barzinhos.edit', $barzinho) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('barzinhos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                Voltar
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Informações do Barzinho -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Nome</p>
                <p class="text-lg">{{ $barzinho->nome }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Evento</p>
                <p class="text-lg">
                    @if($barzinho->evento)
                        <a href="{{ route('eventos.show', $barzinho->evento) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $barzinho->evento->nome }}
                        </a>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Status</p>
                <p class="text-lg">
                    <span class="px-2 py-1 rounded text-sm font-semibold @if($barzinho->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                        {{ $barzinho->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </p>
            </div>
        </div>

        @if ($barzinho->descricao)
        <div class="pt-4 border-t">
            <p class="text-gray-600 text-sm font-semibold mb-2">Descrição</p>
            <p class="text-base">{{ $barzinho->descricao }}</p>
        </div>
        @endif
    </div>

    <!-- Tabs para Produtos, Combos e Vendas -->
    <div x-data="{ activeTab: 'produtos' }" class="bg-white rounded-lg shadow">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 flex">
            <button
                @click="activeTab = 'produtos'"
                :class="activeTab === 'produtos' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                class="px-6 py-3 font-medium text-sm"
            >
                Produtos ({{ $barzinho->produtos->count() }})
            </button>
            <button
                @click="activeTab = 'combos'"
                :class="activeTab === 'combos' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                class="px-6 py-3 font-medium text-sm"
            >
                Combos ({{ $barzinho->combos->count() }})
            </button>
            <button
                @click="activeTab = 'vendas'"
                :class="activeTab === 'vendas' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                class="px-6 py-3 font-medium text-sm"
            >
                Vendas ({{ $barzinho->vendas->count() }})
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Produtos Tab -->
            <div x-show="activeTab === 'produtos'" class="space-y-4">
                @if($barzinho->produtos->count())
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Produto</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Preço</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Quantidade</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barzinho->produtos as $produto)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $produto->nome ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">R$ {{ number_format($produto->preco ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $produto->quantidade ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-semibold @if($produto->ativo ?? true) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ ($produto->ativo ?? true) ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center py-8">Nenhum produto cadastrado</p>
                @endif
            </div>

            <!-- Combos Tab -->
            <div x-show="activeTab === 'combos'" class="space-y-4">
                @if($barzinho->combos->count())
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Combo</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Preço</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Itens</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barzinho->combos as $combo)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $combo->nome ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">R$ {{ number_format($combo->preco ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $combo->itens ? $combo->itens->count() : 0 }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-semibold @if($combo->ativo ?? true) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ ($combo->ativo ?? true) ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center py-8">Nenhum combo cadastrado</p>
                @endif
            </div>

            <!-- Vendas Tab -->
            <div x-show="activeTab === 'vendas'" class="space-y-4">
                @if($barzinho->vendas->count())
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Data</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Total</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Itens</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barzinho->vendas as $venda)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $venda->created_at ? $venda->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 font-medium">R$ {{ number_format($venda->total ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $venda->itens ? $venda->itens->count() : 0 }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-semibold @if($venda->status === 'concluida') bg-green-100 text-green-800 @elseif($venda->status === 'cancelada') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($venda->status ?? 'pendente') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center py-8">Nenhuma venda registrada</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function initTabs() {
        // Alpine.js já gerencia isso com x-data
    }
</script>
@endsection
