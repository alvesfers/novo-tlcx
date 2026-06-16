@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Dirigentes</h1>
        <a href="{{ route('dirigentes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Novo Dirigente
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Telefone</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Núcleo Principal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dirigentes as $dirigente)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <strong>{{ $dirigente->nome }}</strong>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $dirigente->telefone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $nucleoPrincipal = $dirigente->getNucleoPrincipal();
                            @endphp
                            {{ $nucleoPrincipal?->nome ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                @if($dirigente->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $dirigente->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('dirigentes.show', $dirigente) }}" class="text-blue-600 hover:underline">Ver</a>
                            <a href="{{ route('dirigentes.edit', $dirigente) }}" class="text-amber-600 hover:underline">Editar</a>
                            <form action="{{ route('dirigentes.destroy', $dirigente) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum dirigente encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $dirigentes->links() }}
    </div>
</div>
@endsection
