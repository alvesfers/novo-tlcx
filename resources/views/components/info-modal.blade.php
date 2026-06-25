@props([
    'id' => 'infoModal',
    'title' => 'Informações',
])

@php $idLower = strtolower($id); @endphp

<div x-data="{ open: false }"
     @open-modal-{{ $idLower }}.window="open = true"
     @close-modal-{{ $idLower }}.window="open = false"
     @keydown.escape.window="if(open){ open = false; document.body.style.overflow = ''; }"
     x-cloak>

    {{-- Backdrop --}}
    <div x-show="open" x-transition.opacity.duration.200ms
         class="fixed inset-0 z-[999998] bg-gray-900/50 backdrop-blur-sm"
         @click="open = false; document.body.style.overflow = '';"></div>

    {{-- Modal --}}
    <div x-show="open" x-transition
         class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col" @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h2 class="text-base font-semibold text-gray-800" id="{{ $id }}Title">{{ $title }}</h2>
                <button type="button" onclick="hideModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto px-6 pb-4 flex-1" id="{{ $id }}Content">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex justify-end px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="hideModal('{{ $id }}')"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
