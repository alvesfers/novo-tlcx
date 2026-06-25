@extends('layouts.app')

@section('content')
<div x-data="dioceseShowManager()" class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $diocese->nome }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerenciamento da Diocese</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('update', $diocese)
                    <button onclick="editDiocese({{ $diocese->id }}, '{{ addslashes($diocese->nome) }}', '{{ addslashes($diocese->email ?? '') }}', {{ $diocese->ativo ? '1' : '0' }})"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                @endcan
                @can('delete', $diocese)
                    <form action="{{ route('dioceses.destroy', $diocese) }}" method="POST" class="inline"
                          @submit.prevent="deleteDiocese($event)">
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

            @if($diocese->getFotoUrl())
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ $diocese->getFotoUrl() }}"
                        alt="{{ $diocese->nome }}"
                        class="w-40 h-40 object-cover rounded-xl shadow-md border border-gray-200 dark:border-gray-700"
                    >
                </div>
            @endif

            <div class="space-y-5">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $diocese->email ?? 'Não informado' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        @if($diocese->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $diocese->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Criada em</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $diocese->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>

                @if($diocese->updated_at)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Última atualização</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $diocese->updated_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estatísticas Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Estatísticas</h2>

            <div class="space-y-5">
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Núcleos</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $diocese->entidadesFilhas->count() }}</p>
                </div>

                <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Dirigentes</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $diocese->dirigenteVinculos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Núcleos Section -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Núcleos</h2>
        </div>

        <div class="p-6">
            @if ($diocese->entidadesFilhas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($diocese->entidadesFilhas as $nucleo)
                        <div class="rounded-xl border border-gray-200 p-4 hover:shadow-md transition dark:border-white/[0.05] dark:hover:bg-white/[0.05]">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $nucleo->nome }}</h3>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($nucleo->ativo) bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $nucleo->email ?? 'Sem email' }}</p>
                            <a href="{{ route('nucleos.show', $nucleo) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Ver detalhes
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhum núcleo vinculado</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Dirigentes Section -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dirigentes Vinculados</h2>
        </div>

        <div class="overflow-x-auto">
            @if ($diocese->dirigenteVinculos->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nome</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Núcleo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Cargo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($diocese->dirigenteVinculos as $vinculo)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $vinculo->dirigente->nome }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $vinculo->entidade->nome }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $vinculo->cargo }}</td>
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
</div>

<!-- Modal para editar diocese -->
<x-modal
    id="dioceseEditModal"
    title="Editar Diocese"
    submitText="Atualizar"
>
    <input type="hidden" name="id" id="dioceseId" value="">

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input
            type="text"
            name="nome"
            id="dioceseName"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="nomeError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
        <input
            type="email"
            name="email"
            id="dioceseEmail"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
        <span class="text-red-500 text-sm" id="emailError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
        <label class="flex items-center dark:text-gray-200">
            <input type="checkbox" name="ativo" id="dioceseAtivo" value="1" class="rounded dark:bg-gray-700">
            <span class="ml-2">Diocese ativa no sistema</span>
        </label>
    </div>
</x-modal>

<script>
    function dioceseShowManager() {
        return {
            deleteDiocese(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar esta diocese? Esta ação não pode ser desfeita.',
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

    function editDiocese(id, nome, email, ativo) {
        document.getElementById('dioceseId').value = id;
        document.getElementById('dioceseName').value = nome;
        document.getElementById('dioceseEmail').value = email;
        document.getElementById('dioceseAtivo').checked = ativo == 1 || ativo == true;
        showModal('dioceseEditModal');
    }

    async function submitDioceseForm(event) {
        event.preventDefault();
        const id = document.getElementById('dioceseId').value;
        const url = `/dioceses/${id}`;
        const formData = new FormData(document.getElementById('dioceseEditModalForm'));
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

            hideModal('dioceseEditModal');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Diocese atualizada com sucesso!',
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
</script>
@endsection
