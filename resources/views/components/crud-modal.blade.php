@props([
    'id' => 'crudModal',
    'title' => 'Modal',
    'formId' => 'crudForm',
    'submitText' => 'Salvar',
])

<!-- Modal -->
<div id="{{ $id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[999999] overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 dark:bg-gray-800 my-8">
        <!-- Header -->
        <div class="flex justify-between items-start p-6 border-b dark:border-gray-700">
            <div>
                <h2 class="text-xl font-bold dark:text-white" id="{{ $id }}Title">{{ $title }}</h2>
            </div>
            <button
                type="button"
                onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body com Scroll -->
        <div class="max-h-[70vh] overflow-y-auto">
            <form id="{{ $formId }}" class="p-6 space-y-4">
                {{ $slot }}
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <button
                type="button"
                onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
                class="px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Cancelar
            </button>
            <button
                type="submit"
                form="{{ $formId }}"
                id="{{ $id }}SubmitText"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800"
            >
                {{ $submitText }}
            </button>
        </div>
    </div>
</div>
