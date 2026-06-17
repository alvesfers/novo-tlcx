@props(['diocese' => null])

<div class="fixed inset-0 z-50" id="dioceseModal" style="display: none;" x-cloak>
    <!-- Backdrop -->
    <div class="absolute inset-0 backdrop-blur-sm" onclick="closeDioceseModal()"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto dark:bg-gray-800">
            <button onclick="closeDioceseModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-6 dark:text-white" id="dioceseModalTitle">Criar Nova Diocese</h2>

            <form id="dioceseForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
                    <input
                        type="text"
                        name="nome"
                        id="dioceseName"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                    >
                    <span class="text-red-500 text-sm" id="nomeError"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="dioceseEmail"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                    <span class="text-red-500 text-sm" id="emailError"></span>
                </div>

                <div id="ativoDiv" style="display: none;">
                    <label class="block text-sm font-medium mb-2 dark:text-gray-200">Ativo</label>
                    <label class="flex items-center dark:text-gray-200">
                        <input type="checkbox" name="ativo" id="dioceseAtivo" value="1" class="rounded dark:bg-gray-700">
                        <span class="ml-2">Diocese ativa no sistema</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" id="dioceseSubmitBtn">Criar</button>
                    <button type="button" onclick="closeDioceseModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-white">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let dioceseModalState = {
            isEditing: false,
            currentDioceseId: null,
        };

        function openDioceseCreateModal() {
            dioceseModalState.isEditing = false;
            dioceseModalState.currentDioceseId = null;
            document.getElementById('dioceseModalTitle').textContent = 'Criar Nova Diocese';
            document.getElementById('dioceseName').value = '';
            document.getElementById('dioceseEmail').value = '';
            document.getElementById('dioceseAtivo').checked = false;
            document.getElementById('ativoDiv').style.display = 'none';
            document.getElementById('dioceseSubmitBtn').textContent = 'Criar';
            clearDioceseErrors();
            document.getElementById('dioceseModal').style.display = 'flex';
        }

        function openDioceseEditModal(diocese) {
            dioceseModalState.isEditing = true;
            dioceseModalState.currentDioceseId = diocese.id;
            document.getElementById('dioceseModalTitle').textContent = 'Editar Diocese';
            document.getElementById('dioceseName').value = diocese.nome;
            document.getElementById('dioceseEmail').value = diocese.email || '';
            document.getElementById('dioceseAtivo').checked = diocese.ativo ? true : false;
            document.getElementById('ativoDiv').style.display = 'block';
            document.getElementById('dioceseSubmitBtn').textContent = 'Atualizar';
            clearDioceseErrors();
            document.getElementById('dioceseModal').style.display = 'flex';
        }

        function closeDioceseModal() {
            document.getElementById('dioceseModal').style.display = 'none';
        }

        function clearDioceseErrors() {
            document.getElementById('nomeError').textContent = '';
            document.getElementById('emailError').textContent = '';
        }

        document.getElementById('dioceseForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const url = dioceseModalState.isEditing
                ? `/dioceses/${dioceseModalState.currentDioceseId}`
                : '/dioceses';

            const method = dioceseModalState.isEditing ? 'PUT' : 'POST';

            const formData = {
                nome: document.getElementById('dioceseName').value,
                email: document.getElementById('dioceseEmail').value,
                ativo: document.getElementById('dioceseAtivo').checked ? 1 : 0,
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(formData),
                });

                if (!response.ok) {
                    const data = await response.json();
                    if (data.errors) {
                        if (data.errors.nome) {
                            document.getElementById('nomeError').textContent = data.errors.nome[0];
                        }
                        if (data.errors.email) {
                            document.getElementById('emailError').textContent = data.errors.email[0];
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Erro ao processar o formulário',
                    });
                    return;
                }

                closeDioceseModal();

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: dioceseModalState.isEditing ? 'Diocese atualizada com sucesso!' : 'Diocese criada com sucesso!',
                }).then(() => {
                    window.location.reload();
                });
            } catch (error) {
                console.error('Erro:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao processar o formulário',
                });
            }
        });
    </script>
</div>
