@props([
    'id' => 'modal',
    'title' => 'Modal',
    'submitText' => 'Salvar',
    'formId' => null,
])

<div id="{{ $id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-xl font-bold">{{ $title }}</h2>
            <button onclick="closeModal('{{ $id }}')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            {{ $slot }}
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
            <button onclick="closeModal('{{ $id }}')" class="px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100">
                Cancelar
            </button>
            <button type="submit" form="{{ $formId ?? 'form-' . $id }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                {{ $submitText }}
            </button>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Limpar formulário se existir
        const form = document.getElementById(id).querySelector('form');
        if (form) {
            form.reset();
            form.classList.remove('hidden');
        }
    }

    // Fechar modal ao clicar fora
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('[id$="-modal"]');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
    });
</script>
