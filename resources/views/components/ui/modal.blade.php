@props([
    'isOpen' => false,
    'showCloseButton' => true,
])

<div x-data="{
    open: @js($isOpen),
    init() {
        this.$watch('open', value => {
            document.body.style.overflow = value ? 'hidden' : '';
        });
    }
}" x-show="open" x-cloak @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    {{ $attributes->except(['class', 'isOpen', 'showCloseButton']) }}>

    {{-- Backdrop --}}
    <div @click="open = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    {{-- Modal Content --}}
    <div @click.stop class="relative w-full rounded-2xl bg-white shadow-xl {{ $attributes->get('class') }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        @if ($showCloseButton)
            <button @click="open = false"
                class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif

        <div>
            {{ $slot }}
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>
