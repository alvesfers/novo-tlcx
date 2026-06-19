@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Barzinhos</h1>
        <a href="{{ route('barzinhos.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Novo Barzinho
        </a>
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

    <!-- Tabela de Barzinhos -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Evento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Produtos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Vendas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barzinhos as $barzinho)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $barzinho->nome }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($barzinho->evento)
                            <a href="{{ route('eventos.show', $barzinho->evento) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $barzinho->evento->nome }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            {{ $barzinho->produtos->count() }} produto(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">
                            {{ $barzinho->vendas->count() }} venda(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($barzinho->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $barzinho->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-3">
                            <a href="{{ route('barzinhos.show', $barzinho) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Ver
                            </a>
                            <a href="{{ route('barzinhos.edit', $barzinho) }}" class="text-gray-600 hover:text-gray-900">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('barzinhos.destroy', $barzinho) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Tem certeza?')">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Nenhum barzinho cadastrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($barzinhos->hasPages())
    <div class="mt-6">
        {{ $barzinhos->links() }}
    </div>
    @endif
</div>
@endsection
