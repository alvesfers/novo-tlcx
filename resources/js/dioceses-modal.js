export function dioceseModalManager() {
    return {
        showModal: false,
        isEditing: false,
        form: {
            nome: '',
            email: '',
            ativo: false,
        },
        errors: {},
        currentDioceseId: null,

        openCreateModal() {
            this.resetForm();
            this.isEditing = false;
            this.showModal = true;
        },

        openEditModal(diocese) {
            this.form.nome = diocese.nome;
            this.form.email = diocese.email || '';
            this.form.ativo = diocese.ativo ? true : false;
            this.currentDioceseId = diocese.id;
            this.isEditing = true;
            this.showModal = true;
            this.errors = {};
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        },

        resetForm() {
            this.form = {
                nome: '',
                email: '',
                ativo: false,
            };
            this.errors = {};
            this.currentDioceseId = null;
        },

        async submitForm() {
            const url = this.isEditing
                ? `/dioceses/${this.currentDioceseId}`
                : '/dioceses';

            const method = this.isEditing ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(this.form),
                });

                if (!response.ok) {
                    const data = await response.json();
                    if (data.errors) {
                        this.errors = data.errors;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Erro ao processar o formulário',
                    });
                    return;
                }

                const data = await response.json();
                this.closeModal();

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: this.isEditing ? 'Diocese atualizada com sucesso!' : 'Diocese criada com sucesso!',
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
        },
    };
}
