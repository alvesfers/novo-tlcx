@extends('layouts.app')

@section('content')
<div x-data="secretariaShowManager()" class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    @if ($secretaria->entidadePai)
                        <a href="{{ route('nucleos.show', $secretaria->entidadePai) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ $secretaria->entidadePai->nome }}
                        </a>
                    @else
                        Sem núcleo
                    @endif
                </p>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $secretaria->nome }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('update', $secretaria)
                    <button onclick="editSecretaria({{ $secretaria->id }}, '{{ addslashes($secretaria->nome) }}', '{{ addslashes($secretaria->email ?? '') }}', {{ $secretaria->entidade_pai_id }}, '{{ $secretaria->tipo_secretaria }}', {{ $secretaria->ativo ? '1' : '0' }})"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                @endcan
                @can('delete', $secretaria)
                    <form action="{{ route('secretarias.destroy', $secretaria) }}" method="POST" class="inline"
                          @submit.prevent="deleteSecretaria($event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white text-red-600 px-4 py-2 text-sm font-medium hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-950 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Deletar
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Informações Card -->
        <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Básicas</h2>

            @if($secretaria->getFotoUrl())
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ $secretaria->getFotoUrl() }}"
                        alt="{{ $secretaria->nome }}"
                        class="w-40 h-40 object-cover rounded-xl shadow-md border border-gray-200 dark:border-gray-700"
                    >
                </div>
            @endif

            <div class="space-y-5">
                @if($secretaria->tipo_secretaria)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tipo</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                            @if($secretaria->tipo_secretaria->isAberta())
                                bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400
                            @else
                                bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400
                            @endif">
                            {{ $secretaria->tipo_secretaria->label() }}
                        </span>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $secretaria->email ?? 'Não informado' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        @if($secretaria->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $secretaria->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Criada em</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $secretaria->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>

                @if($secretaria->updated_at)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Última atualização</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $secretaria->updated_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estatísticas Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Estatísticas</h2>

            <div class="space-y-5">
                <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Dirigentes</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $secretaria->dirigenteVinculos->count() }}</p>
                </div>

                <div class="p-4 rounded-xl bg-cyan-50 dark:bg-cyan-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Habilidades</p>
                    <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400">{{ $secretaria->habilidades->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dirigentes Section -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dirigentes Vinculados</h2>
        </div>

        <div class="overflow-x-auto">
            @if ($secretaria->dirigenteVinculos->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nome</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Cargo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Papel</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tipo de Vínculo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($secretaria->dirigenteVinculos as $vinculo)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $vinculo->dirigente->nome }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $vinculo->cargo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $vinculo->papel ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                        @if($vinculo->isCoordenacao())
                                            bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400
                                        @else
                                            bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400
                                        @endif">
                                        {{ $vinculo->tipo_vinculo->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                        @if($vinculo->ativo)
                                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                        @else
                                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                        @endif">
                                        {{ $vinculo->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('dirigentes.show', $vinculo->dirigente) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhum dirigente vinculado</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Habilidades Section -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Habilidades</h2>
        </div>

        <div class="p-6">
            @if ($secretaria->habilidades->count() > 0)
                <div class="overflow-x-auto mb-6">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Habilidade</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Descrição</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($secretaria->habilidades as $habilidade)
                                <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-200">{{ $habilidade->nome }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $habilidade->descricao ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                            @if($habilidade->ativo)
                                                bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                            @else
                                                bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                            @endif">
                                            {{ $habilidade->ativo ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <button type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition" onclick="editarHabilidade({{ $habilidade->id }}, '{{ addslashes($habilidade->nome) }}', '{{ addslashes($habilidade->descricao ?? '') }}', {{ $habilidade->ativo ? 'true' : 'false' }})" title="Editar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('habilidades.destroy', $habilidade) }}" method="POST" class="inline" @submit.prevent="deleteHabilidade($event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition" title="Deletar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 mb-6">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6M12 6H6M6 12H0m12 0h6"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma habilidade registrada ainda.</p>
                </div>
            @endif

            <!-- Formulário para adicionar habilidade -->
            <div class="border-t border-gray-200 dark:border-white/[0.05] pt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adicionar Habilidade</h3>
                <form action="{{ route('habilidades.store', $secretaria) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome da Habilidade *</label>
                            <input type="text" name="nome" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ex: Violão" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                            <input type="text" name="descricao" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ex: Habilidade em tocar violão">
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Adicionar Habilidade
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar secretaria -->
<x-modal
    id="secretariaEditModal"
    title="Editar Secretaria"
    submitText="Atualizar"
>
    <input type="hidden" name="id" id="secretariaId" value="">

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Núcleo</label>
        <select
            name="entidade_pai_id"
            id="secretariaNucleo"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
            <option value="">Selecione um núcleo</option>
            @foreach(\App\Models\Entidade::where('tipo_entidade', 'nucleo')->ativas()->get() as $nucleo)
                <option value="{{ $nucleo->id }}">{{ $nucleo->nome }}</option>
            @endforeach
        </select>
        <span class="text-red-500 text-sm" id="nucleoError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input
            type="text"
            name="nome"
            id="secretariaName"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="nomeError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo</label>
        <select
            name="tipo_secretaria"
            id="secretariaTipo"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
            <option value="">Selecione um tipo</option>
            <option value="aberta">Aberta</option>
            <option value="fechada">Fechada</option>
        </select>
        <span class="text-red-500 text-sm" id="tipoError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
        <input
            type="email"
            name="email"
            id="secretariaEmail"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
        <span class="text-red-500 text-sm" id="emailError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
        <label class="flex items-center dark:text-gray-200">
            <input type="checkbox" name="ativo" id="secretariaAtivo" value="1" class="rounded dark:bg-gray-700">
            <span class="ml-2">Secretaria ativa no sistema</span>
        </label>
    </div>
</x-modal>

<script>
    function secretariaShowManager() {
        return {
            deleteSecretaria(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar esta secretaria? Esta ação não pode ser desfeita.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    zIndex: 99999
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });
            },
            deleteHabilidade(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar esta habilidade?',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Deletar',
                    cancelButtonText: 'Cancelar',
                    zIndex: 99999
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });
            }
        };
    }

    function editSecretaria(id, nome, email, nucleoId, tipo, ativo) {
        document.getElementById('secretariaId').value = id;
        document.getElementById('secretariaName').value = nome;
        document.getElementById('secretariaEmail').value = email;
        document.getElementById('secretariaNucleo').value = nucleoId;
        document.getElementById('secretariaTipo').value = tipo;
        document.getElementById('secretariaAtivo').checked = ativo == 1 || ativo == true;
        showModal('secretariaEditModal');
    }

    async function submitSecretariaForm(event) {
        event.preventDefault();
        const id = document.getElementById('secretariaId').value;
        const url = `/secretarias/${id}`;
        const formData = new FormData(document.getElementById('secretariaEditModalForm'));
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(url, {
                method: 'PUT',
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

            hideModal('secretariaEditModal');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Secretaria atualizada com sucesso!',
                showConfirmButton: false,
                timer: 1500,
                zIndex: 99999
            }).then(() => {
                window.location.reload();
            });
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao processar o formulário',
                zIndex: 99999
            });
        }
    }

    function editarHabilidade(id, nome, descricao, ativo) {
        // TODO: Implementar modal para editar habilidade se necessário
        console.log('Editar habilidade:', id, nome, descricao, ativo);
    }
</script>
@endsection
