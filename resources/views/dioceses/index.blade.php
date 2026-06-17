@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Dioceses</h1>
        <button onclick="openModal('dioceses-form-modal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Nova Diocese
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
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Núcleos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dioceses as $diocese)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $diocese->nome }}</td>
                        <td class="px-6 py-4 text-sm">{{ $diocese->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $diocese->entidadesFilhas->count() }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                @if($diocese->ativo) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $diocese->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="#" onclick="editDiocese({{ $diocese->id }}, '{{ $diocese->nome }}', '{{ $diocese->email }}')" class="text-amber-600 hover:underline">Editar</a>
                            <form action="{{ route('dioceses.destroy', $diocese) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma diocese encontrada
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para criar/editar diocese -->
<x-modal id="dioceses-form-modal" title="Diocese" formId="diocese-form">
    <form id="diocese-form" method="POST" action="{{ route('dioceses.store') }}" class="space-y-4">
        @csrf
        @method('POST')

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="diocese-nome" class="w-full px-4 py-2 border rounded-lg" required>
            <span class="text-red-500 text-sm hidden" id="diocese-nome-error"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="diocese-email" class="w-full px-4 py-2 border rounded-lg">
            <span class="text-red-500 text-sm hidden" id="diocese-email-error"></span>
        </div>

        <input type="hidden" id="diocese-id" name="diocese_id">
    </form>
</x-modal>

<script>
    function editDiocese(id, nome, email) {
        document.getElementById('diocese-id').value = id;
        document.getElementById('diocese-nome').value = nome;
        document.getElementById('diocese-email').value = email;

        const form = document.getElementById('diocese-form');
        form.action = `/dioceses/${id}`;
        form.method = 'POST';
        form.querySelector('input[name="_method"]')?.remove();

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        document.querySelector('#dioceses-form-modal h2').textContent = 'Editar Diocese';
        openModal('dioceses-form-modal');
    }

    // Reset form quando modal fecha
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('dioceses-form-modal');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (modal.classList.contains('hidden')) {
                    document.getElementById('diocese-form').reset();
                    document.getElementById('diocese-form').action = '{{ route("dioceses.store") }}';
                    document.getElementById('diocese-form').method = 'POST';
                    document.getElementById('diocese-form').querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#dioceses-form-modal h2').textContent = 'Diocese';
                }
            });
        });
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endsection
