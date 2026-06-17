@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Núcleos</h1>
        <button onclick="openModal('nucleos-form-modal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Novo Núcleo
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Diocese</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nucleos as $nucleo)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $nucleo->nome }}</td>
                        <td class="px-6 py-4 text-sm">{{ $nucleo->entidadePai?->nome ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $nucleo->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                @if($nucleo->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="#" onclick="editNucleo({{ $nucleo->id }}, '{{ $nucleo->nome }}', '{{ $nucleo->email }}', {{ $nucleo->entidade_pai_id }})" class="text-amber-600 hover:underline">Editar</a>
                            <form action="{{ route('nucleos.destroy', $nucleo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum núcleo encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para criar/editar núcleo -->
<x-modal id="nucleos-form-modal" title="Núcleo" formId="nucleo-form">
    <form id="nucleo-form" method="POST" action="{{ route('nucleos.store') }}" class="space-y-4">
        @csrf
        @method('POST')

        <div>
            <label class="block text-sm font-medium mb-2">Diocese *</label>
            <select name="entidade_pai_id" id="nucleo-diocese" class="w-full px-4 py-2 border rounded-lg" required>
                <option value="">Selecione uma diocese</option>
                @foreach($dioceses as $diocese)
                    <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
                @endforeach
            </select>
            <span class="text-red-500 text-sm hidden" id="nucleo-diocese-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="nucleo-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="nucleo-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="nucleo-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="nucleo-email-error"></span>
        </div>

        <input type="hidden" id="nucleo-id" name="nucleo_id">
    </form>
</x-modal>

<script>
    function editNucleo(id, nome, email, dioceseId) {
        document.getElementById('nucleo-id').value = id;
        document.getElementById('nucleo-nome').value = nome;
        document.getElementById('nucleo-email').value = email;
        document.getElementById('nucleo-diocese').value = dioceseId;

        const form = document.getElementById('nucleo-form');
        form.action = `/nucleos/${id}`;
        form.method = 'POST';
        form.querySelector('input[name="_method"]')?.remove();

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        document.querySelector('#nucleos-form-modal h2').textContent = 'Editar Núcleo';
        openModal('nucleos-form-modal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('nucleos-form-modal');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (modal.classList.contains('hidden')) {
                    document.getElementById('nucleo-form').reset();
                    document.getElementById('nucleo-form').action = '{{ route("nucleos.store") }}';
                    document.getElementById('nucleo-form').method = 'POST';
                    document.getElementById('nucleo-form').querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#nucleos-form-modal h2').textContent = 'Núcleo';
                }
            });
        });
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endsection
