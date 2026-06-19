# Pronto para Copiar e Colar!

## 🚀 Copie EXATAMENTE isso e mande no chat:

```
Agent(
    description: "Apply design improvements to 5 admin views",
    prompt: """
    Apply design improvements to these 5 views using dioceses, nucleos, secretarias, and dirigentes as templates:
    
    Files to update:
    1. resources/views/entidades/index.blade.php
    2. resources/views/eventos/index.blade.php
    3. resources/views/tipo-eventos/index.blade.php
    4. resources/views/participante-externos/index.blade.php
    5. resources/views/financeiro-categorias/index.blade.php
    
    Improvements to apply:
    
    1. **SweetAlert patterns** (from SCRIPT_DESIGN_IMPROVEMENTS.md section 7)
       - Confirmações: warning icon + showCancelButton + red (#dc2626) for delete + gray (#6b7280) for cancel + z-index 99999
       - Sucessos: auto-close timer 1500ms + showConfirmButton: false + z-index 99999
       - Erros: error icon + message + z-index 99999
       - Todas com: didOpen: () => { document.querySelector('.swal2-container').style.zIndex = '99999'; }
    
    2. **Filtros** (from SCRIPT_DESIGN_IMPROVEMENTS.md section 1)
       - Search/pesquisa por nome ou campo principal
       - Status filter (ativo/inativo) se tiver campo status
       - Related entity filter (diocese, evento, etc) se aplicável
       - Botão "Filtrar" e "Limpar"
       - Form GET para submit
    
    3. **Responsive Design**
       - Desktop (≥768px): Tabela com checkboxes para bulk delete
       - Tablet: Reduza tamanho das colunas, esconda ações secundárias
       - Mobile (<768px): Transforme tabela em cards com grid responsive
    
    4. **Empty States** (quando não há registros)
       - Icon SVG padronizado (caixa vazia)
       - Mensagem amigável "Nenhum registro encontrado"
       - Botão para criar primeiro item (@can permission)
    
    5. **Validação de Formulários**
       - Frontend validation em modais
       - Mensagens de erro por campo
       - Error messages em div vermelha destacada
       - Validação antes de submit com Swal.fire warning
    
    Template references (copy code from these):
    - dioceses/index.blade.php - Estrutura base + filtros
    - nucleos/index.blade.php - Filtros com relacionamento
    - secretarias/index.blade.php - Responsivo + validação
    - dirigentes/index.blade.php - Modais complexos + Swal patterns
    
    Requirements:
    - Mantenha controllers e rotas INTACTOS
    - Apenas melhore a view/UX/design
    - Aplique TODOS os Swal.fire() calls (não deixe alert/confirm)
    - Teste responsividade: desktop, tablet, mobile
    - Verifique z-index em 99999 para modals
    """
)
```

---

## ✅ Checklist Após Rodar:

- [ ] Agent rodou e fez as alterações
- [ ] Verifique em browser cada tela
- [ ] Teste SweetAlerts (criar, editar, deletar)
- [ ] Teste filtros (search, status, etc)
- [ ] Teste responsividade (resize window)
- [ ] Teste empty state (deletar todos os registros)
- [ ] Teste validação (deixar campos vazios)

---

## 🎯 Depois dessa rodada de 5, você pode fazer:

**Rodada 2 (próximas 5):**
```
Agent(
    description: "Apply design improvements to 5 more admin views",
    prompt: """
    Apply design improvements to these 5 views:
    
    Files:
    1. resources/views/participante-externos/index.blade.php
    2. resources/views/financeiro-categorias/index.blade.php
    3. resources/views/financeiro-movimentos/index.blade.php
    4. resources/views/almoxarifado-categorias/index.blade.php
    5. resources/views/almoxarifado-itens/index.blade.php
    
    [... use o mesmo prompt acima, apenas mude os files ...]
    """
)
```

**Rodada 3 (últimas 3):**
```
Agent(
    description: "Apply design improvements to remaining 3 admin views",
    prompt: """
    Apply design improvements to these 3 views:
    
    Files:
    1. resources/views/almoxarifado-movimentos/index.blade.php
    2. resources/views/tarefa-categorias/index.blade.php
    3. resources/views/tarefas/index.blade.php
    
    [... use o mesmo prompt, apenas mude os files ...]
    """
)
```

**Rodada 4 (documentos):**
```
Agent(
    description: "Apply design improvements to document views",
    prompt: """
    Apply design improvements to these 2 views:
    
    Files:
    1. resources/views/documento-categorias/index.blade.php
    2. resources/views/documentos/index.blade.php
    
    [... use o mesmo prompt, apenas mude os files ...]
    """
)
```

---

## 💡 Resumo Geral:

| Rodada | Views | Comando |
|--------|-------|---------|
| 1️⃣ | entidades, eventos, tipo-eventos, participante-externos, financeiro-categorias | ⬇️ Copie acima |
| 2️⃣ | financeiro-movimentos, almoxarifado-categorias, almoxarifado-itens, almoxarifado-movimentos, tarefa-categorias | Adapt prompt |
| 3️⃣ | tarefas, documento-categorias, documentos | Adapt prompt |
| ✅ | **Total: 13 views melhoradas** | **~2-3 horas** |

---

## 🚀 É Isso!

Só manda o comando Agent lá em cima que roda tudo! 🎉
