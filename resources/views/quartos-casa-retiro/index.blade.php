@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('casas-retiro.index') }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">← Voltar</a>
            <h1 class="text-3xl font-bold">Quartos - {{ $casasDeRetiro->nome_casa }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('casas-retiro.alas.index', $casasDeRetiro) }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Ver Alas
            </a>
            <button onclick="openCreateQuartoModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Quarto
            </button>
        </div>
    </div>

    <!-- Filtro por Alas -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <label class="block text-sm font-medium mb-3">Filtrar por Ala:</label>
        <select id="alaFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todas as Alas</option>
            @foreach ($casasDeRetiro->alas as $ala)
                <option value="{{ $ala->id_ala }}">{{ $ala->nome_ala }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabela de Quartos -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Número</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ala</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Vagas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Banheiros</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acessibilidade</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quartos as $quarto)
                <tr class="border-b border-gray-200 hover:bg-gray-50 quarto-row" data-ala-id="{{ $quarto->id_ala ?? '' }}">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $quarto->numero_quarto }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if ($quarto->ala)
                            <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">
                                {{ $quarto->ala->nome_ala }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Sem ala</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $quarto->vagas }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $quarto->banheiros ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($quarto->acessibilidade) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $quarto->acessibilidade ? 'Sim' : 'Não' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditQuartoModal({{ $quarto->id_quarto }}, '{{ addslashes($quarto->numero_quarto) }}', {{ $quarto->vagas }}, {{ $quarto->banheiros ?? 0 }}, {{ $quarto->acessibilidade ? '1' : '0' }})"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button type="button"
                               onclick="confirmarDelecao('{{ route('casas-retiro.quartos.destroy', [$casasDeRetiro, $quarto]) }}')"
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
                        Nenhum quarto cadastrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($quartos->hasPages())
    <div class="mt-6">
        {{ $quartos->links() }}
    </div>
    @endif

    <!-- CRUD Modal -->
    <x-crud-modal
        id="quartoModal"
        title="Criar Novo Quarto"
        formId="quartoForm"
        submitText="Criar"
    >
        <input type="hidden" name="id" id="quartoId">

        <div>
            <label class="block text-sm font-medium mb-2">Número do Quarto *</label>
            <input type="text" name="numero_quarto" id="quartoNumero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-500 text-sm" id="numero_quartoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Vagas *</label>
            <input type="number" name="vagas" id="quartoVagas" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" required>
            <span class="text-red-500 text-sm" id="vagasError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Banheiros</label>
            <input type="number" name="banheiros" id="quartoBanheiros" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
            <span class="text-red-500 text-sm" id="banheirosError"></span>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="acessibilidade" id="quartoAcessibilidade" value="1" class="rounded">
                <span class="ml-2 text-sm font-medium">Acessibilidade</span>
            </label>
        </div>
    </x-crud-modal>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openCreateQuartoModal() {
    document.getElementById('quartoModalTitle').textContent = 'Criar Novo Quarto';
    document.getElementById('quartoModalSubmitText').textContent = 'Criar';
    document.getElementById('quartoForm').reset();
    document.getElementById('quartoId').value = '';
    document.getElementById('quartoModal').classList.remove('hidden');
}

function openEditQuartoModal(id, numero, vagas, banheiros, acessibilidade) {
    document.getElementById('quartoModalTitle').textContent = 'Editar Quarto';
    document.getElementById('quartoModalSubmitText').textContent = 'Atualizar';
    document.getElementById('quartoId').value = id;
    document.getElementById('quartoNumero').value = numero;
    document.getElementById('quartoVagas').value = vagas;
    document.getElementById('quartoBanheiros').value = banheiros;
    document.getElementById('quartoAcessibilidade').checked = acessibilidade == 1 || acessibilidade == true;
    document.getElementById('quartoModal').classList.remove('hidden');
}

document.getElementById('quartoForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('quartoId').value;
    const url = id ? `/casas-retiro/{{ $casasDeRetiro->id_casa }}/quartos/${id}` : '/casas-retiro/{{ $casasDeRetiro->id_casa }}/quartos';
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

        document.getElementById('quartoModal').classList.add('hidden');
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: id ? 'Quarto atualizado com sucesso!' : 'Quarto criado com sucesso!',
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
        text: 'Tem certeza que deseja deletar este quarto?',
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

// Filtro de Alas
document.getElementById('alaFilter').addEventListener('change', function(e) {
    const selectedAlaId = this.value;
    const rows = document.querySelectorAll('.quarto-row');

    rows.forEach(row => {
        const alaId = row.getAttribute('data-ala-id');
        if (selectedAlaId === '' || alaId === selectedAlaId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
