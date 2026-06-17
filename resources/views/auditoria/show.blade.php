@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <a href="{{ route('auditoria.index') }}" class="text-blue-500 hover:text-blue-700">← Voltar</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">Detalhes do Log</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informações Gerais -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informações Gerais</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-600">ID</dt>
                    <dd class="text-lg text-gray-900">{{ $auditLog->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Data/Hora</dt>
                    <dd class="text-lg text-gray-900">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Ação</dt>
                    <dd>
                        <span class="px-2 py-1 rounded text-white text-sm font-medium
                            @if($auditLog->action === 'create') bg-green-500
                            @elseif($auditLog->action === 'update') bg-blue-500
                            @elseif($auditLog->action === 'delete') bg-red-500
                            @else bg-gray-500
                            @endif">
                            {{ $auditLog->action }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Usuário</dt>
                    <dd class="text-lg text-gray-900">{{ $auditLog->user?->name ?? 'Sistema' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Informações de Acesso -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Acesso</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-600">IP Address</dt>
                    <dd class="text-lg text-gray-900 font-mono">{{ $auditLog->ip_address ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">User Agent</dt>
                    <dd class="text-sm text-gray-600 break-words">{{ $auditLog->user_agent ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Modelo</dt>
                    <dd class="text-sm text-gray-900 font-mono">{{ class_basename($auditLog->model_type) ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">ID do Modelo</dt>
                    <dd class="text-lg text-gray-900">{{ $auditLog->model_id ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Valores Anteriores -->
    @if($auditLog->old_values)
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Valores Anteriores</h2>
        <div class="bg-red-50 border border-red-200 rounded p-4">
            <pre class="text-sm text-gray-700 overflow-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif

    <!-- Valores Novos -->
    @if($auditLog->new_values)
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Valores Novos</h2>
        <div class="bg-green-50 border border-green-200 rounded p-4">
            <pre class="text-sm text-gray-700 overflow-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif
</div>
@endsection
