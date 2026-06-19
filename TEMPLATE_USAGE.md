# Template CRUD Modal - Guia de Uso

## Componente Criado

Arquivo: `resources/views/components/crud-modal.blade.php`

Um componente Blade reutilizável que encapsula toda a estrutura do modal com:
- Header com título e botão fechar
- Body com scroll automático
- Footer com botões Cancelar e Salvar
- Suporte a dark mode
- Z-index correto

---

## 📖 Como Usar

### Exemplo Básico

```blade
<!-- Botão para abrir -->
<button onclick="openCreateModal()" class="...">+ Novo Item</button>

<!-- Modal usando o componente -->
<x-crud-modal 
    id="itemModal"
    title="Criar Novo Item"
    formId="itemForm"
    submitText="Criar"
>
    <div>
        <label class="block text-sm font-medium mb-2">Nome</label>
        <input type="text" name="nome" class="w-full px-3 py-2 border rounded-lg" required>
    </div>
    
    <div>
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg">
    </div>
    
    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="ativo" checked>
            <span class="text-sm">Ativo</span>
        </label>
    </div>
</x-crud-modal>

<!-- Script -->
<script>
let itemEditId = null;

function openCreateModal() {
    itemEditId = null;
    document.getElementById('itemForm').reset();
    document.getElementById('itemModalTitle').textContent = 'Criar Novo Item';
    document.getElementById('itemModal').classList.remove('hidden');
}

async function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('itemForm'));
    const data = Object.fromEntries(formData);
    
    const response = await fetch(
        itemEditId ? `/items/${itemEditId}` : `/items`,
        {
            method: itemEditId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data)
        }
    );
    
    const result = await response.json();
    if (result.success) {
        document.getElementById('itemModal').classList.add('hidden');
        location.reload();
    } else {
        alert(result.message);
    }
}
</script>
```

---

## 🎨 Props Disponíveis

| Prop | Tipo | Default | Descrição |
|------|------|---------|-----------|
| `id` | string | `crudModal` | ID do modal (deve ser único) |
| `title` | string | `Modal` | Título do modal |
| `formId` | string | `crudForm` | ID do formulário |
| `submitText` | string | `Salvar` | Texto do botão submit |

---

## 💡 Exemplo Completo - Tipo de Eventos

```blade
<!-- resources/views/tipo-eventos/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tipos de Eventos</h1>
        @can('create', App\Models\TipoEvento::class)
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                + Novo Tipo
            </button>
        @endcan
    </div>

    <!-- Tabela Desktop -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full">
            <thead class="border-b">
                <tr>
                    <th class="text-left py-2 px-4">Nome</th>
                    <th class="text-left py-2 px-4">Status</th>
                    <th class="text-left py-2 px-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipos as $tipo)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-3 px-4">{{ $tipo->nome }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-sm {{ $tipo->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 flex gap-2">
                            @can('update', $tipo)
                                <button onclick="handleEdit(this)" data-id="{{ $tipo->id }}" data-nome="{{ $tipo->nome }}" data-ativo="{{ $tipo->ativo ? 1 : 0 }}" class="text-blue-600 hover:text-blue-800">✏️ Editar</button>
                            @endcan
                            @can('delete', $tipo)
                                <button onclick="confirmDelete({{ $tipo->id }})" class="text-red-600 hover:text-red-800">🗑️ Deletar</button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Cards Mobile -->
    <div class="md:hidden space-y-4">
        @foreach($tipos as $tipo)
            <div class="border rounded-lg p-4 dark:border-gray-700">
                <h3 class="font-bold mb-2">{{ $tipo->nome }}</h3>
                <p class="text-sm text-gray-600 mb-3">
                    <span class="px-2 py-1 rounded text-xs {{ $tipo->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </p>
                <div class="flex gap-2">
                    @can('update', $tipo)
                        <button onclick="handleEdit(this)" data-id="{{ $tipo->id }}" data-nome="{{ $tipo->nome }}" data-ativo="{{ $tipo->ativo ? 1 : 0 }}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm">✏️ Editar</button>
                    @endcan
                    @can('delete', $tipo)
                        <button onclick="confirmDelete({{ $tipo->id }})" class="flex-1 px-3 py-2 bg-red-600 text-white rounded text-sm">🗑️ Deletar</button>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal usando Template -->
<x-crud-modal 
    id="tipoModal"
    title="Criar Novo Tipo de Evento"
    formId="tipoForm"
    submitText="Criar"
>
    <div>
        <label class="block text-sm font-medium mb-2 dark:text-gray-200">Nome</label>
        <input 
            type="text" 
            name="nome" 
            id="tipoModalnome"
            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            required
        >
    </div>

    <div>
        <label class="flex items-center gap-2 dark:text-gray-200">
            <input type="checkbox" name="ativo" id="tipoModalativo" checked class="rounded dark:bg-gray-700">
            <span class="text-sm">Ativo</span>
        </label>
    </div>
</x-crud-modal>

<script>
let tipoEditId = null;

function openCreateModal() {
    tipoEditId = null;
    document.getElementById('tipoForm').reset();
    document.getElementById('tipoModalTitle').textContent = 'Criar Novo Tipo de Evento';
    document.getElementById('tipoModal').classList.remove('hidden');
}

function handleEdit(button) {
    tipoEditId = button.dataset.id;
    document.getElementById('tipoModalnome').value = button.dataset.nome;
    document.getElementById('tipoModalativo').checked = button.dataset.ativo == 1;
    document.getElementById('tipoModalTitle').textContent = 'Editar Tipo de Evento';
    document.getElementById('tipoModal').classList.remove('hidden');
}

document.getElementById('tipoForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.ativo = document.getElementById('tipoModalativo').checked ? 1 : 0;

    const response = await fetch(
        tipoEditId ? `/tipo-eventos/${tipoEditId}` : `/tipo-eventos`,
        {
            method: tipoEditId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data)
        }
    );

    const result = await response.json();
    if (result.success) {
        document.getElementById('tipoModal').classList.add('hidden');
        location.reload();
    } else {
        alert(result.message);
    }
});

function confirmDelete(id) {
    if (confirm('Tem certeza que deseja deletar?')) {
        fetch(`/tipo-eventos/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(() => location.reload());
    }
}
</script>
@endsection
```

---

## 🚀 Vantagens

✅ **DRY (Don't Repeat Yourself)** - Escreva o modal uma vez, use em 10 telas
✅ **Consistência** - Todos os modais têm a mesma aparência
✅ **Manutenção Fácil** - Mudança no modal afeta todas as telas
✅ **Dark Mode** - Já incluído
✅ **Responsivo** - Já otimizado para mobile
✅ **Acessibilidade** - Estrutura semântica

---

## 📝 Checklist para Usar

Para cada tela nova:

- [ ] Substitua `id`, `title`, `formId`, `submitText` apropriados
- [ ] Adicione os campos que precisa dentro do slot
- [ ] Crie funções JavaScript: `openCreateModal()`, `handleEdit()`, submit handler
- [ ] Use data attributes nos botões de editar
- [ ] Aplique `@can` para permissões
- [ ] Teste em desktop e mobile

---

## ⚡ Resumo

Em vez de copiar/colar centenas de linhas de HTML do modal em cada view, agora você só precisa:

```blade
<x-crud-modal id="minhaModal" title="Meu Modal" formId="meuForm">
    <!-- Seus campos aqui -->
</x-crud-modal>
```

Muito mais limpo! 🎉
