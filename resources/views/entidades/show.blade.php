@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $entidade->nome }}</h1>
        <div class="space-x-2">
            <a href="{{ route('entidades.edit', $entidade) }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">
                Editar
            </a>
            <a href="{{ route('entidades.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Voltar
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Informações Gerais</h2>
            <div class="space-y-3">
                <div>
                    <strong>Tipo:</strong>
                    <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium
                        @if($entidade->isDiocese()) bg-purple-100 text-purple-800
                        @elseif($entidade->isNucleo()) bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($entidade->tipo_entidade) }}
                    </span>
                </div>
                <div>
                    <strong>Hierarquia:</strong>
                    <p class="text-gray-700">{{ $entidade->getHierarquiaCompleta() }}</p>
                </div>
                <div>
                    <strong>Email:</strong>
                    <p class="text-gray-700">{{ $entidade->email ?? '—' }}</p>
                </div>
                <div>
                    <strong>Status:</strong>
                    <span class="ml-2 px-2 py-1 rounded text-sm
                        @if($entidade->ativo) bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $entidade->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
            </div>
        </div>

        @if ($entidade->entidadesFilhas->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Entidades Filhas</h2>
            <ul class="space-y-2">
                @foreach ($entidade->entidadesFilhas as $filha)
                    <li>
                        <a href="{{ route('entidades.show', $filha) }}" class="text-blue-600 hover:underline">
                            {{ $filha->nome }}
                        </a>
                        <span class="ml-2 text-sm text-gray-600">({{ ucfirst($filha->tipo_entidade) }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if ($entidade->entidadePai)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Entidade Pai</h2>
            <a href="{{ route('entidades.show', $entidade->entidadePai) }}" class="text-blue-600 hover:underline">
                {{ $entidade->entidadePai->nome }}
            </a>
            <p class="text-sm text-gray-600">{{ ucfirst($entidade->entidadePai->tipo_entidade) }}</p>
        </div>
        @endif

        @if ($entidade->user)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Usuário Vinculado</h2>
            <div class="space-y-2">
                <div>
                    <strong>Nome:</strong>
                    <p class="text-gray-700">{{ $entidade->user->name }}</p>
                </div>
                <div>
                    <strong>Email:</strong>
                    <p class="text-gray-700">{{ $entidade->user->email }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">Histórico</h2>
        <div class="text-sm text-gray-600 space-y-1">
            <p><strong>Criada em:</strong> {{ $entidade->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Última atualização:</strong> {{ $entidade->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
