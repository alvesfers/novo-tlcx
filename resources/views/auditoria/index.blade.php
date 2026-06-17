@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Auditoria e Logs</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ação</label>
                <input type="text" name="action" value="{{ request('action') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="create, update, delete...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                <input type="number" name="user_id" value="{{ request('user_id') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Data/Hora</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Usuário</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ação</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Modelo</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">IP</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $log->user?->name ?? 'Sistema' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs font-medium
                            @if($log->action === 'create') bg-green-500
                            @elseif($log->action === 'update') bg-blue-500
                            @elseif($log->action === 'delete') bg-red-500
                            @else bg-gray-500
                            @endif">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ class_basename($log->model_type) ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $log->ip_address ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('auditoria.show', $log) }}" class="text-blue-500 hover:text-blue-700">Ver Detalhes</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
