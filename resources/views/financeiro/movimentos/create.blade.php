@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Novo Movimento Financeiro</h1>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('financeiro-movimentos.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="data_movimento" class="block text-sm font-semibold mb-2">Data *</label>
                    <input type="date" id="data_movimento" name="data_movimento" value="{{ old('data_movimento', today()->toDateString()) }}" class="w-full border rounded-lg px-3 py-2 @error('data_movimento') border-red-500 @enderror" required>
                    @error('data_movimento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-semibold mb-2">Tipo *</label>
                    <select id="tipo" name="tipo" class="w-full border rounded-lg px-3 py-2 @error('tipo') border-red-500 @enderror" required>
                        <option value="">Selecione...</option>
                        <option value="entrada" @selected(old('tipo') === 'entrada')>Entrada (Receita)</option>
                        <option value="saida" @selected(old('tipo') === 'saida')>Saída (Despesa)</option>
                    </select>
                    @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="financeiro_categoria_id" class="block text-sm font-semibold mb-2">Categoria *</label>
                <select id="financeiro_categoria_id" name="financeiro_categoria_id" class="w-full border rounded-lg px-3 py-2 @error('financeiro_categoria_id') border-red-500 @enderror" required>
                    <option value="">Selecione...</option>
                    @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" data-tipo="{{ $cat->tipo->value }}" @selected(old('financeiro_categoria_id') === (string)$cat->id)>
                        {{ $cat->nome }}
                    </option>
                    @endforeach
                </select>
                @error('financeiro_categoria_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="descricao" class="block text-sm font-semibold mb-2">Descrição *</label>
                <input type="text" id="descricao" name="descricao" value="{{ old('descricao') }}" class="w-full border rounded-lg px-3 py-2 @error('descricao') border-red-500 @enderror" required>
                @error('descricao')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="valor" class="block text-sm font-semibold mb-2">Valor *</label>
                    <input type="number" id="valor" name="valor" value="{{ old('valor') }}" step="0.01" min="0" class="w-full border rounded-lg px-3 py-2 @error('valor') border-red-500 @enderror" required>
                    @error('valor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="forma_pagamento" class="block text-sm font-semibold mb-2">Forma de Pagamento *</label>
                    <select id="forma_pagamento" name="forma_pagamento" class="w-full border rounded-lg px-3 py-2 @error('forma_pagamento') border-red-500 @enderror" required>
                        <option value="">Selecione...</option>
                        <option value="dinheiro" @selected(old('forma_pagamento') === 'dinheiro')>Dinheiro</option>
                        <option value="pix" @selected(old('forma_pagamento') === 'pix')>PIX</option>
                        <option value="transferencia" @selected(old('forma_pagamento') === 'transferencia')>Transferência</option>
                        <option value="cartao" @selected(old('forma_pagamento') === 'cartao')>Cartão</option>
                        <option value="cheque" @selected(old('forma_pagamento') === 'cheque')>Cheque</option>
                        <option value="outro" @selected(old('forma_pagamento') === 'outro')>Outro</option>
                    </select>
                    @error('forma_pagamento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="evento_id" class="block text-sm font-semibold mb-2">Evento (Opcional)</label>
                <select id="evento_id" name="evento_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Nenhum evento associado</option>
                </select>
                <p class="text-gray-600 text-sm mt-1">Deixe em branco se não estiver vinculado a um evento</p>
            </div>

            <div class="mb-6">
                <label for="comprovante_url" class="block text-sm font-semibold mb-2">URL do Comprovante (Opcional)</label>
                <input type="url" id="comprovante_url" name="comprovante_url" value="{{ old('comprovante_url') }}" class="w-full border rounded-lg px-3 py-2 @error('comprovante_url') border-red-500 @enderror" placeholder="https://...">
                @error('comprovante_url')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="observacao" class="block text-sm font-semibold mb-2">Observação (Opcional)</label>
                <textarea id="observacao" name="observacao" rows="3" class="w-full border rounded-lg px-3 py-2 @error('observacao') border-red-500 @enderror">{{ old('observacao') }}</textarea>
                @error('observacao')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Criar Movimento
                </button>
                <a href="{{ route('financeiro-movimentos.index') }}" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
