@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Formas de Pagamento</h1>
        <button onclick="openCreateFormaModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Forma
        </button>
    </div>

    <!-- Tabela de Formas de Pagamento -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome/Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Taxa Crédito</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Taxa Débito</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Taxa PIX</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($formas as $forma)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $forma->nome }} ({{ $forma->tipo }})</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($forma->taxa_credito)
                            {{ number_format($forma->taxa_credito, 2, ',', '.') }}%
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($forma->taxa_debito)
                            {{ number_format($forma->taxa_debito, 2, ',', '.') }}%
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($forma->taxa_pix)
                            {{ number_format($forma->taxa_pix, 2, ',', '.') }}%
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($forma->ativa) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $forma->ativa ? 'Ativa' : 'Inativa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditFormaModal({{ $forma->id }}, '{{ addslashes($forma->nome) }}', '{{ addslashes($forma->tipo) }}', {{ $forma->taxa_credito ?? 0 }}, {{ $forma->taxa_debito ?? 0 }}, {{ $forma->taxa_pix ?? 0 }}, {{ $forma->ativa ? '1' : '0' }})"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button type="button"
                               onclick="confirmarDelecao('{{ route('formas-pagamento.destroy', $forma) }}')"
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
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Nenhuma forma de pagamento cadastrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($formas->hasPages())
    <div class="mt-6">
        {{ $formas->links() }}
    </div>
    @endif

    <!-- CRUD Modal -->
    <x-crud-modal
        id="formaModal"
        title="Criar Nova Forma de Pagamento"
        formId="formaForm"
        submitText="Criar"
    >
        <input type="hidden" name="id" id="formaId">

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="formaNome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-500 text-sm" id="nomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tipo *</label>
            <select name="tipo" id="formaTipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Selecione...</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="cartao_credito">Cartão de Crédito</option>
                <option value="cartao_debito">Cartão de Débito</option>
                <option value="pix">PIX</option>
                <option value="outra">Outra</option>
            </select>
            <span class="text-red-500 text-sm" id="tipoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Taxa Crédito (%)</label>
            <input type="number" step="0.01" name="taxa_credito" id="formaTaxaCredito" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="taxa_creditoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Taxa Débito (%)</label>
            <input type="number" step="0.01" name="taxa_debito" id="formaTaxaDebito" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="taxa_debitoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Taxa PIX (%)</label>
            <input type="number" step="0.01" name="taxa_pix" id="formaTaxaPix" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="taxa_pixError"></span>
        </div>

        <div id="formaAtivaField" style="display: none;">
            <label class="flex items-center">
                <input type="checkbox" name="ativa" id="formaAtiva" value="1" class="rounded">
                <span class="ml-2 text-sm font-medium">Forma ativa</span>
            </label>
        </div>
    </x-crud-modal>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openCreateFormaModal() {
    document.getElementById('formaModalTitle').textContent = 'Criar Nova Forma de Pagamento';
    document.getElementById('formaModalSubmitText').textContent = 'Criar';
    document.getElementById('formaForm').reset();
    document.getElementById('formaId').value = '';
    document.getElementById('formaAtivaField').style.display = 'none';
    document.getElementById('formaModal').classList.remove('hidden');
}

function openEditFormaModal(id, nome, tipo, taxaCredito, taxaDebito, taxaPix, ativa) {
    document.getElementById('formaModalTitle').textContent = 'Editar Forma de Pagamento';
    document.getElementById('formaModalSubmitText').textContent = 'Atualizar';
    document.getElementById('formaId').value = id;
    document.getElementById('formaNome').value = nome;
    document.getElementById('formaTipo').value = tipo;
    document.getElementById('formaTaxaCredito').value = taxaCredito;
    document.getElementById('formaTaxaDebito').value = taxaDebito;
    document.getElementById('formaTaxaPix').value = taxaPix;
    document.getElementById('formaAtiva').checked = ativa == 1 || ativa == true;
    document.getElementById('formaAtivaField').style.display = 'block';
    document.getElementById('formaModal').classList.remove('hidden');
}

document.getElementById('formaForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('formaId').value;
    const url = id ? `/formas-pagamento/${id}` : '/formas-pagamento';
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

        document.getElementById('formaModal').classList.add('hidden');
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: id ? 'Forma de pagamento atualizada com sucesso!' : 'Forma de pagamento criada com sucesso!',
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
        text: 'Tem certeza que deseja deletar esta forma de pagamento?',
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
