@extends('layouts.app')

@section('content')
<div x-data="nucleoShowManager()" class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    @if ($nucleo->entidadePai)
                        <a href="{{ route('dioceses.show', $nucleo->entidadePai) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ $nucleo->entidadePai->nome }}
                        </a>
                    @else
                        Sem diocese
                    @endif
                </p>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $nucleo->nome }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('update', $nucleo)
                    <button onclick="editNucleo({{ $nucleo->id }}, '{{ addslashes($nucleo->nome) }}', '{{ addslashes($nucleo->email ?? '') }}', {{ $nucleo->entidade_pai_id }}, {{ $nucleo->ativo ? '1' : '0' }})"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                @endcan
                @can('delete', $nucleo)
                    <form action="{{ route('nucleos.destroy', $nucleo) }}" method="POST" class="inline"
                          @submit.prevent="deleteNucleo($event)">
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

            @if($nucleo->getFotoUrl())
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ $nucleo->getFotoUrl() }}"
                        alt="{{ $nucleo->nome }}"
                        class="w-40 h-40 object-cover rounded-xl shadow-md border border-gray-200 dark:border-gray-700"
                    >
                </div>
            @endif

            <div class="space-y-5">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $nucleo->email ?? 'Não informado' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        @if($nucleo->ativo)
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                        @endif">
                        {{ $nucleo->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Criado em</p>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $nucleo->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>

                @if($nucleo->updated_at)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Última atualização</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $nucleo->updated_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estatísticas Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Estatísticas</h2>

            <div class="space-y-5">
                <div class="p-4 rounded-xl bg-orange-50 dark:bg-orange-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Secretarias</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $nucleo->entidadesFilhas->count() }}</p>
                </div>

                <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-500/10">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total de Dirigentes</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $nucleo->dirigenteVinculos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secretarias Section -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03] mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Secretarias</h2>
        </div>

        <div class="p-6">
            @if ($nucleo->entidadesFilhas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($nucleo->entidadesFilhas as $secretaria)
                        <div class="rounded-xl border border-gray-200 p-4 hover:shadow-md transition dark:border-white/[0.05] dark:hover:bg-white/[0.05]">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $secretaria->nome }}</h3>
                                    @if($secretaria->tipo_secretaria)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                                @if($secretaria->tipo_secretaria->isAberta()) bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400
                                                @else bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400
                                                @endif">
                                                {{ $secretaria->tipo_secretaria->label() }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($secretaria->ativo) bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                    @endif">
                                    {{ $secretaria->ativo ? 'Ativa' : 'Inativa' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $secretaria->email ?? 'Sem email' }}</p>
                            <a href="{{ route('secretarias.show', $secretaria) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
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
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma secretaria vinculada</p>
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
            @if ($nucleo->dirigenteVinculos->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-white/[0.05]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nome</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Cargo</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Papel</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nucleo->dirigenteVinculos as $vinculo)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $vinculo->dirigente->nome }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $vinculo->cargo }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $vinculo->papel ?? '-' }}</td>
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

<!-- Modal para editar núcleo -->
<x-crud-modal
    id="nucleoEditModal"
    title="Editar Núcleo"
    formId="nucleoEditForm"
    submitText="Atualizar"
>
    <input type="hidden" name="id" id="nucleoId" value="">

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Diocese</label>
        <select
            name="entidade_pai_id"
            id="nucleoDiocese"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
            <option value="">Selecione uma diocese</option>
            @foreach(\App\Models\Entidade::where('tipo_entidade', 'diocese')->ativas()->get() as $diocese)
                <option value="{{ $diocese->id }}">{{ $diocese->nome }}</option>
            @endforeach
        </select>
        <span class="text-red-500 text-sm" id="dioceseError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input
            type="text"
            name="nome"
            id="nucleoName"
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
            id="nucleoEmail"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
        <span class="text-red-500 text-sm" id="emailError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
        <label class="flex items-center dark:text-gray-200">
            <input type="checkbox" name="ativo" id="nucleoAtivo" value="1" class="rounded dark:bg-gray-700">
            <span class="ml-2">Núcleo ativo no sistema</span>
        </label>
    </div>
</x-crud-modal>

<script>
    function nucleoShowManager() {
        return {
            deleteNucleo(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmar exclusão',
                    text: 'Tem certeza que deseja deletar este núcleo? Esta ação não pode ser desfeita.',
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

    function editNucleo(id, nome, email, dioceseId, ativo) {
        document.getElementById('nucleoId').value = id;
        document.getElementById('nucleoName').value = nome;
        document.getElementById('nucleoEmail').value = email;
        document.getElementById('nucleoDiocese').value = dioceseId;
        document.getElementById('nucleoAtivo').checked = ativo == 1 || ativo == true;
        document.getElementById('nucleoEditModal').classList.remove('hidden');
    }

    async function submitNucleoForm(event) {
        event.preventDefault();
        const id = document.getElementById('nucleoId').value;
        const url = `/nucleos/${id}`;
        const formData = new FormData(document.getElementById('nucleoEditForm'));
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

            document.getElementById('nucleoEditModal').classList.add('hidden');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Núcleo atualizado com sucesso!',
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
