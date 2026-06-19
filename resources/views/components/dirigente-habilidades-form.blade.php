<div x-data="habilidadesManager()" class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Habilidades</h2>
    </div>

    <div class="p-6">
        @if ($dirigente->habilidades->count() > 0)
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Habilidades Registradas</h3>
                @php
                    $habilidadesAgrupadas = $dirigente->habilidades->groupBy(function($hab) {
                        return $hab->secretaria->nome;
                    });
                @endphp

                <div class="space-y-6">
                    @foreach ($habilidadesAgrupadas as $secretaria => $habilidades)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">{{ $secretaria }}</h4>
                            <div class="space-y-2">
                                @foreach ($habilidades as $habilidade)
                                    @php
                                        $nivelEnum = \App\Enums\NivelHabilidade::tryFrom($habilidade->pivot->nivel);
                                    @endphp
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-white/[0.05] hover:shadow-sm transition">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $habilidade->nome }}</span>
                                                @if ($nivelEnum)
                                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                                        @if($nivelEnum->value === 'iniciante') bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400
                                                        @elseif($nivelEnum->value === 'intermediario') bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400
                                                        @else bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                                        @endif">
                                                        {{ $nivelEnum->label() }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($habilidade->pivot->observacao)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">{{ $habilidade->pivot->observacao }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <button type="button" @click="openEditModal({{ $habilidade->id }}, '{{ addslashes($habilidade->nome) }}', '{{ $habilidade->pivot->nivel }}', '{{ addslashes($habilidade->pivot->observacao ?? '') }}')"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition" title="Editar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('dirigentes.habilidades.destroy', [$dirigente, $habilidade]) }}" method="POST" class="inline" @submit.prevent="deleteHabilidade($event)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition" title="Deletar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12 mb-8">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5h.01"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Nenhuma habilidade registrada ainda.</p>
            </div>
        @endif

        <!-- Formulário para adicionar habilidade -->
        <div class="border-t border-gray-200 dark:border-white/[0.05] pt-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Adicionar Habilidade</h3>
            <form action="{{ route('dirigentes.habilidades.store', $dirigente) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secretaria *</label>
                        <select id="secretariaSelect" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Selecione uma secretaria</option>
                            @foreach (\App\Models\Entidade::where('tipo_entidade', 'secretaria')->where('ativo', true)->get() as $secretaria)
                                <option value="{{ $secretaria->id }}">{{ $secretaria->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Habilidade *</label>
                        <select name="habilidade_id" id="habilidadeSelect" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Selecione uma habilidade</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nível *</label>
                        <select name="nivel" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Selecione o nível</option>
                            @foreach (\App\Enums\NivelHabilidade::cases() as $nivel)
                                <option value="{{ $nivel->value }}">{{ $nivel->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observação</label>
                        <input type="text" name="observacao" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Opcional">
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

<!-- Modal de edição -->
<div id="editHabilidadeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/[0.05]">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Habilidade</h3>
        </div>
        <form id="editHabilidadeForm" method="POST" class="space-y-4 p-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="habilidadeId" value="">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Habilidade</label>
                <p id="habilidadeNome" class="text-gray-900 dark:text-white font-medium"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nível</label>
                <select name="nivel" id="editNivel" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    @foreach (\App\Enums\NivelHabilidade::cases() as $nivel)
                        <option value="{{ $nivel->value }}">{{ $nivel->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observação</label>
                <textarea name="observacao" id="editObservacao" class="w-full px-4 py-2 border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="3"></textarea>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05] flex gap-3 justify-end">
            <button type="button" @click="closeEditModal()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white text-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                Cancelar
            </button>
            <button type="button" @click="submitEditForm()" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                Salvar
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const secretariaSelect = document.getElementById('secretariaSelect');
    const habilidadeSelect = document.getElementById('habilidadeSelect');

    secretariaSelect.addEventListener('change', function() {
        const secretariaId = this.value;
        habilidadeSelect.innerHTML = '<option value="">Carregando...</option>';

        if (!secretariaId) {
            habilidadeSelect.innerHTML = '<option value="">Selecione uma habilidade</option>';
            return;
        }

        fetch(`/api/secretarias/${secretariaId}/habilidades`)
            .then(response => response.json())
            .then(data => {
                habilidadeSelect.innerHTML = '<option value="">Selecione uma habilidade</option>';
                data.forEach(hab => {
                    const option = document.createElement('option');
                    option.value = hab.id;
                    option.text = hab.nome;
                    habilidadeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erro:', error);
                habilidadeSelect.innerHTML = '<option value="">Erro ao carregar habilidades</option>';
            });
    });
});

function habilidadesManager() {
    return {
        openEditModal(habilidadeId, nome, nivel, observacao) {
            document.getElementById('habilidadeId').value = habilidadeId;
            document.getElementById('habilidadeNome').textContent = nome;
            document.getElementById('editNivel').value = nivel;
            document.getElementById('editObservacao').value = observacao;
            document.getElementById('editHabilidadeModal').style.display = 'flex';
        },
        closeEditModal() {
            document.getElementById('editHabilidadeModal').style.display = 'none';
        },
        submitEditForm() {
            const habilidadeId = document.getElementById('habilidadeId').value;
            const form = document.getElementById('editHabilidadeForm');
            form.action = `/dirigentes/{{ $dirigente->id }}/habilidades/${habilidadeId}`;
            form.submit();
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
                    event.target.closest('form').submit();
                }
            });
        }
    };
}
</script>