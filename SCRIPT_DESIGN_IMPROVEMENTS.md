# Script de Design/UX/UI - Melhorias de Telas Administrativas

---

## ⚡ Biblioteca Pré-Instalada

✅ **SweetAlert2** - Já instalado e pronto para usar!
- Importado em `resources/js/app.js`
- Disponível globalmente como `window.Swal`
- Basta usar `Swal.fire({...})` em qualquer arquivo

Não precisa fazer setup, CDN ou instalar nada - **já está tudo pronto!**

---

## 📐 Princípios de Design (do PRODUCT.md)

Toda nova tela deve seguir os 5 princípios do TLC Admin:

1. **Respect Permission Boundaries** - Nunca mostrar ações que o usuário não pode fazer
2. **Clarity Through Context** - Informações críticas em 1-2 interações (modals de info)
3. **Mobile-First Responsiveness** - Funciona perfeitamente em mobile, tablet, desktop
4. **Hierarchical Clarity** - Distinguir visualmente entidades que usuário gerencia vs. apenas vê
5. **Minimal Cognitive Load** - Ação primária clara (criar/editar), secundárias (ver/deletar)

---

## 📋 PROMPT PARA CLAUDE - Versão Completa

```
Você vai melhorar a tela de [NOME_DA_TELA] seguindo os 5 princípios de design do TLC Admin (PRODUCT.md) E o padrão técnico implementado em dioceses, núcleos, secretarias e dirigentes.

## Contexto do Produto

**Brand Personality**: Professional, efficient, trustworthy. TailAdmin style: clean, modern, corporate.
**Tone**: Serious tool para serious work - sem decoração desnecessária, mas não intimidador.
**Success Criteria**: Users trust the permission system, never see disabled actions, find critical information in 1-2 interactions.
**Light mode preferred** (dark mode suportado, mas light é default)

---

## Princípio 1: Respect Permission Boundaries

NUNCA mostrar botões desabilitados. Em vez disso:
- Esconda botões que usuário não pode usar com @can()
- Botão "Novo" só aparece se @can('create')
- Botão "Editar" só aparece se @can('update', $item)
- Botão "Deletar" só aparece se @can('delete', $item)
- Checkboxes de multi-delete só aparecem se @can('deleteMultiple')

Resultado: Interface adapta-se ao usuário. Ele vê apenas o que pode fazer. Confiança.

---

## Princípio 2: Clarity Through Context

Informações críticas RÁPIDO. Use modals de info para:
- **Dioceses/Núcleos**: Coordenadores atuais, Próximos 3 eventos
- **Dirigentes**: Quais nucleos/secretarias estão vinculados, Habilidades
- Qualquer entidade: Status, Data de criação, Responsável

Pattern:
```
Tabela → Clica em (i) Info → Modal com informações críticas → Fechado
```

Nunca force o usuário a:
- Clicar em "Ver detalhes" → outra página → nova página
- Procurar informações em várias seções
- Scrollar muito para achar dados simples

---

## Princípio 3: Mobile-First Responsiveness

**Desktop (≥768px)**: Tabela com colunas horizontais
**Tablet/Mobile (<768px)**: Cards verticais com mesmas informações

Checklist mobile:
- [ ] Todas as colunas aparecem no card
- [ ] Botões de ação ocupam full width ou grid 2 colunas
- [ ] Status/badges são legíveis em mobile
- [ ] Modal não fica maior que viewport
- [ ] Form fields têm touch-friendly height (min 44px)

Dark mode:
- Use dark:classes do Tailwind
- Teste light E dark mode antes de finalizar
- Sem forçar dark mode como default

---

## Princípio 4: Hierarchical Clarity

Usuários precisam entender o escopo deles. Exemplos:

**Diocese User vendo secretarias**:
- Secretarias que ela gerencia: Normal
- Secretarias de outra diocese: Desabilitadas ou sem ações

**Nucleo User vendo dirigentes**:
- Dirigentes do seu nucleo: Pode editar
- Dirigentes de outro nucleo: Apenas visualizar
- Dirigentes sem vinculo: Não aparecem ou cinzento

Use:
- Indentação visual
- Badges ("Meu", "Externo")
- Estados desabilitados (com cor cinza)
- Separadores visuais

---

## Princípio 5: Minimal Cognitive Load

Cada página = 1 ação primária + n ações secundárias

**Ação Primária** (botão grande, destaque):
- "+ Novo [Entidade]" - cria item novo

**Ações Secundárias** (tabela/card):
- 👁️ Info - visualizar detalhes
- ✏️ Editar - editar item
- 🗑️ Deletar - deletar item

Padronização:
- Icons iguais em todas as telas
- Posição igual em todas as telas
- Cores iguais: azul=view/edit, vermelho=delete
- Ordem igual: sempre info → editar → deletar

---

## 🎨 Sistema de Ícones Padronizado

**IMPORTANTE**: Use os ícones abaixo em TODAS as telas para manter consistência.

### Ícones e Cores (Padrão)

| Ação | Ícone | Cor | Uso | CSS |
|------|-------|-----|-----|-----|
| **Visualizar/Info** | 👁️ (emoji) ou eye SVG | Azul (`#2563eb`) | Clique abre modal com informações | `text-blue-600 hover:text-blue-800` |
| **Editar** | ✏️ (emoji) ou pencil SVG | Azul (`#2563eb`) | Clique abre modal de edição | `text-blue-600 hover:text-blue-800` |
| **Deletar** | 🗑️ (emoji) ou trash SVG | Vermelho (`#dc2626`) | Clique pede confirmação | `text-red-600 hover:text-red-800` |
| **Criar** | + (emoji) ou plus SVG | Verde (`#16a34a`) | Botão "Novo [Entidade]" | `bg-green-600 hover:bg-green-700` |
| **Salvar** | ✓ (checkmark) ou check SVG | Azul | Dentro do modal | `bg-blue-600 hover:bg-blue-700` |
| **Cancelar** | × (close) ou X SVG | Cinza | Dentro do modal | `border rounded-lg hover:bg-gray-100` |
| **Deletar Selecionados** | 🗑️ | Vermelho | Botão em tabela header | `border border-red-300 text-red-600` |

### Implementação: Emojis vs SVGs

**Escolha UMA abordagem e use em TODA a aplicação:**

#### Opção A: Emojis (Mais Simples)
```html
<button onclick="..." title="Visualizar">👁️ Info</button>
<button onclick="..." title="Editar">✏️ Editar</button>
<button onclick="..." title="Deletar">🗑️ Deletar</button>
<button onclick="..." class="...">+ Novo Item</button>
```
✅ Simples, rápido, universalmente compatível
❌ Tamanho e alinhamento inconsistente em alguns browsers

#### Opção B: SVGs (Recomendado)
```html
<button onclick="..." title="Visualizar" class="w-8 h-8 flex items-center justify-center">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
</button>
```
✅ Consistente, redimensionável, pode animar
✅ Mais profissional (brand TailAdmin)
❌ Mais código

**RECOMENDAÇÃO**: Use SVGs para desktop/tablet, emojis para mobile (melhor performance)

### SVG Icons Reference

**Eye (Visualizar)**:
```svg
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>
```

**Pencil (Editar)**:
```svg
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
</svg>
```

**Trash (Deletar)**:
```svg
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
</svg>
```

**Plus (Criar)**:
```svg
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
</svg>
```

**Check (Salvar/Confirmação)**:
```svg
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
</svg>
```

**Close (Cancelar)**:
```svg
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
</svg>
```

### Padrão de Botões com Ícones (Desktop)

```html
<!-- Em tabela/cards - Icon only -->
<button class="w-8 h-8 flex items-center justify-center rounded hover:bg-blue-50 text-blue-600 hover:text-blue-800">
    👁️ <!-- ou SVG -->
</button>

<!-- Em header - Com texto -->
<button class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3">
    <svg class="w-5 h-5" ...>...</svg>
    Nova Entidade
</button>
```

### Padrão de Botões com Ícones (Mobile)

```html
<!-- Em cards - Full width com texto -->
<button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm font-medium">
    👁️ Info
</button>

<button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm font-medium">
    ✏️ Editar
</button>

<button class="flex-1 px-3 py-2 bg-red-600 text-white rounded text-sm font-medium">
    🗑️ Deletar
</button>
```

### Checklist de Ícones

Para TODA tela nova, verifique:

- [ ] **Info Button**: Azul, sempre oferece visualização rápida
- [ ] **Edit Button**: Azul, aparece apenas se @can('update')
- [ ] **Delete Button**: Vermelho, aparece apenas if @can('delete')
- [ ] **New Button**: Verde, header, aparece apenas if @can('create')
- [ ] **Desktop**: Ícones apenas (emoji ou SVG pequeno)
- [ ] **Mobile**: Ícones + texto, full width
- [ ] **Dark Mode**: Ícones/cores funcionam em dark mode também
- [ ] **Hover States**: Todos têm hover de cor/fundo
- [ ] **Order**: Sempre Info → Edit → Delete (consistente)
- [ ] **Consistency**: EXATAMENTE iguais em todas as telas

---

## Estrutura Técnica (Componentes + JavaScript)

Toda tela deve ter:

### 1. **Modal de Criar/Editar** (usando <x-crud-modal>)

```blade
<x-crud-modal 
    id="[nomeModal]"
    title="Criar Novo [Entidade]"
    formId="[nomeForm]"
    submitText="Criar"
>
    <!-- Campos aqui -->
</x-crud-modal>
```

### 2. **Modal de Informações** (usando <x-info-modal>)

```blade
<x-info-modal id="infoModal"></x-info-modal>
```

Preenchido dinamicamente via fetch de `/[rota]/{id}/info`

### 3. **Tabela Desktop** (≥768px)

```blade
<div class="hidden md:block">
    <table>
        <thead>
            <tr>
                @can('deleteMultiple')
                    <th><input type="checkbox" onclick="handleSelectAll()"></th>
                @endcan
                <th>Nome</th>
                <th>Email</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr data-row-id="{{ $item->id }}">
                    @can('deleteMultiple')
                        <td><input type="checkbox" onchange="handleRowSelect({{ $item->id }})"></td>
                    @endcan
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-sm {{ $item->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $item->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="flex gap-2">
                        @can('view', $item)
                            <button onclick="openInfoModal('item', {{ $item->id }}, '{{ $item->nome }}')" title="Visualizar" class="w-8 h-8 flex items-center justify-center rounded hover:bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        @endcan
                        @can('update', $item)
                            <button onclick="handle[Nomeacao](this)" data-id="{{ $item->id }}" data-nome="{{ $item->nome }}" data-email="{{ $item->email ?? '' }}" data-ativo="{{ $item->ativo ? 1 : 0 }}" title="Editar" class="w-8 h-8 flex items-center justify-center rounded hover:bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        @endcan
                        @can('delete', $item)
                            <button onclick="confirmDelete({{ $item->id }})" title="Deletar" class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

### 4. **Cards Mobile** (<768px)

```blade
<div class="md:hidden space-y-4">
    @foreach($items as $item)
        <div class="border rounded-lg p-4 dark:border-gray-700">
            <h3 class="font-bold mb-2">{{ $item->nome }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $item->email }}</p>
            <span class="inline-block px-2 py-1 rounded text-xs mb-4 {{ $item->ativo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $item->ativo ? 'Ativo' : 'Inativo' }}
            </span>
            <div class="flex gap-2">
                @can('view', $item)
                    <button onclick="openInfoModal('item', {{ $item->id }}, '{{ $item->nome }}')" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm font-medium">👁️ Info</button>
                @endcan
                @can('update', $item)
                    <button onclick="handle[Nomeacao](this)" data-id="{{ $item->id }}" data-nome="{{ $item->nome }}" data-email="{{ $item->email ?? '' }}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm font-medium">✏️ Editar</button>
                @endcan
                @can('delete', $item)
                    <button onclick="confirmDelete({{ $item->id }})" class="flex-1 px-3 py-2 bg-red-600 text-white rounded text-sm font-medium">🗑️ Deletar</button>
                @endcan
            </div>
        </div>
    @endforeach
</div>
```

### 5. **JavaScript Functions**

```javascript
let [nomeEntidade]EditId = null;

function open[Nomeacao]Modal() {
    [nomeEntidade]EditId = null;
    document.getElementById('[nomeForm]').reset();
    
    const titleEl = document.getElementById('[nomeModal]Title');
    if (titleEl) titleEl.textContent = 'Criar Novo [Entidade]';
    
    document.getElementById('[nomeModal]').classList.remove('hidden');
}

function handle[Nomeacao](button) {
    [nomeEntidade]EditId = button.dataset.id;
    
    // Populate form
    const nomeEl = document.getElementById('[nomeModal]nome');
    const emailEl = document.getElementById('[nomeModal]email');
    const ativoEl = document.getElementById('[nomeModal]ativo');
    
    if (nomeEl) nomeEl.value = button.dataset.nome;
    if (emailEl) emailEl.value = button.dataset.email || '';
    if (ativoEl) ativoEl.checked = button.dataset.ativo == 1;
    
    const titleEl = document.getElementById('[nomeModal]Title');
    if (titleEl) titleEl.textContent = 'Editar [Entidade]';
    
    document.getElementById('[nomeModal]').classList.remove('hidden');
}

document.getElementById('[nomeForm]').addEventListener('submit', async function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    const response = await fetch(
        [nomeEntidade]EditId 
            ? \`/[rota]/\${[nomeEntidade]EditId}\`
            : '/[rota]',
        {
            method: [nomeEntidade]EditId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data)
        }
    );
    
    const result = await response.json();
    if (result.success) {
        document.getElementById('[nomeModal]').classList.add('hidden');
        location.reload();
    } else {
        alert(result.message || 'Erro ao salvar');
    }
});

function confirmDelete(id) {
    if (confirm('Tem certeza que deseja deletar?')) {
        fetch(\`/[rota]/\${id}\`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(() => location.reload());
    }
}

// Multi-select
let selectedRows = [];

function handleSelectAll() {
    const checkboxes = document.querySelectorAll('[data-row-id] input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function handleRowSelect(id) {
    if (selectedRows.includes(id)) {
        selectedRows = selectedRows.filter(rid => rid !== id);
    } else {
        selectedRows.push(id);
    }
}

async function deleteSelected() {
    if (selectedRows.length === 0) {
        alert('Selecione itens para deletar');
        return;
    }
    
    if (!confirm(\`Deletar \${selectedRows.length} item(ns)?\`)) return;
    
    const response = await fetch('/[rota]/delete-multiple', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ids: selectedRows })
    });
    
    const result = await response.json();
    if (result.success) location.reload();
}
```

---

## ✅ Checklist Completo

### Design Principles
- [ ] **Permissões**: Botões aparecem apenas se @can() autoriza
- [ ] **Info Modal**: Abre em 1 clique, mostra dados críticos
- [ ] **Mobile**: Cards em mobile, tabela em desktop
- [ ] **Hierarchy**: Visual clara de escopo (seu vs. externo)
- [ ] **Cognitive Load**: 1 ação primária, 3 secundárias máximo

### Technical
- [ ] Modal CRUD usando `<x-crud-modal>`
- [ ] Modal Info usando `<x-info-modal>`
- [ ] Desktop table com checkboxes @can
- [ ] Mobile cards com botões full-width
- [ ] Data attributes em botões editar
- [ ] Dark mode com dark: classes
- [ ] Icons padronizados (azul=view/edit, vermelho=delete)
- [ ] JavaScript functions (openModal, handleEdit, submit, delete)
- [ ] API endpoints (GET, POST, PUT, DELETE)
- [ ] Info endpoint retornando dados críticos

### Polish
- [ ] Tested em mobile, tablet, desktop
- [ ] Light e dark mode funcionando
- [ ] Buttons com hover states
- [ ] Modals com backdrop e scroll
- [ ] Error handling com user feedback

---

## 📄 Telas a Melhorar

Prioridade:
1. **Tipo de Eventos** (`/tipo-eventos`) - Simples, sem nested
2. **Eventos** (`/eventos`) - Mais complexo, com relacionamentos
3. Outras telas conforme necessidade

---

## 🎯 Resultado Esperado

Uma tela que:
- ✅ Respeita permissões (confia no usuário)
- ✅ Mostra informações críticas rapidamente (1-2 cliques)
- ✅ Funciona perfeito em mobile
- ✅ Deixa claro o que o usuário pode fazer
- ✅ Não sobrecarrega cognitivamente
- ✅ Segue o brand TailAdmin: profissional, limpo, moderno

```

---

## 🔍 Componentes Adicionais (Além do Modal)

### 1. **Filtros (OBRIGATÓRIO)**

Toda tela de listagem deve ter filtros para:
- **Search/Pesquisa** - Por nome, email, etc
- **Status** - Ativo/Inativo
- **Relacionamento** - Diocese, Núcleo, Secretaria (se aplicável)
- **Data** - Data de criação (opcional)

```blade
<!-- Filtros -->
<div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
    <div class="px-6 py-4">
        <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3">Filtros</h3>
        <form method="GET" action="{{ route('items.index') }}" class="flex gap-3 items-end flex-wrap">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Pesquisar</label>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Nome, email..."
                    value="{{ $searchQuery }}"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
            </div>
            
            <!-- Status -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Status</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Todos os status</option>
                    <option value="ativo" {{ $selectedStatus === 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ $selectedStatus === 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <!-- Related Entity (se aplicável) -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium mb-2 dark:text-gray-200">Diocese</label>
                <select name="diocese_id" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Todas</option>
                    @foreach($dioceses as $diocese)
                        <option value="{{ $diocese->id }}" {{ request('diocese_id') == $diocese->id ? 'selected' : '' }}>
                            {{ $diocese->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Botões -->
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 font-medium hover:bg-blue-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filtrar
            </button>
            
            @if($selectedStatus || $searchQuery || request('diocese_id'))
                <a href="{{ route('items.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 text-gray-700 px-4 py-2 font-medium hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Limpar
                </a>
            @endif
        </form>
    </div>
</div>
```

### 2. **Success/Error Messages**

Flash messages do Laravel:

```blade
<!-- Success -->
@if ($message = Session::get('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/30 dark:border-green-700 dark:text-green-300">
        {{ $message }}
    </div>
@endif

<!-- Error -->
@if ($message = Session::get('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
        {{ $message }}
    </div>
@endif
```

### 3. **Empty State**

Quando nenhum registro existe:

```blade
@forelse($items as $item)
    <!-- Tabela ou Cards -->
@empty
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p class="text-gray-600 dark:text-gray-400 text-lg font-medium mb-2">Nenhum registro encontrado</p>
        <p class="text-gray-500 dark:text-gray-500 mb-4">Nenhum [entidade] criado ainda</p>
        @can('create', App\Models\Item::class)
            <button onclick="openCreateModal()" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 font-medium hover:bg-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Criar Primeiro
            </button>
        @endcan
    </div>
@endforelse
```

### 4. **Loading State (Modal)**

Enquanto carrega dados do servidor:

```javascript
// Ao submeter formulário
async function submitForm(event) {
    event.preventDefault();
    const submitBtn = document.querySelector('#[nomeForm] button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Salvando...';
    
    try {
        const response = await fetch(...);
        const result = await response.json();
        // sucesso
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}
```

### 5. **Validação de Formulários (Frontend)**

```javascript
function validate[Nomeacao]Form(data) {
    const errors = [];
    
    if (!data.nome || data.nome.trim() === '') {
        errors.push('Nome é obrigatório');
    }
    
    if (data.email && !isValidEmail(data.email)) {
        errors.push('Email inválido');
    }
    
    if (errors.length > 0) {
        alert(errors.join('\n'));
        return false;
    }
    
    return true;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
```

### 6. **Confirmação de Ações (SweetAlert - NÃO use alert/confirm!)**

❌ **NUNCA use `alert()` ou `confirm()`** - Feios, não combinam com design
✅ **Use SweetAlert2** - Elegante, profissional, consistente

**⚠️ IMPORTANTE**: SweetAlert já está instalado no projeto! Disponível globalmente como `window.Swal`

#### Padrão de Confirmação

```javascript
// ❌ RUIM
if (!confirm('Deletar?')) return;

// ✅ BOM
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Deletar?',
        text: `Tem certeza que deseja deletar "${name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, deletar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/[rota]/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Deletado!',
                    text: 'Registro foi deletado com sucesso.',
                    timer: 1500
                }).then(() => location.reload());
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao deletar o registro.'
                });
            });
        }
    });
}
```

#### Padrão de Erro

```javascript
// ❌ RUIM
alert('Selecione itens');

// ✅ BOM
async function deleteSelected() {
    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione pelo menos um item para deletar'
        });
        return;
    }
    
    // ... rest of code
}
```

#### Padrão de Sucesso

```javascript
// ❌ RUIM
alert('Salvo com sucesso!');

// ✅ BOM
Swal.fire({
    icon: 'success',
    title: 'Sucesso!',
    text: 'Registro salvo com sucesso.',
    timer: 2000
});
```

#### Padrão de Confirmação em Massa

```javascript
async function deleteSelected() {
    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione itens para deletar'
        });
        return;
    }
    
    Swal.fire({
        title: 'Deletar múltiplos itens?',
        text: `Você vai deletar ${selectedRows.length} item(ns). Esta ação não pode ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, deletar tudo',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deletando...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            
            try {
                const response = await fetch('/[rota]/delete-multiple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ids: selectedRows })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deletado!',
                        text: `${selectedRows.length} item(ns) foram deletados.`,
                        timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: result.message || 'Erro ao deletar registros'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro na requisição'
                });
            }
        }
    });
}
```

---

## ✅ 7. SweetAlert Patterns Padronizados

**Use SEMPRE estes padrões em TODAS as views. Veja FIX_SWEET_ALERTS.md para implementação completa.**

### Padrão 1: Confirmações (Delete Actions)

Todas as ações de delete devem usar este padrão:

```javascript
Swal.fire({
    title: 'Confirmar exclusão',
    text: 'Tem certeza que deseja deletar este item?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Deletar',
    cancelButtonText: 'Cancelar',
    allowOutsideClick: false,

}).then((result) => {
    if (result.isConfirmed) {
        // continue with delete
    }
});
```

### Padrão 2: Success Messages (Create/Update/Delete)

Após operações bem-sucedidas:

```javascript
Swal.fire({
    icon: 'success',
    title: 'Sucesso!',
    text: 'Entidade criada com sucesso!',
    showConfirmButton: false,
    timer: 1500,

}).then(() => {
    location.reload();
});
```

### Padrão 3: Error Handling

Ao retornar erro:

```javascript
Swal.fire({
    icon: 'error',
    title: 'Erro!',
    text: result.message || 'Erro ao salvar',

});
```

### Padrão 4: Validation/Warnings

Para validações e avisos:

```javascript
Swal.fire({
    icon: 'warning',
    title: 'Atenção',
    text: 'Selecione pelo menos um item',

});
```

### Tabela de Padrões SweetAlert

| Tipo | Icon | Titulo | showConfirmButton | timer | Z-Index | Cancelar |
|------|------|--------|------------------|-------|---------|----------|
| **Confirmação** | warning | "Confirmar exclusão" | true | - | 99999 | sim |
| **Sucesso** | success | "Sucesso!" | false | 1500 | 99999 | não |
| **Erro** | error | "Erro!" | true | - | 99999 | não |
| **Aviso** | warning | "Atenção" | true | - | 99999 | não |

### Cores Padrão

```javascript
// Delete/Destruir
confirmButtonColor: '#dc2626' // Vermelho TailwindCSS

// Cancelar (sempre)
cancelButtonColor: '#6b7280' // Cinza TailwindCSS

// Confirmar safe (Azul)
confirmButtonColor: '#2563eb' // Azul TailwindCSS
```

### ⚠️ IMPORTANTE: Z-Index Fix

Sempre adicione este código no `didOpen`:
```javascript
didOpen: () => {
    document.querySelector('.swal2-container').style.zIndex = '99999';
}
```

Isso evita que o modal fique atrás de outros elementos.

### Checklist SweetAlert

Remova TODOS os:
- [ ] ❌ `alert('...')` 
- [ ] ❌ `confirm('...')`
- [ ] ❌ `window.alert()`
- [ ] ❌ `window.confirm()`

E substitua por:
- [ ] ✅ `Swal.fire()` para confirmações
- [ ] ✅ `Swal.fire()` para sucessos
- [ ] ✅ `Swal.fire()` para erros
- [ ] ✅ `showConfirmButton: false, timer: 1500` para sucessos
- [ ] ✅ `allowOutsideClick: false` para confirmações
- [ ] ✅ Z-Index 99999 em ALL Swal.fire calls
- [ ] ✅ Cores consistentes (vermelho, cinza, azul)
- [ ] ✅ Icons apropriados (warning, error, success, info)

### Implementação Completa

Veja **FIX_SWEET_ALERTS.md** para a implementação completa em:
- dioceses/index.blade.php
- nucleos/index.blade.php
- secretarias/index.blade.php
- dirigentes/index.blade.php

---

### SweetAlert Patterns Padronizados (Legado)

**Use SEMPRE estes padrões:**

#### 1. Confirmação de Ação Irreversível
```javascript
Swal.fire({
    title: 'Tem certeza?',
    text: 'Descrição da ação...',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626', // Vermelho para destruir
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sim, [ação]',
    cancelButtonText: 'Cancelar'
})
```

#### 2. Sucesso
```javascript
Swal.fire({
    icon: 'success',
    title: 'Sucesso!',
    text: 'Descrição do que foi feito...',
    timer: 1500 // Auto-close após 1.5s
})
```

#### 3. Erro
```javascript
Swal.fire({
    icon: 'error',
    title: 'Erro!',
    text: 'Descrição do erro...'
})
```

#### 4. Aviso
```javascript
Swal.fire({
    icon: 'warning',
    title: 'Atenção',
    text: 'Descrição do aviso...'
})
```

#### 5. Info
```javascript
Swal.fire({
    icon: 'info',
    title: 'Informação',
    text: 'Descrição...'
})
```

#### 6. Loading
```javascript
Swal.fire({
    title: 'Processando...',
    didOpen: () => {
        Swal.showLoading();
    },
    allowOutsideClick: false,
    allowEscapeKey: false
})
```

---

### Cores Padronizadas para SweetAlert

```javascript
// Confirmar ações seguras (criar, editar)
confirmButtonColor: '#2563eb' // Azul

// Deletar/Ações perigosas
confirmButtonColor: '#dc2626' // Vermelho

// Cancelar (sempre)
cancelButtonColor: '#6b7280' // Cinza

// Editar/Avisos
icon: 'warning' // Amarelo

// Sucesso
icon: 'success' // Verde

// Erro
icon: 'error' // Vermelho

// Info
icon: 'info' // Azul
```

---

### Checklist SweetAlert

Remova TODOS os:
- [ ] ❌ `alert('...')` 
- [ ] ❌ `confirm('...')`
- [ ] ❌ `window.alert()`
- [ ] ❌ `window.confirm()`

E substitua por:
- [ ] ✅ `Swal.fire()` para confirmações
- [ ] ✅ `Swal.fire()` para sucessos
- [ ] ✅ `Swal.fire()` para erros
- [ ] ✅ `Swal.fire()` com `showLoading()` para loading
- [ ] ✅ Cores consistentes (azul, vermelho, cinza)
- [ ] ✅ Icons apropriados (warning, error, success, info)

### 7. **Mensagens de Erro em Modal**

```blade
<!-- Dentro do <x-crud-modal> -->
<div id="formError" class="hidden mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/30 dark:border-red-700 dark:text-red-300"></div>

<!-- Input com mensagem de erro -->
<div>
    <label class="block text-sm font-medium mb-2">Nome</label>
    <input type="text" name="nome" id="[nomeModal]nome" class="w-full px-3 py-2 border rounded-lg" required>
    <span class="text-red-500 text-sm hidden" id="[nomeModal]nomeError"></span>
</div>
```

```javascript
const response = await fetch(...);
const result = await response.json();

if (!result.success) {
    const errorDiv = document.getElementById('formError');
    errorDiv.textContent = result.message || 'Erro ao salvar';
    errorDiv.classList.remove('hidden');
    return;
}
```

### 8. **Paginação (se necessário)**

```blade
<!-- Após tabela/cards -->
<div class="mt-6 flex justify-between items-center">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Mostrando {{ $items->firstItem() ?? 0 }} até {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} registros
    </p>
    
    <div class="flex gap-2">
        {{ $items->links() }}
    </div>
</div>
```

### 9. **Breadcrumbs (Navegação Hierárquica)**

Se há relacionamentos:

```blade
<div class="mb-6 flex items-center gap-2 text-sm">
    <a href="{{ route('dioceses.index') }}" class="text-blue-600 hover:underline">Dioceses</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('dioceses.show', $diocese) }}" class="text-blue-600 hover:underline">{{ $diocese->nome }}</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Núcleos</span>
</div>
```

### 10. **Sorting (Colunas Ordenáveis)**

```blade
<!-- Header da tabela -->
<th class="px-6 py-3 font-medium text-gray-500 text-sm dark:text-gray-400 text-start">
    <a href="{{ route('items.index', array_merge(request()->all(), ['sort' => 'nome', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1">
        Nome
        @if(request('sort') === 'nome')
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                @if(request('order') === 'asc')
                    <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3z"/>
                @else
                    <path d="M17 3a1 1 0 000 2H6a1 1 0 000-2h11zM17 7a1 1 0 000 2h-7a1 1 0 100-2h7zM17 11a1 1 0 100-2h-4a1 1 0 100 2h4z"/>
                @endif
            </svg>
        @endif
    </a>
</th>
```

---

## ✅ Checklist Completo (Atualizado)

### Design Principles
- [ ] Permissões (@can) corretas
- [ ] Info modal com dados críticos
- [ ] Mobile responsivo (cards)
- [ ] Hierarquia clara (seu vs externo)
- [ ] 1 ação primária, 3-4 secundárias

### Filtros & Search
- [ ] Search por nome/email
- [ ] Filtro por status
- [ ] Filtro por relacionamento (diocese, nucleo, etc)
- [ ] Botão Filtrar funcional
- [ ] Botão Limpar filtros

### User Feedback
- [ ] Success message ao criar/editar/deletar
- [ ] Error message ao falhar
- [ ] Empty state quando sem registros
- [ ] Loading state no submit
- [ ] Validação de form (frontend)
- [ ] Confirmação antes de deletar

### Tabela/Cards
- [ ] Desktop table com checkboxes
- [ ] Mobile cards responsivos
- [ ] Icons padronizados (info/edit/delete)
- [ ] Dark mode funcionando
- [ ] Hover states

### Modal
- [ ] CRUD modal usando `<x-crud-modal>`
- [ ] Info modal usando `<x-info-modal>`
- [ ] Validação com mensagens de erro
- [ ] Loading state ao submeter
- [ ] Fechamento ao sucesso

### Backend
- [ ] GET /[rota] (listagem)
- [ ] POST /[rota] (criar)
- [ ] PUT /[rota]/{id} (editar)
- [ ] DELETE /[rota]/{id} (deletar)
- [ ] POST /[rota]/delete-multiple (bulk delete)
- [ ] GET /[rota]/{id}/info (dados para modal)
- [ ] JSON responses com success/error/message

---

### Versão 1: Uso Rápido
```
Mande para Claude este PROMPT PARA CLAUDE (colinha com o conteúdo entre ```)
E substitua as variáveis [PLACEHOLDER] conforme a tela
```

### Versão 2: Abordagem Completa
```
1. Leia PRODUCT.md para entender os princípios
2. Copie o PROMPT PARA CLAUDE
3. Substitua todos os [PLACEHOLDER]
4. Mande para Claude executar
```

### Versão 3: Com Impeccable (Recomendado)
```
/impeccable craft [nome da tela]

Com contexto:
"Seguindo PRODUCT.md principles e o padrão técnico de dioceses/nucleos/secretarias/dirigentes"
```

---

## 📊 Mapeamento de Variáveis

Para cada tela, substitua:

| Placeholder | Exemplo (Tipo de Eventos) |
|---|---|
| `[NOME_DA_TELA]` | "Tipo de Eventos" |
| `[nomeModal]` | "tipoEventoModal" |
| `[nomeForm]` | "tipoEventoForm" |
| `[Nomeacao]` | "TipoEvento" |
| `[nomeEntidade]` | "tipoEvento" |
| `[rota]` | "tipo-eventos" |

---

## 📚 Referências

### Design
- `PRODUCT.md` - Princípios e brand personality
- `resources/views/dioceses/index.blade.php` - Exemplo simples
- `resources/views/dirigentes/index.blade.php` - Exemplo complexo

### Componentes
- `resources/views/components/crud-modal.blade.php`
- `resources/views/components/info-modal.blade.php`

### Utilities
- `TEMPLATE_USAGE.md` - Como usar os componentes

---

## 💡 Dicas Importantes

1. **Permissões**: Se um botão não aparece, usuario não reclama. Confiança.
2. **Icons**: Sempre igual em todas as telas (i=info, ✏️=edit, 🗑️=delete)
3. **Mobile**: Botões devem ter mínimo 44px de height para touch
4. **Info Modal**: Nunca force novo clique/página. Tudo em 1 modal.
5. **Dark Mode**: Teste sempre light E dark antes de considerar pronto
6. **Null Safety**: Sempre `if (el) el.value = ...` antes de atribuir

---

Boa sorte! 🚀
