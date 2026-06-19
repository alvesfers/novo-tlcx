@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Documentos</h1>
    </div>

    <!-- Filtros -->
    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
            <form method="GET" action="{{ route('documentos.index') }}" class="flex gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                    <input
                        type="text"
                        name="search"
                        placeholder="Título do documento..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Visibilidade</label>
                    <select
                        name="filter"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos</option>
                        <option value="publicos" {{ request('filter') === 'publicos' ? 'selected' : '' }}>Públicos</option>
                        <option value="privados" {{ request('filter') === 'privados' ? 'selected' : '' }}>Privados</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('search') || request('filter'))
                    <a href="{{ route('documentos.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 text-theme-sm font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Desktop/Tablet View (≥768px) -->
    <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Documentos
                </h3>
            </div>
            <button onclick="openCreateDocumentoModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Novo Documento
            </button>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Título</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tipo</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Visibilidade</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Tamanho</th>
                        <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $documento)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-row-id="{{ $documento->id }}">
                        <td class="px-6 py-3.5 text-sm font-medium text-gray-800 dark:text-gray-100">{{ $documento->titulo }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ $documento->tipo_documento->label() }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                @if($documento->visibilidade->value === 'publico')
                                    bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                @else
                                    bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                                @endif">
                                {{ $documento->visibilidade->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 dark:text-gray-300">{{ number_format($documento->arquivo_tamanho / 1024 / 1024, 2) }} MB</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('documentos.download', $documento) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                   title="Download">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <button onclick="openEditDocumentoModal({{ $documento->id }}, '{{ addslashes($documento->titulo) }}', '{{ $documento->tipo_documento->value }}')"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" @submit.prevent="deletarDocumento($event, {{ $documento->id }})">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                       title="Deletar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Nenhum documento encontrado</p>
                                <button onclick="openCreateDocumentoModal()" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-green-700">
                                    <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Novo Documento
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View (<768px) -->
    <div class="md:hidden space-y-3">
        @if(count($documentos) > 0)
            <div class="flex flex-col gap-2">
                <button onclick="openCreateDocumentoModal()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Novo Documento
                </button>
            </div>
        @endif

        @forelse($documentos as $documento)
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">{{ $documento->titulo }}</h4>
                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($documento->arquivo_tamanho / 1024 / 1024, 2) }} MB</p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded text-theme-xs font-medium ml-2
                        @if($documento->visibilidade->value === 'publico')
                            bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                        @else
                            bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500
                        @endif">
                        {{ $documento->visibilidade->label() }}
                    </span>
                </div>

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('documentos.download', $documento) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-theme-xs font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-900 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>

                    <button onclick="openEditDocumentoModal({{ $documento->id }}, '{{ addslashes($documento->titulo) }}', '{{ $documento->tipo_documento->value }}')"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-theme-xs font-medium text-amber-600 hover:bg-amber-50 dark:border-amber-900 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>

                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="flex-1" @submit.prevent="deletarDocumento($event, {{ $documento->id }})">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Deletar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-medium mb-4">Nenhum documento encontrado</p>
                    <button onclick="openCreateDocumentoModal()" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-2 text-theme-sm font-medium hover:bg-green-700">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Novo Documento
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $documentos->links() }}
    </div>
</div>

<!-- Modal de Criação/Edição -->
<x-crud-modal
    id="documentoModal"
    title="Criar Novo Documento"
    formId="documentoModalForm"
    submitText="Criar"
>
    <input type="hidden" name="id" id="documentoId" value="">

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Título</label>
        <input
            type="text"
            name="titulo"
            id="documentoTitulo"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
        <span class="text-red-500 text-sm" id="tituloError"></span>
    </div>

    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Descrição</label>
        <textarea
            name="descricao"
            id="documentoDescricao"
            rows="3"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        ></textarea>
        <span class="text-red-500 text-sm" id="descricaoError"></span>
    </div>

    <div id="arquivoField">
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Arquivo</label>
        <input
            type="file"
            name="arquivo"
            id="documentoArquivo"
            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
        <span class="text-red-500 text-sm" id="arquivoError"></span>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Tipo</label>
            <select
                name="tipo_documento"
                id="documentoTipo"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="geral">Geral</option>
                <option value="ata">Ata</option>
                <option value="financeiro">Financeiro</option>
                <option value="evento">Evento</option>
            </select>
            <span class="text-red-500 text-sm" id="tipo_documentoError"></span>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-200">Visibilidade</label>
            <select
                name="visibilidade"
                id="documentoVisibilidade"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="privado">Privado</option>
                <option value="publico">Público</option>
            </select>
            <span class="text-red-500 text-sm" id="visibilidadeError"></span>
        </div>
    </div>

    <input type="hidden" name="entidade_id" value="{{ auth()->user()->entidade_id }}">
</x-crud-modal>

<script>
    // Abre modal para criar documento
    function openCreateDocumentoModal() {
        document.getElementById('documentoModalTitle').textContent = 'Criar Novo Documento';
        document.getElementById('documentoSubmitBtn').textContent = 'Criar';
        document.getElementById('documentoModalForm').reset();
        document.getElementById('documentoId').value = '';
        document.getElementById('arquivoField').style.display = 'block';
        document.getElementById('documentoModal').classList.remove('hidden');
    }

    // Abre modal para editar documento
    function openEditDocumentoModal(id, titulo, tipo) {
        document.getElementById('documentoModalTitle').textContent = 'Editar Documento';
        document.getElementById('documentoSubmitBtn').textContent = 'Atualizar';
        document.getElementById('documentoId').value = id;
        document.getElementById('documentoTitulo').value = titulo;
        document.getElementById('documentoTipo').value = tipo;
        document.getElementById('arquivoField').style.display = 'none';
        document.getElementById('documentoModal').classList.remove('hidden');
    }

    // Submete o formulário
    async function submitDocumentoForm(event) {
        event.preventDefault();

        const id = document.getElementById('documentoId').value;
        const url = id ? `/documentos/${id}` : '/documentos';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('documentoModalForm'));
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

            document.getElementById('documentoModal').classList.add('hidden');
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: id ? 'Documento atualizado com sucesso!' : 'Documento criado com sucesso!',
                showConfirmButton: false,
                timer: 1500,
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '99999';
                }
            }).then(() => {
                window.location.reload();
            });
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao processar o formulário',
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '99999';
                }
            });
        }
    }

    // Deletar documento com SweetAlert
    function deletarDocumento(event, documentoId) {
        event.preventDefault();

        Swal.fire({
            title: 'Confirmar exclusão',
            text: 'Tem certeza que deseja deletar este documento? Esta ação não pode ser desfeita.',
            icon: 'warning',
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
                event.target.closest('form').submit();
            }
        });
    }
</script>
@endsection
