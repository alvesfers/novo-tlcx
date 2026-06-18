<div class="card">
    <div class="card-header">
        <h5 class="card-title">Habilidades</h5>
    </div>
    <div class="card-body">
        @if ($dirigente->habilidades->count() > 0)
            <div class="mb-4">
                <h6 class="mb-3">Habilidades registradas:</h6>
                @php
                    $habilidadesAgrupadas = $dirigente->habilidades->groupBy(function($hab) {
                        return $hab->secretaria->nome;
                    });
                @endphp

                @foreach ($habilidadesAgrupadas as $secretaria => $habilidades)
                    <div class="mb-3">
                        <strong>{{ $secretaria }}:</strong>
                        <div class="mt-2">
                            @foreach ($habilidades as $habilidade)
                                <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                    <div>
                                        <span>{{ $habilidade->nome }}</span>
                                        <span class="badge bg-{{ $habilidade->pivot->nivel->color() }} ms-2">
                                            {{ $habilidade->pivot->nivel->label() }}
                                        </span>
                                        @if ($habilidade->pivot->observacao)
                                            <small class="d-block text-muted mt-1">{{ $habilidade->pivot->observacao }}</small>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                                data-bs-target="#editHabilidadeModal{{ $habilidade->id }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form action="{{ route('dirigentes.habilidades.destroy', [$dirigente, $habilidade]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Tem certeza?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal de edição -->
                                <div class="modal fade" id="editHabilidadeModal{{ $habilidade->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('dirigentes.habilidades.update', [$dirigente, $habilidade]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar: {{ $habilidade->nome }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nível</label>
                                                        <select name="nivel" class="form-control" required>
                                                            @foreach (\App\Enums\NivelHabilidade::cases() as $nivel)
                                                                <option value="{{ $nivel->value }}"
                                                                    {{ $habilidade->pivot->nivel === $nivel ? 'selected' : '' }}>
                                                                    {{ $nivel->label() }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Observação</label>
                                                        <textarea name="observacao" class="form-control" rows="2">{{ $habilidade->pivot->observacao }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Nenhuma habilidade registrada ainda.</p>
        @endif

        <!-- Formulário para adicionar habilidade -->
        <hr>
        <h6 class="mb-3">Adicionar habilidade:</h6>
        <form action="{{ route('dirigentes.habilidades.store', $dirigente) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Secretaria</label>
                    <select id="secretariaSelect" class="form-control" required>
                        <option value="">Selecione uma secretaria</option>
                        @foreach (\App\Models\Entidade::where('tipo_entidade', 'secretaria')->where('ativo', true)->get() as $secretaria)
                            <option value="{{ $secretaria->id }}">{{ $secretaria->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Habilidade</label>
                    <select name="habilidade_id" id="habilidadeSelect" class="form-control" required>
                        <option value="">Selecione uma habilidade</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nível</label>
                    <select name="nivel" class="form-control" required>
                        <option value="">Selecione o nível</option>
                        @foreach (\App\Enums\NivelHabilidade::cases() as $nivel)
                            <option value="{{ $nivel->value }}">{{ $nivel->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Observação</label>
                    <input type="text" name="observacao" class="form-control" placeholder="Opcional">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Adicionar habilidade</button>
                </div>
            </div>
        </form>
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
</script>