@props([
    'id' => 'modal',
    'title' => 'Modal',
    'submitText' => 'Salvar',
    'size' => 'md',
    'resource' => null,
])

@php
$sizeClasses = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
];
$sizeClass = $sizeClasses[$size] ?? 'max-w-md';
$idLower = strtolower($id);
@endphp

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
        <div class="bg-white rounded-2xl shadow-xl w-full {{ $sizeClass }} max-h-[90vh] flex flex-col" @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <h2 class="text-base font-semibold text-gray-800" id="{{ $id }}Title">{{ $title }}</h2>
                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto px-6 pb-2 flex-1">
                <form id="{{ $id }}Form" class="space-y-4">
                    {{ $slot }}
                </form>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button type="submit" form="{{ $id }}Form" id="{{ $id }}SubmitBtn"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg min-w-[90px] transition-colors">
                    {{ $submitText }}
                </button>
            </div>
        </div>
    </div>
</div>

@if ($resource)
<script>
(function() {
    function init() {
        window.modalStates = window.modalStates || {};
        window.modalStates['{{ $id }}'] = window.modalStates['{{ $id }}'] || {};
        window.modalStates['{{ $id }}'].resourceName = '{{ $resource }}';
        window.modalStates['{{ $id }}'].isEditing = window.modalStates['{{ $id }}'].isEditing || false;
        window.modalStates['{{ $id }}'].currentId = window.modalStates['{{ $id }}'].currentId || null;
        var form = document.getElementById('{{ $id }}Form');
        if (form && !form._tlcSubmitBound) {
            form._tlcSubmitBound = true;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                window.submitModalForm('{{ $id }}');
            });
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>
@endif
