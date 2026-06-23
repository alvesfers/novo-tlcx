<div>
    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ $label }}</label>
    <div class="flex gap-2 items-center">
        <select
            x-model="{{ $xModel }}"
            @if(!empty($name)) name="{{ $name }}" @endif
            @if(!empty($onChange)) @change="{{ $onChange }}" @endif
            class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            <option value="">Selecione</option>
            <template x-for="opt in {{ $optExpr }}" :key="opt.id">
                <option :value="opt.id"
                    @if(!empty($withTema))
                    x-text="opt.tema ? opt.nome + ' — ' + opt.tema : opt.nome"
                    @else
                    x-text="opt.nome"
                    @endif
                ></option>
            </template>
        </select>
        <button type="button"
            @click="openModal('{{ $modalType }}', '{{ $modalTarget }}', {{ $modalPrefill ?? '{}' }})"
            class="shrink-0 text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors whitespace-nowrap">
            Não encontrei
        </button>
    </div>
</div>
