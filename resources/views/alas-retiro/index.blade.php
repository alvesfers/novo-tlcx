@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('casas-retiro.quartos.index', $casasDeRetiro) }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">← Voltar</a>
            <h1 class="text-3xl font-bold">Alas - {{ $casasDeRetiro->nome_casa }}</h1>
        </div>
        <button onclick="openCreateAlaModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Ala
        </button>
    </div>

    <!-- Tabela de Alas -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome da Ala</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Descrição</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Quartos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alas as $ala)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $ala->nome_ala }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $ala->descricao ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            {{ $ala->quartos->count() }} quarto(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditAlaModal({{ $ala->id_ala }}, '{{ addslashes($ala->nome_ala) }}', '{{ addslashes($ala->descricao ?? '') }}')"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button type="button"
                               onclick="confirmarDelecao('{{ route('casas-retiro.alas.destroy', [$casasDeRetiro, $ala]) }}')"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                               title="Deletar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        Nenhuma ala cadastrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($alas->hasPages())
    <div class="mt-6">
        {{ $alas->links() }}
    </div>
    @endif

    <!-- CRUD Modal -->
    <x-crud-modal
        id="alaModal"
        title="Criar Nova Ala"
        formId="alaForm"
        submitText="Criar"
    >
        <input type="hidden" name="id" id="alaId">

        <div>
            <label class="block text-sm font-medium mb-2">Nome da Ala *</label>
            <input type="text" name="nome_ala" id="alaNome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-500 text-sm" id="nome_alaError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Descrição</label>
            <textarea name="descricao" id="alaDescricao" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            <span class="text-red-500 text-sm" id="descricaoError"></span>
        </div>
    </x-crud-modal>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openCreateAlaModal() {
    document.getElementById('alaModalTitle').textContent = 'Criar Nova Ala';
    document.getElementById('alaModalSubmitText').textContent = 'Criar';
    document.getElementById('alaForm').reset();
    document.getElementById('alaId').value = '';
    document.getElementById('alaModal').classList.remove('hidden');
}

function openEditAlaModal(id, nome, descricao) {
    document.getElementById('alaModalTitle').textContent = 'Editar Ala';
    document.getElementById('alaModalSubmitText').textContent = 'Atualizar';
    document.getElementById('alaId').value = id;
    document.getElementById('alaNome').value = nome;
    document.getElementById('alaDescricao').value = descricao;
    document.getElementById('alaModal').classList.remove('hidden');
}

document.getElementById('alaForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('alaId').value;
    const url = id ? `/casas-retiro/{{ $casasDeRetiro->id_casa }}/alas/${id}` : '/casas-retiro/{{ $casasDeRetiro->id_casa }}/alas';
    const method = id ? 'PUT' : 'POST';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            const errorData = await response.json();
            if (errorData.errors) {
                Object.keys(errorData.errors).forEach(key => {
                    const errorEl = document.getElementById(key + 'Error');
                    if (errorEl) {
                        errorEl.textContent = errorData.errors[key][0];
                    }
                });
            }
            return;
        }

        document.getElementById('alaModal').classList.add('hidden');
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: id ? 'Ala atualizada com sucesso!' : 'Ala criada com sucesso!',
            showConfirmButton: false,
            timer: 1500,
        }).then(() => {
            window.location.reload();
        });
    } catch (error) {
        console.error('Erro:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Erro ao processar o formulário',
        });
    }
});

function confirmarDelecao(url) {
    Swal.fire({
        icon: 'warning',
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja deletar esta ala?',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Deletar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
        didOpen: () => {
            document.querySelector('.swal2-container').style.zIndex = '99999';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    });
}

@if (session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 1500,
        didOpen: () => {
            document.querySelector('.swal2-container').style.zIndex = '99999';
        }
    });
@endif
</script>
@endsection
