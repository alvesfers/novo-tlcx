# 3 Agents com Skill Impeccable - Melhorias Completas

## 🎨 Abordagem: Use `/impeccable adapt` para cada rodada

Cada Agent vai usar a skill `impeccable` para:
- ✅ Respeitar PRODUCT.md e DESIGN.md
- ✅ Melhorar responsividade (mobile/tablet/desktop)
- ✅ Transformar tabelas em cards
- ✅ Esconder botões baseado em permissões
- ✅ Adicionar modals de informações
- ✅ Aplicar padrões visuais consistentes
- ✅ Melhorar UX/UI geral

---

## 🚀 Copie TUDO e mande de uma vez:

```
Agent(
    description: "Apply impeccable design improvements - Rodada 1",
    prompt: """
    Use the /impeccable adapt skill to improve these 7 views.
    
    Respect PRODUCT.md and DESIGN.md entirely.
    
    Views to improve:
    1. resources/views/entidades/index.blade.php
    2. resources/views/eventos/index.blade.php
    3. resources/views/tipo-eventos/index.blade.php
    4. resources/views/participante-externos/index.blade.php
    5. resources/views/financeiro-categorias/index.blade.php
    6. resources/views/financeiro-movimentos/index.blade.php
    7. resources/views/eventos/calendario.blade.php (calendar view - special)
    
    For each view:
    1. Invoke Skill: /impeccable adapt [file-path]
    2. Focus on:
       - Responsividade: mobile/tablet/desktop
       - Transform tables to cards on mobile
       - Hide secondary buttons based on @can permissions
       - Add info modals with complete data
       - Apply SweetAlert patterns (warning/success/error)
       - Add filters (search, status, relationships)
       - Empty states when no records
       - Dark mode support
    
    Reference files:
    - PRODUCT.md (brand, tone, users)
    - DESIGN.md (colors, typography, spacing)
    - dioceses/index.blade.php (improved example)
    - nucleos/index.blade.php (improved example)
    - secretarias/index.blade.php (improved example)
    - dirigentes/index.blade.php (improved example)
    
    Special note for /eventos/calendario:
    - Melhore o design respeitando a estrutura de calendário
    - Aplique as cores e tipografia do DESIGN.md
    - Melhore responsividade
    - Melhore UX de navegação
    - Não mude a funcionalidade, apenas o visual
    """
)

Agent(
    description: "Apply impeccable design improvements - Rodada 2",
    prompt: """
    Use the /impeccable adapt skill to improve these 7 views.
    
    Respect PRODUCT.md and DESIGN.md entirely.
    
    Views to improve:
    1. resources/views/almoxarifado-categorias/index.blade.php
    2. resources/views/almoxarifado-itens/index.blade.php
    3. resources/views/almoxarifado-movimentos/index.blade.php
    4. resources/views/tarefa-categorias/index.blade.php
    5. resources/views/tarefas/index.blade.php
    6. resources/views/documento-categorias/index.blade.php
    7. resources/views/documentos/index.blade.php
    
    For each view:
    1. Invoke Skill: /impeccable adapt [file-path]
    2. Focus on:
       - Responsividade: mobile/tablet/desktop
       - Transform tables to cards on mobile
       - Hide secondary buttons based on @can permissions
       - Add info modals with complete data
       - Apply SweetAlert patterns (warning/success/error)
       - Add filters (search, status, relationships)
       - Empty states when no records
       - Dark mode support
    
    Reference files:
    - PRODUCT.md (brand, tone, users)
    - DESIGN.md (colors, typography, spacing)
    - dioceses/index.blade.php (improved example)
    - nucleos/index.blade.php (improved example)
    - secretarias/index.blade.php (improved example)
    - dirigentes/index.blade.php (improved example)
    """
)

Agent(
    description: "Apply impeccable design improvements - Rodada 3 (Reports & Special)",
    prompt: """
    Use the /impeccable adapt skill to improve these 6 special views.
    
    Respect PRODUCT.md and DESIGN.md entirely.
    
    Views to improve:
    1. resources/views/relatorios/financeiro.blade.php
    2. resources/views/relatorios/eventos.blade.php
    3. resources/views/relatorios/dirigentes.blade.php
    4. resources/views/auditoria/index.blade.php (if exists)
    5. resources/views/pages/dashboard.blade.php (or dashboard/index.blade.php)
    6. resources/views/pages/profile.blade.php (if exists)
    
    For each view:
    1. Invoke Skill: /impeccable adapt [file-path]
    2. Focus on:
       - Responsividade: mobile/tablet/desktop
       - Improve typography hierarchy
       - Better spacing and layout
       - Color consistency with DESIGN.md
       - Dark mode support
       - Better data visualization (charts if any)
       - Improve empty states
    
    Special instructions:
    - These are not CRUD views, they are reports/dashboards
    - Focus on: readability, hierarchy, visual appeal
    - Don't change functionality, only improve visual design
    - Apply brand colors and typography from DESIGN.md
    
    Reference files:
    - PRODUCT.md (brand, tone, users)
    - DESIGN.md (colors, typography, spacing)
    - dioceses/index.blade.php (improved example for reference)
    """
)
```

---

## ✅ O que vai acontecer:

```
3 Agents rodam em paralelo ⏭️

Agent 1 (Rodada 1):
├─ /impeccable adapt entidades/index.blade.php
├─ /impeccable adapt eventos/index.blade.php
├─ /impeccable adapt tipo-eventos/index.blade.php
├─ /impeccable adapt participante-externos/index.blade.php
├─ /impeccable adapt financeiro-categorias/index.blade.php
├─ /impeccable adapt financeiro-movimentos/index.blade.php
└─ /impeccable adapt eventos/calendario.blade.php

Agent 2 (Rodada 2):
├─ /impeccable adapt almoxarifado-categorias/index.blade.php
├─ /impeccable adapt almoxarifado-itens/index.blade.php
├─ /impeccable adapt almoxarifado-movimentos/index.blade.php
├─ /impeccable adapt tarefa-categorias/index.blade.php
├─ /impeccable adapt tarefas/index.blade.php
├─ /impeccable adapt documento-categorias/index.blade.php
└─ /impeccable adapt documentos/index.blade.php

Agent 3 (Rodada 3 - Reports/Dashboards):
├─ /impeccable adapt relatorios/financeiro.blade.php
├─ /impeccable adapt relatorios/eventos.blade.php
├─ /impeccable adapt relatorios/dirigentes.blade.php
├─ /impeccable adapt auditoria/index.blade.php
├─ /impeccable adapt pages/dashboard.blade.php
└─ /impeccable adapt pages/profile.blade.php
```

---

## 🎯 Resultado Final:

```
✅ 20 views melhoradas
✅ Respeita PRODUCT.md e DESIGN.md
✅ Responsivo (mobile/tablet/desktop)
✅ SweetAlert patterns
✅ Filtros adicionados
✅ Empty states
✅ Modals de informação
✅ Dark mode
✅ UX/UI melhorado
✅ Design consistente
✅ Brand colors e typography
```

---

## 📊 Resumo:

| Rodada | Views | Tipo | Skill |
|--------|-------|------|-------|
| **1** | 7 | CRUD | /impeccable adapt |
| **2** | 7 | CRUD | /impeccable adapt |
| **3** | 6 | Reports/Dashboards | /impeccable adapt |
| **✅ TOTAL** | **20 views** | **Tudo** | **Impeccable** |

---

## 🚀 Como usar:

1. Copie o bloco com 3 Agents acima
2. Cole aqui no chat
3. Clique SEND
4. **Feche, vá descansar** ☕
5. Volte em ~1-2 horas para ver o resultado

**Seu admin vai ficar INCRÍVEL!** ✨

---

## 💡 Dica:

Se algum Agent falhar (arquivo não encontrado, etc):
- Ele vai avisar qual arquivo não achou
- Você remove do prompt e manda novamente
- Sem problema! 👍
