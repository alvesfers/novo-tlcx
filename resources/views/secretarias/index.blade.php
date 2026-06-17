@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Secretarias</h1>
        <button onclick="openModal('secretarias-form-modal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Nova Secretaria
        </button>
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Núcleo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($secretarias as $secretaria)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $secretaria->nome }}</td>
                        <td class="px-6 py-4 text-sm">{{ $secretaria->entidadePai?->nome ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($secretaria->tipo_secretaria->isAberta()) bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ $secretaria->tipo_secretaria->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $secretaria->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                @if($secretaria->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $secretaria->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="#" onclick="editSecretaria({{ $secretaria->id }}, '{{ $secretaria->nome }}', '{{ $secretaria->email }}', {{ $secretaria->entidade_pai_id }}, '{{ $secretaria->tipo_secretaria }}')" class="text-amber-600 hover:underline">Editar</a>
                            <form action="{{ route('secretarias.destroy', $secretaria) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma secretaria encontrada
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para criar/editar secretaria -->
<x-modal id="secretarias-form-modal" title="Secretaria" formId="secretaria-form">
    <form id="secretaria-form" method="POST" action="{{ route('secretarias.store') }}" class="space-y-4">
        @csrf
        @method('POST')

        <div>
            <label class="block text-sm font-medium mb-2">Núcleo *</label>
            <select name="entidade_pai_id" id="secretaria-nucleo" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione um núcleo</option>
                @foreach($nucleos as $nucleo)
                    <option value="{{ $nucleo->id }}">{{ $nucleo->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm hidden" id="secretaria-nucleo-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="secretaria-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="secretaria-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tipo *</label>
            <select name="tipo_secretaria" id="secretaria-tipo" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione um tipo</option>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </select>
            <span class="text-red-500 text-sm hidden" id="secretaria-tipo-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="secretaria-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="secretaria-email-error"></span>
        </div>

        <input type="hidden" id="secretaria-id" name="secretaria_id">
    </form>
</x-modal>

<script>
    function editSecretaria(id, nome, email, nucleoId, tipo) {
        document.getElementById('secretaria-id').value = id;
        document.getElementById('secretaria-nome').value = nome;
        document.getElementById('secretaria-email').value = email;
        document.getElementById('secretaria-nucleo').value = nucleoId;
        document.getElementById('secretaria-tipo').value = tipo;

        const form = document.getElementById('secretaria-form');
        form.action = `/secretarias/${id}`;
        form.method = 'POST';
        form.querySelector('input[name="_method"]')?.remove();

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        document.querySelector('#secretarias-form-modal h2').textContent = 'Editar Secretaria';
        openModal('secretarias-form-modal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('secretarias-form-modal');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (modal.classList.contains('hidden')) {
                    document.getElementById('secretaria-form').reset();
                    document.getElementById('secretaria-form').action = '{{ route("secretarias.store") }}';
                    document.getElementById('secretaria-form').method = 'POST';
                    document.getElementById('secretaria-form').querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#secretarias-form-modal h2').textContent = 'Secretaria';
                }
            });
        });
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endsection
