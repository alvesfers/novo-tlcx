<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - {{ $evento->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">
            <!-- Card Principal -->
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $evento->nome }}</h1>
                    <p class="text-gray-600 mt-2">{{ $evento->entidadeCriadora->nome }}</p>
                    @if ($evento->descricao)
                        <p class="text-gray-700 mt-4">{{ $evento->descricao }}</p>
                    @endif
                </div>

                <!-- Formulário -->
                <form id="formularioParticipante" class="space-y-6">
                    @csrf

                    @forelse ($formulario as $campo)
                        @php
                            $campoId = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($campo['nome']));
                            $requerido = $campo['obrigatorio'] ?? false;
                        @endphp

                        <div>
                            <label for="{{ $campoId }}" class="block text-sm font-medium text-gray-900 mb-2">
                                {{ $campo['nome'] }}
                                @if ($requerido)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if ($campo['descricao'])
                                <p class="text-sm text-gray-600 mb-2">{{ $campo['descricao'] }}</p>
                            @endif

                            <!-- Text, Email, Number, Date -->
                            @if (in_array($campo['tipo'], ['text', 'email', 'number', 'date']))
                                <input
                                    type="{{ $campo['tipo'] }}"
                                    id="{{ $campoId }}"
                                    name="{{ $campoId }}"
                                    {{ $requerido ? 'required' : '' }}
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ $campo['descricao'] ?? '' }}"
                                >
                            @endif

                            <!-- Textarea -->
                            @if ($campo['tipo'] === 'textarea')
                                <textarea
                                    id="{{ $campoId }}"
                                    name="{{ $campoId }}"
                                    rows="4"
                                    {{ $requerido ? 'required' : '' }}
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="{{ $campo['descricao'] ?? '' }}"
                                ></textarea>
                            @endif

                            <!-- Select -->
                            @if ($campo['tipo'] === 'select')
                                <select
                                    id="{{ $campoId }}"
                                    name="{{ $campoId }}"
                                    {{ $requerido ? 'required' : '' }}
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Selecione uma opção...</option>
                                    @foreach ($campo['opcoes'] ?? [] as $opcao)
                                        <option value="{{ $opcao }}">{{ $opcao }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <!-- Radio -->
                            @if ($campo['tipo'] === 'radio')
                                <div class="space-y-2">
                                    @foreach ($campo['opcoes'] ?? [] as $opcao)
                                        @php $radioId = $campoId . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($opcao)); @endphp
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                id="{{ $radioId }}"
                                                name="{{ $campoId }}"
                                                value="{{ $opcao }}"
                                                {{ $requerido && $loop->first ? 'required' : '' }}
                                                class="w-4 h-4 text-blue-600"
                                            >
                                            <label for="{{ $radioId }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                {{ $opcao }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Checkbox -->
                            @if ($campo['tipo'] === 'checkbox')
                                <div class="space-y-2">
                                    @foreach ($campo['opcoes'] ?? [] as $opcao)
                                        @php $checkboxId = $campoId . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($opcao)); @endphp
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                id="{{ $checkboxId }}"
                                                name="{{ $campoId }}[]"
                                                value="{{ $opcao }}"
                                                class="w-4 h-4 text-blue-600 rounded"
                                            >
                                            <label for="{{ $checkboxId }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                {{ $opcao }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-600">
                            <p>Nenhum campo de formulário configurado para este evento.</p>
                        </div>
                    @endforelse

                    @if (count($formulario) > 0)
                        <!-- Botão Enviar -->
                        <div class="mt-8 pt-6 border-t">
                            <button
                                type="submit"
                                id="btnEnviar"
                                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition"
                            >
                                Enviar Formulário
                            </button>
                        </div>
                    @endif
                </form>

                <!-- Mensagem de Carregamento -->
                <div id="carregando" style="display: none;" class="text-center py-8">
                    <div class="inline-block">
                        <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                    </div>
                    <p class="mt-4 text-gray-600">Enviando formulário...</p>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="mt-8 text-center text-gray-600 text-sm">
                <p>{{ $evento->data_inicio->format('d/m/Y') }}{{ $evento->data_fim ? ' até ' . $evento->data_fim->format('d/m/Y') : '' }} • {{ $evento->local }}</p>
            </div>
        </div>
    </div>

    <script>
        const formulario = document.getElementById('formularioParticipante');
        const carregando = document.getElementById('carregando');
        const btnEnviar = document.getElementById('btnEnviar');

        formulario.addEventListener('submit', async (e) => {
            e.preventDefault();

            btnEnviar.disabled = true;
            carregando.style.display = 'block';

            const formData = new FormData(formulario);
            const data = Object.fromEntries(formData);

            try {
                const response = await axios.post(
                    '{{ route("evento.formulario.enviar", $evento->uuid) }}',
                    data,
                    {
                        headers: {
                            'X-CSRF-Token': document.querySelector('input[name="_token"]').value
                        }
                    }
                );

                if (response.data.success) {
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 500);
                }
            } catch (error) {
                carregando.style.display = 'none';
                btnEnviar.disabled = false;

                if (error.response?.status === 422) {
                    alert('Por favor, preencha todos os campos obrigatórios corretamente.');
                } else {
                    alert('Ocorreu um erro ao enviar o formulário. Tente novamente.');
                }
                console.error(error);
            }
        });
    </script>
</body>
</html>
