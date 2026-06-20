@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Fornecedores de Camisetas</h1>
        <button onclick="openCreateFornecedorModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Fornecedor
        </button>
    </div>

    <!-- Tabela de Fornecedores -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Contato</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipos de Camiseta</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fornecedores as $fornecedor)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $fornecedor->nome }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $fornecedor->contato ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $fornecedor->email ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($fornecedor->tipos->count())
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                {{ $fornecedor->tipos->count() }} tipo(s)
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold @if($fornecedor->ativo) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            {{ $fornecedor->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditFornecedorModal({{ $fornecedor->id }}, '{{ addslashes($fornecedor->nome) }}', '{{ addslashes($fornecedor->contato ?? '') }}', '{{ addslashes($fornecedor->email ?? '') }}', {{ $fornecedor->ativo ? '1' : '0' }})"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button type="button"
                               onclick="confirmarDelecao('{{ route('fornecedores-camisetas.destroy', $fornecedor) }}')"
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
                        Nenhum fornecedor cadastrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($fornecedores->hasPages())
    <div class="mt-6">
        {{ $fornecedores->links() }}
    </div>
    @endif

    <!-- CRUD Modal -->
    <x-crud-modal
        id="fornecedorModal"
        title="Criar Novo Fornecedor"
        formId="fornecedorForm"
        submitText="Criar"
    >
        <input type="hidden" name="id" id="fornecedorId">

        <div>
            <label class="block text-sm font-medium mb-2">Nome *</label>
            <input type="text" name="nome" id="fornecedorNome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-500 text-sm" id="nomeError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Contato</label>
            <input type="text" name="contato" id="fornecedorContato" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="contatoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="fornecedorEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm" id="emailError"></span>
        </div>

        <div id="fornecedorAtivoField" style="display: none;">
            <label class="flex items-center">
                <input type="checkbox" name="ativo" id="fornecedorAtivo" value="1" class="rounded">
                <span class="ml-2 text-sm font-medium">Fornecedor ativo</span>
            </label>
        </div>
    </x-crud-modal>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openCreateFornecedorModal() {
    document.getElementById('fornecedorModalTitle').textContent = 'Criar Novo Fornecedor';
    document.getElementById('fornecedorModalSubmitText').textContent = 'Criar';
    document.getElementById('fornecedorForm').reset();
    document.getElementById('fornecedorId').value = '';
    document.getElementById('fornecedorAtivoField').style.display = 'none';
    document.getElementById('fornecedorModal').classList.remove('hidden');
}

function openEditFornecedorModal(id, nome, contato, email, ativo) {
    document.getElementById('fornecedorModalTitle').textContent = 'Editar Fornecedor';
    document.getElementById('fornecedorModalSubmitText').textContent = 'Atualizar';
    document.getElementById('fornecedorId').value = id;
    document.getElementById('fornecedorNome').value = nome;
    document.getElementById('fornecedorContato').value = contato;
    document.getElementById('fornecedorEmail').value = email;
    document.getElementById('fornecedorAtivo').checked = ativo == 1 || ativo == true;
    document.getElementById('fornecedorAtivoField').style.display = 'block';
    document.getElementById('fornecedorModal').classList.remove('hidden');
}

document.getElementById('fornecedorForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('fornecedorId').value;
    const url = id ? `/fornecedores-camisetas/${id}` : '/fornecedores-camisetas';
    const method = id ? 'PUT' : 'POST';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(data),
        });

        const responseData = await response.json().catch(e => null);
        console.log('════════════════════════════════════════');
        console.log('📊 STATUS:', response.status);
        console.log('📦 RESPOSTA COMPLETA:', responseData);
        console.log('════════════════════════════════════════');

        if (!response.ok) {
            console.error('❌ ERRO NA REQUISIÇÃO');
            console.error('Status:', response.status);
            console.error('Dados:', responseData);
            if (responseData?.errors) {
                console.error('Erros de validação:', responseData.errors);
                Object.keys(responseData.errors).forEach(key => {
                    const errorEl = document.getElementById(key + 'Error');
                    if (errorEl) {
                        errorEl.textContent = responseData.errors[key][0];
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao processar o formulário (Status: ' + response.status + ')',
                });
            }
            return;
        }

        console.log('✅ SUCESSO! Dados retornados:');
        console.table(responseData);
        document.getElementById('fornecedorModal').classList.add('hidden');
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: id ? 'Fornecedor atualizado com sucesso!' : 'Fornecedor criado com sucesso!',
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
    console.log('🗑️ URL PARA DELETAR:', url);
    Swal.fire({
        icon: 'warning',
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja deletar este fornecedor?',
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
            console.log('🚀 ENVIANDO DELETE PARA:', url);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                },
                body: '_method=DELETE'
            })
            .then(async response => {
                const data = await response.json().catch(e => null);
                console.log('════════════════════════════════════════');
                console.log('📊 STATUS DELETE:', response.status);
                console.log('📦 RESPOSTA COMPLETA:', data);
                console.log('════════════════════════════════════════');

                if (response.ok) {
                    console.log('✅ DELETADO COM SUCESSO');
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Fornecedor deletado com sucesso!',
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error('Erro: ' + response.status);
                }
            })
            .catch(error => {
                console.error('════════════════════════════════════════');
                console.error('❌ ERRO AO DELETAR:', error);
                console.error('════════════════════════════════════════');
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao deletar fornecedor: ' + error.message,
                });
            });
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
