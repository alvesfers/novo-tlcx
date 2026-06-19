// Generic manager for all entity types
function createEntityManager(entityType) {
    return {
        selectedRows: [],
        selectAll: false,
        entityType: entityType,

        handleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                document.querySelectorAll('[data-row-id]').forEach(el => {
                    const id = parseInt(el.dataset.rowId);
                    if (!this.selectedRows.includes(id)) this.selectedRows.push(id);
                });
            } else {
                this.selectedRows = [];
            }
        },

        handleRowSelect(id) {
            if (this.selectedRows.includes(id)) {
                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
            } else {
                this.selectedRows.push(id);
            }
        },

        deleteSelected() {
            if (this.selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Selecione pelo menos um item',
                });
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Confirmar exclusão',
                text: `Tem certeza que deseja deletar ${this.selectedRows.length} item(ns) selecionado(s)?`,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Deletar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('ids', JSON.stringify(this.selectedRows));
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    fetch(`/${this.entityType}/delete-multiple`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Sucesso!',
                                  text: 'Itens deletados com sucesso!',
                              }).then(() => window.location.reload());
                          } else {
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Erro',
                                  text: 'Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'),
                              });
                          }
                      });
                }
            });
        },

        deleteItem(id, event) {
            event.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Confirmar exclusão',
                text: 'Tem certeza que deseja deletar este item?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Deletar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    };
}

// Global Alpine managers
window.diocesesManager = function() {
    return createEntityManager('dioceses');
};

window.nucleosManager = function() {
    return createEntityManager('nucleos');
};

window.secretariasManager = function() {
    return createEntityManager('secretarias');
};

window.dirigentesManager = function() {
    return createEntityManager('dirigentes');
};

// Global info modal function
window.openInfoModal = function(type, id, name) {
    const modal = document.getElementById('infoModal');
    const modalTitle = modal.querySelector('h2');
    const modalContent = document.getElementById('infoModalContent');

    modalTitle.textContent = `Informações: ${name}`;

    if (type === 'diocese' || type === 'nucleo' || type === 'secretaria') {
        const endpoint = `/${type === 'nucleo' ? 'nucleos' : type === 'secretaria' ? 'secretarias' : 'dioceses'}/${id}/info`;

        modalContent.innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Coordenadores Atuais</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Carregando...</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Próximos Eventos (3)</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Carregando...</p>
                </div>
            </div>
        `;

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                let coordenadores = '<ul class="list-disc list-inside space-y-1">';
                if (data.coordenadores && data.coordenadores.length) {
                    data.coordenadores.forEach(c => {
                        coordenadores += `<li class="text-sm text-gray-600 dark:text-gray-400">${c.nome}</li>`;
                    });
                } else {
                    coordenadores += '<li class="text-sm text-gray-600 dark:text-gray-400">Nenhum coordenador</li>';
                }
                coordenadores += '</ul>';

                let eventos = '<ul class="list-disc list-inside space-y-1">';
                if (data.eventos && data.eventos.length) {
                    data.eventos.forEach(e => {
                        eventos += `<li class="text-sm text-gray-600 dark:text-gray-400">${e.nome} - ${e.data}</li>`;
                    });
                } else {
                    eventos += '<li class="text-sm text-gray-600 dark:text-gray-400">Nenhum evento</li>';
                }
                eventos += '</ul>';

                modalContent.innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Coordenadores Atuais</h4>
                            ${coordenadores}
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Próximos Eventos (3)</h4>
                            ${eventos}
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                modalContent.innerHTML = '<p class="text-sm text-red-600">Erro ao carregar informações</p>';
            });
    } else if (type === 'dirigente') {
        modalContent.innerHTML = `
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Carregando...</p>
            </div>
        `;

        fetch(`/dirigentes/${id}/info`)
            .then(response => response.json())
            .then(data => {
                let nucleos = '<ul class="list-disc list-inside space-y-1">';
                if (data.nucleos && data.nucleos.length) {
                    data.nucleos.forEach(n => {
                        nucleos += `<li class="text-sm text-gray-600 dark:text-gray-400">${n.nome}</li>`;
                    });
                } else {
                    nucleos += '<li class="text-sm text-gray-600 dark:text-gray-400">Nenhum núcleo</li>';
                }
                nucleos += '</ul>';

                let secretarias = '<ul class="list-disc list-inside space-y-1">';
                if (data.secretarias && data.secretarias.length) {
                    data.secretarias.forEach(s => {
                        secretarias += `<li class="text-sm text-gray-600 dark:text-gray-400">${s.nome}</li>`;
                    });
                } else {
                    secretarias += '<li class="text-sm text-gray-600 dark:text-gray-400">Nenhuma secretaria</li>';
                }
                secretarias += '</ul>';

                let habilidades = '<ul class="list-disc list-inside space-y-1">';
                if (data.habilidades && data.habilidades.length) {
                    data.habilidades.forEach(h => {
                        habilidades += `<li class="text-sm text-gray-600 dark:text-gray-400">${h.nome}</li>`;
                    });
                } else {
                    habilidades += '<li class="text-sm text-gray-600 dark:text-gray-400">Nenhuma habilidade</li>';
                }
                habilidades += '</ul>';

                modalContent.innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Núcleos</h4>
                            ${nucleos}
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secretarias</h4>
                            ${secretarias}
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Habilidades</h4>
                            ${habilidades}
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                modalContent.innerHTML = '<p class="text-sm text-red-600">Erro ao carregar informações</p>';
            });
    }

    document.getElementById('infoModal').classList.remove('hidden');
};
