@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Casas de Retiro</h1>
        <button onclick="openCreateCasaModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Casa de Retiro
        </button>
    </div>

    <!-- Tabela de Casas de Retiro -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Endereço</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Valor Estimado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Quartos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($casas as $casa)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $casa->nome_casa }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $casa->endereco ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($casa->valor_estimado)
                            R$ {{ number_format($casa->valor_estimado, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            {{ $casa->quartos->count() }} quarto(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($casa->ativa) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $casa->ativa ? 'Ativa' : 'Inativa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditCasaModal({{ $casa->id_casa }}, '{{ addslashes($casa->nome_casa) }}', '{{ addslashes($casa->endereco ?? '') }}', {{ $casa->valor_estimado ?? 0 }}, {{ $casa->capacidade ?? 0 }}, {{ $casa->acessibilidade ? '1' : '0' }}, {{ $casa->ativa ? '1' : '0' }})"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button type="button"
                               onclick="confirmarDelecao('{{ route('casas-retiro.destroy', $casa) }}')"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                               title="Deletar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>

                            <!-- Quartos Button -->
                            <a href="{{ route('casas-retiro.quartos.index', $casa) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition-colors"
                               title="Ver quartos">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4l4 2m-2-2l-4-2"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Nenhuma casa de retiro cadastrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($casas->hasPages())
    <div class="mt-6">
        {{ $casas->links() }}
    </div>
    @endif

    <!-- CRUD Modal -->
    <x-crud-modal
        id="casaModal"
        title="Criar Nova Casa de Retiro"
        formId="casaForm"
        submitText="Criar"
    >
        <input type="hidden" name="id" id="casaId">

        <div>
            <label class="block text-sm font-medium mb-2">Nome da Casa *</label>
            <input type="text" name="nome_casa" id="casaNome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-500 text-sm" id="nome_casaError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Endereço</label>
            <input type="text" name="endereco" id="casaEndereco" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="enderecoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Valor Estimado (R$)</label>
            <input type="number" step="0.01" name="valor_estimado" id="casaValor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="valor_estimadoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Capacidade (Pessoas)</label>
            <input type="number" name="capacidade" id="casaCapacidade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
            <span class="text-red-500 text-sm" id="capacidadeError"></span>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="acessibilidade" id="casaAcessibilidade" value="1" class="rounded">
                <span class="ml-2 text-sm font-medium">Acessibilidade</span>
            </label>
        </div>

        <div id="casaAtivaField" style="display: none;">
            <label class="flex items-center">
                <input type="checkbox" name="ativa" id="casaAtiva" value="1" class="rounded">
                <span class="ml-2 text-sm font-medium">Casa ativa</span>
            </label>
        </div>
    </x-crud-modal>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openCreateCasaModal() {
    document.getElementById('casaModalTitle').textContent = 'Criar Nova Casa de Retiro';
    document.getElementById('casaModalSubmitText').textContent = 'Criar';
    document.getElementById('casaForm').reset();
    document.getElementById('casaId').value = '';
    document.getElementById('casaAtivaField').style.display = 'none';
    document.getElementById('casaModal').classList.remove('hidden');
}

function openEditCasaModal(id, nome, endereco, valor, capacidade, acessibilidade, ativa) {
    document.getElementById('casaModalTitle').textContent = 'Editar Casa de Retiro';
    document.getElementById('casaModalSubmitText').textContent = 'Atualizar';
    document.getElementById('casaId').value = id;
    document.getElementById('casaNome').value = nome;
    document.getElementById('casaEndereco').value = endereco;
    document.getElementById('casaValor').value = valor;
    document.getElementById('casaCapacidade').value = capacidade;
    document.getElementById('casaAcessibilidade').checked = acessibilidade == 1 || acessibilidade == true;
    document.getElementById('casaAtiva').checked = ativa == 1 || ativa == true;
    document.getElementById('casaAtivaField').style.display = 'block';
    document.getElementById('casaModal').classList.remove('hidden');
}

document.getElementById('casaForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('casaId').value;
    const url = id ? `/casas-retiro/${id}` : '/casas-retiro';
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

        document.getElementById('casaModal').classList.add('hidden');
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: id ? 'Casa de retiro atualizada com sucesso!' : 'Casa de retiro criada com sucesso!',
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
        text: 'Tem certeza que deseja deletar esta casa de retiro? Todos os quartos também serão deletados.',
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
