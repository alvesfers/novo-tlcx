@props([
    'id' => 'modal',
    'title' => 'Modal',
    'subtitle' => '',
])

<div id="{{ $id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[99999] overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 dark:bg-gray-800 my-8">
        <!-- Header -->
        <div class="flex justify-between items-start p-6 border-b dark:border-gray-700">
            <div class="flex-1">
                <h2 class="text-xl font-bold dark:text-white" id="{{ $id }}Title">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            <button onclick="closeModal('{{ $id }}')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 ml-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div id="{{ $id }}Body" class="p-6 max-h-[60vh] overflow-y-auto">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <button type="button" onclick="closeModal('{{ $id }}')" class="px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                Cancelar
            </button>
            <button type="submit" form="{{ $id }}Form" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800" id="{{ $id }}SubmitBtn">
                Salvar
            </button>
        </div>
    </div>
</div>
