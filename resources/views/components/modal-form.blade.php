@props([
    'id' => 'modal',
    'title' => 'Modal',
    'resource' => 'item',
    'size' => 'md', // sm, md, lg, xl, 2xl, 3xl, 4xl
    'fields' => [],
])

@php
    $sizeClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="fixed inset-0 z-[99999]" id="{{ $id }}" style="display: none;">
    <!-- Backdrop -->
    <div class="absolute inset-0 backdrop-blur-sm" onclick="closeModal('{{ $id }}')"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-[99999] flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-lg p-6 w-full {{ $sizeClass }} max-h-[90vh] overflow-y-auto dark:bg-gray-800">
            <button onclick="closeModal('{{ $id }}')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-6 dark:text-white" id="{{ $id }}Title">{{ $title }}</h2>

            <form id="{{ $id }}Form" class="space-y-4">
                {{ $slot }}

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" id="{{ $id }}SubmitBtn">Criar</button>
                    <button type="button" onclick="closeModal('{{ $id }}')" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-white">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize modal state if not already exists
        if (!window.modalStates) {
            window.modalStates = {};
        }
        if (!window.modalStates['{{ $id }}']) {
            window.modalStates['{{ $id }}'] = {
                isEditing: false,
                currentId: null,
                resourceName: '{{ $resource }}',
            };
        }

        // Generic modal functions
        window.openModal = function(id, isEditing = false, data = null) {
            const state = window.modalStates[id];
            const form = document.getElementById(id + 'Form');
            const title = document.getElementById(id + 'Title');
            const submitBtn = document.getElementById(id + 'SubmitBtn');

            if (isEditing && data) {
                state.isEditing = true;
                state.currentId = data.id;
                title.textContent = title.textContent.replace('Criar', 'Editar');
                submitBtn.textContent = 'Atualizar';

                // Populate form fields with data
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = data[key] ? true : false;
                        } else {
                            input.value = data[key] || '';
                        }
                    }
                });

                // Show fields that are only for editing
                form.querySelectorAll('[data-edit-only]').forEach(el => {
                    el.style.display = 'block';
                });
            } else {
                state.isEditing = false;
                state.currentId = null;
                const originalTitle = title.getAttribute('data-create-title') || title.textContent;
                title.textContent = originalTitle;
                submitBtn.textContent = 'Criar';
                form.reset();

                // Hide fields that are only for editing
                form.querySelectorAll('[data-edit-only]').forEach(el => {
                    el.style.display = 'none';
                });
            }

            // Clear errors
            form.querySelectorAll('[id$="Error"]').forEach(el => {
                el.textContent = '';
            });

            document.getElementById(id).style.display = 'flex';
        };

        window.closeModal = function(id) {
            document.getElementById(id).style.display = 'none';
            window.modalStates[id] = {
                isEditing: false,
                currentId: null,
                resourceName: window.modalStates[id].resourceName,
            };
        };

        window.submitModalForm = async function(id, onSuccess = null) {
            const state = window.modalStates[id];
            const form = document.getElementById(id + 'Form');
            const url = state.isEditing
                ? `/${state.resourceName}/${state.currentId}`
                : `/${state.resourceName}`;

            const method = state.isEditing ? 'PUT' : 'POST';
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(key => {
                            const errorEl = document.getElementById(id + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
                            if (errorEl) {
                                errorEl.textContent = errorData.errors[key][0];
                            }
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: errorData.message || 'Erro ao processar o formulário',
                    });
                    return;
                }

                window.closeModal(id);

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: state.isEditing ? 'Registro atualizado com sucesso!' : 'Registro criado com sucesso!',
                }).then(() => {
                    if (onSuccess) {
                        onSuccess();
                    } else {
                        window.location.reload();
                    }
                });
            } catch (error) {
                console.error('Erro:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao processar o formulário',
                });
            }
        };

        // Form submit handler
        document.getElementById('{{ $id }}Form').addEventListener('submit', function(e) {
            e.preventDefault();
            window.submitModalForm('{{ $id }}');
        });
    </script>
</div>
