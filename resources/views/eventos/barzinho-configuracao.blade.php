@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h1 class="text-4xl font-bold">Configurar Barzinho</h1>
            </div>
            <p class="text-gray-600 mt-2 ml-11">Defina o modelo de venda e configurações</p>
        </div>
        <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
    </div>

    <!-- Evento Info Card -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ $evento->nome }}</h2>
        <p class="text-gray-700 mt-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $evento->entidadeCriadora->nome }}
        </p>
    </div>

    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- Tipos de Venda - Info Cards -->
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">Tipos de Venda Disponíveis</h3>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @foreach ($tipos as $tipo)
                <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                    <h4 class="font-bold text-gray-900 mb-2">{{ $tipo->label() }}</h4>
                    <p class="text-sm text-gray-600">{{ $tipo->descricao() }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="{{ route('eventos.barzinho-configuracao.update', $evento) }}" class="bg-white rounded-lg shadow">
        @csrf
        @method('PUT')

        <!-- Dados Básicos -->
        <div class="bg-gray-50 border-b px-6 py-4 flex items-start gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-900">Configuração Básica</h3>
        </div>

        <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium mb-2">Nome do Barzinho *</label>
                <input
                    type="text"
                    name="nome"
                    value="{{ $barzinho->nome ?? 'Barzinho do Evento' }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                    required
                >
                @error('nome')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Tipo de Venda *</label>
                <select
                    name="tipo_venda"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipo_venda') border-red-500 @enderror"
                    required
                    x-data
                    @change="$dispatch('tipo-venda-changed', { tipo: $event.target.value })"
                >
                    <option value="">Selecione um tipo...</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->value }}" {{ $barzinho && $barzinho->tipo_venda->value === $tipo->value ? 'selected' : '' }}>
                            {{ $tipo->label() }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_venda')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Descrição</label>
            <textarea
                name="descricao"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
            >{{ $barzinho->descricao ?? '' }}</textarea>
            @error('descricao')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Configurações por Tipo -->
        <div class="bg-gray-50 border-t px-6 py-4 mt-6 flex items-start gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">Configurações Específicas do Tipo</h3>
        </div>

        <div class="p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg m-6">
            <p class="text-sm text-blue-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Configurações adicionais baseado no tipo de venda escolhido:
            </p>

            <div id="config-consumo-depois" class="space-y-4" style="display: none;">
                <label class="flex items-center">
                    <input type="checkbox" class="mr-2" id="permite-debito">
                    <span>Permitir débito/crédito no evento</span>
                </label>
                <div>
                    <label class="block text-sm font-medium mb-1">Limite de débito (R$)</label>
                    <input type="number" class="w-full px-3 py-2 border rounded" id="limite-debito" placeholder="Ex: 500" step="0.01">
                </div>
            </div>

            <div id="config-paga-na-hora" class="space-y-4" style="display: none;">
                <p class="text-sm text-gray-600">Nenhuma configuração adicional necessária. Pagamento é cobrado imediatamente.</p>
            </div>

            <div id="config-pre-pago" class="space-y-4" style="display: none;">
                <div>
                    <label class="block text-sm font-medium mb-1">Valor Mínimo de Recarga (R$)</label>
                    <input type="number" class="w-full px-3 py-2 border rounded" id="minimo-recarga" placeholder="Ex: 10" step="0.01">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Valor Máximo de Recarga (R$)</label>
                    <input type="number" class="w-full px-3 py-2 border rounded" id="maximo-recarga" placeholder="Ex: 500" step="0.01">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Limite de Crédito (R$)</label>
                    <input type="number" class="w-full px-3 py-2 border rounded" id="limite-credito" placeholder="Ex: 1000" step="0.01">
                </div>
            </div>

            <input type="hidden" name="config" id="config-json">
        </div>

        </div>

        <!-- Status Toggle -->
        <div class="px-6 py-4 border-t">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ $barzinho && $barzinho->ativo ? 'checked' : 'checked' }} class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <span class="font-medium text-gray-900">Barzinho ativo</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 border-t px-6 py-4 flex gap-3 justify-end">
            <a href="{{ route('eventos.show', $evento) }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium transition">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Salvar Configuração
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoSelect = document.querySelector('select[name="tipo_venda"]');
        const configJson = document.getElementById('config-json');

        function showConfigForType(tipo) {
            // Hide all
            document.getElementById('config-consumo-depois').style.display = 'none';
            document.getElementById('config-paga-na-hora').style.display = 'none';
            document.getElementById('config-pre-pago').style.display = 'none';

            // Show selected
            if (tipo === 'consumo_depois') {
                document.getElementById('config-consumo-depois').style.display = 'block';
            } else if (tipo === 'paga_na_hora') {
                document.getElementById('config-paga-na-hora').style.display = 'block';
            } else if (tipo === 'pre_pago') {
                document.getElementById('config-pre-pago').style.display = 'block';
            }

            buildConfigJson(tipo);
        }

        function buildConfigJson(tipo) {
            let config = { tipo };

            if (tipo === 'consumo_depois') {
                config.permite_debito = document.getElementById('permite-debito').checked;
                config.limite_debito = parseFloat(document.getElementById('limite-debito').value) || 0;
            } else if (tipo === 'pre_pago') {
                config.valor_minimo_recarga = parseFloat(document.getElementById('minimo-recarga').value) || 10;
                config.valor_maximo_recarga = parseFloat(document.getElementById('maximo-recarga').value) || 500;
                config.limite_credito = parseFloat(document.getElementById('limite-credito').value) || 1000;
            }

            configJson.value = JSON.stringify(config);
        }

        // Load current config if exists
        @if ($barzinho && $barzinho->barzinho_config)
            const currentConfig = @json($barzinho->barzinho_config);
            const tipo = currentConfig.tipo;

            if (tipo === 'consumo_depois') {
                document.getElementById('permite-debito').checked = currentConfig.permite_debito || false;
                document.getElementById('limite-debito').value = currentConfig.limite_debito || '';
            } else if (tipo === 'pre_pago') {
                document.getElementById('minimo-recarga').value = currentConfig.valor_minimo_recarga || '';
                document.getElementById('maximo-recarga').value = currentConfig.valor_maximo_recarga || '';
                document.getElementById('limite-credito').value = currentConfig.limite_credito || '';
            }
        @endif

        // Initial show
        if (tipoSelect.value) {
            showConfigForType(tipoSelect.value);
        }

        // On change
        tipoSelect.addEventListener('change', function() {
            showConfigForType(this.value);
        });

        // Update JSON on input change
        document.querySelectorAll('input[type="checkbox"], input[type="number"]').forEach(input => {
            input.addEventListener('change', function() {
                buildConfigJson(tipoSelect.value);
            });
        });
    });
</script>
@endsection
