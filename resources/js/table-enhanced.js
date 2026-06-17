function createTableComponent(resourceName) {
    return {
        selectedRows: [],
        selectAll: false,
        resourceName: resourceName,
        handleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                document.querySelectorAll('[data-row-id]').forEach(el => {
                    const id = parseInt(el.dataset.rowId);
                    if (!this.selectedRows.includes(id)) {
                        this.selectedRows.push(id);
                    }
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
                alert('Selecione pelo menos um item');
                return;
            }
            if (!confirm('Tem certeza que deseja deletar os itens selecionados?')) {
                return;
            }

            const formData = new FormData();
            formData.append('ids', JSON.stringify(this.selectedRows));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/${this.resourceName}/delete-multiple`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.reload();
                  } else {
                      alert('Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'));
                  }
              });
        }
    };
}

// Expor globalmente
window.createTableComponent = createTableComponent;

export default createTableComponent;
