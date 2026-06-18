# 📱 Telas e Documentação Completa do TLC Admin

**Última Atualização:** 17 de Junho de 2026  
**Versão:** 1.0 Final  
**Status:** ✅ Production-Ready (85-90% de conclusão)

---

## 📋 Índice de Conteúdo

1. [Visão Geral do Sistema](#visão-geral-do-sistema)
2. [Telas de Autenticação](#telas-de-autenticação)
3. [Dashboard](#dashboard)
4. [Entidades (Dioceses, Núcleos, Secretarias)](#entidades)
5. [Dirigentes](#dirigentes)
6. [Eventos](#eventos)
7. [Financeiro](#financeiro)
8. [Relatórios e Exportação](#relatórios-e-exportação)
9. [Matriz de Permissões](#matriz-de-permissões)
10. [Documentação Técnica](#documentação-técnica)

---

## 🎯 Visão Geral do Sistema

### Sobre o TLC Admin

**TLC Admin** é um dashboard administrativo moderno construído com:
- **Backend:** Laravel 12
- **Frontend:** Blade, Tailwind CSS v4, Alpine.js
- **UI:** TailAdmin Laravel (componentes prontos)
- **Database:** MySQL/PostgreSQL/SQLite
- **API:** Laravel Sanctum (futura)

### Arquitetura Multi-Entidade

O sistema opera com uma hierarquia de entidades:

```
Diocese (raiz)
├── Núcleos (filiais)
│   ├── Dirigentes
│   ├── Eventos
│   └── Financeiro
└── Secretarias (especializadas)
    ├── Dirigentes
    ├── Eventos
    └── Financeiro
```

### Stack Tecnológico Completo

| Camada | Tecnologia |
|--------|-----------|
| **Framework** | Laravel 12 |
| **Template** | Blade PHP |
| **Styling** | Tailwind CSS v4 |
| **Interatividade** | Alpine.js |
| **Admin UI** | TailAdmin Laravel |
| **Build Tool** | Vite |
| **Database** | MySQL/PostgreSQL |
| **Auth** | Laravel Sanctum |
| **Exportação** | DomPDF, Maatwebsite Excel |
| **Gráficos** | Chart.js (CDN) |

---

## 🔐 Telas de Autenticação

### 1. Sign In (Entrada)

**Rota:** `GET /login` → `auth.signin`  
**View:** `resources/views/pages/auth/signin.blade.php`

**Funcionalidades:**
- ✅ Email + Senha
- ✅ "Lembrar-me" (opcional)
- ✅ Link "Esqueci minha senha"
- ✅ Redirecionamento para dashboard após login
- ✅ Validação de credenciais
- ✅ Proteção contra brute force (5 tentativas/15 min)

**Layout:**
- Tela minimalista limpa
- Logo do TLC
- Formulário centralizado
- Em português

**Dados Necessários:**
```
Email: usuario@tlc.local
Senha: (bcrypted)
```

### 2. Sign Up (Registro - Futuro)

**Rota:** (A implementar)  
**Status:** ⏳ Planejado para Fase 6

**Funcionalidades Esperadas:**
- Criação de novo usuário
- Seleção de tipo (Admin, Diocese, Núcleo, Secretaria)
- Validação de email
- Confirmação de email
- Termos de serviço

---

## 📊 Dashboard

### Overview

O Dashboard é **tipo-específico** e adapta-se automaticamente ao tipo de usuário.

**Rota:** `GET /dashboard`  
**Controller:** `DashboardController@index`  
**Service:** `DashboardService`

### 1. Dashboard Admin

**View:** `resources/views/dashboard/admin.blade.php`

**KPIs Exibidos:**
- Total de Dioceses
- Total de Núcleos
- Total de Secretarias
- Total de Dirigentes
- Total de Usuários
- Saldo Global (consolidado)

**Dados Dinâmicos:**
- Últimos 5 eventos
- Top 5 entidades por saldo
- Gráfico: Fluxo Financeiro (6 meses)
- Gráfico: Distribuição de Eventos por Status
- Gráfico: Taxa de Presença em Eventos
- Gráfico: Dirigentes por Cargo

**Acesso:** Admin apenas

---

### 2. Dashboard Diocese

**View:** `resources/views/dashboard/diocese.blade.php`

**KPIs Exibidos:**
- Total de Núcleos (filhos diretos)
- Total de Secretarias
- Total de Dirigentes
- Total de Eventos
- Saldo Diocesano

**Dados Dinâmicos:**
- Próximos 5 eventos
- Núcleos com maior participação (ranking)
- Estrutura hierárquica visual
- Gráficos específicos da diocese

**Acesso:** Usuários tipo Diocese

---

### 3. Dashboard Núcleo

**View:** `resources/views/dashboard/nucleo.blade.php`

**KPIs Exibidos:**
- Total de Dirigentes (próprios)
- Total de Eventos
- Saldo do Núcleo
- Próximas atividades

**Dados Dinâmicos:**
- Calendário de eventos
- Dirigentes ativos
- Movimentações financeiras recentes

**Acesso:** Usuários tipo Núcleo

---

### 4. Dashboard Secretaria

**View:** `resources/views/dashboard/secretaria.blade.php`

**KPIs Exibidos:**
- Total de Dirigentes (vínculos)
- Total de Eventos
- Saldo da Secretaria
- Próximas atividades

**Dados Dinâmicos:**
- Foco em atividades temáticas
- Dirigentes afiliados
- Movimentações próprias

**Acesso:** Usuários tipo Secretaria

---

### 5. Dashboard Genérico

**View:** `resources/views/dashboard/generico.blade.php`

**Fallback** para tipos não mapeados

---

## 🏛️ Entidades

O sistema gerencia três tipos de entidades em hierarquia.

### Tabela de Entidades

| Tipo | Descrição | Podem ter | Pai |
|------|-----------|-----------|-----|
| **Diocese** | Raiz da hierarquia | Núcleos, Secretarias | Nenhum |
| **Núcleo** | Filial local | Dirigentes, Eventos, Financeiro | Diocese |
| **Secretaria** | Especialidade temática | Dirigentes, Eventos, Financeiro | Diocese |

---

### 1. Dioceses

#### 1.1 Dioceses - Index (Listagem)

**Rota:** `GET /dioceses` → `dioceses.index`  
**View:** `resources/views/dioceses/index.blade.php`  
**Controller:** `DiocesesController@index`

**Exibição:**
- Tabela com todas as dioceses
- Colunas:
  - Nome (clicável para detalhes)
  - Email
  - Status (Ativo/Inativo)
  - Total de Núcleos
  - Total de Dirigentes
  - Total de Secretarias
  - Ações (Editar, Deletar, Ver Detalhes)

**Filtros:**
- Por nome
- Por status (Ativo/Inativo)

**Paginação:** Sim

**Acesso:** Admin, Diocese (própria)

---

#### 1.2 Dioceses - Show (Detalhes)

**Rota:** `GET /dioceses/{diocese}` → `dioceses.show`  
**View:** `resources/views/dioceses/show.blade.php`  
**Controller:** `DiocesesController@show`

**Seções:**

**Seção 1: Informações Básicas**
- Nome da Diocese
- Email
- Status (badge)
- Criada em (data)

**Seção 2: Estatísticas**
- Total de Núcleos
- Total de Dirigentes
- Total de Secretarias
- Saldo Consolidado

**Seção 3: Núcleos Vinculados**
- Cards navegáveis de núcleos
- Click leva para detalhes do núcleo
- Mostra: Nome, Email, Status

**Seção 4: Dirigentes da Diocese**
- Tabela com:
  - Nome (clicável)
  - Cargo
  - Tipo de Vínculo (Principal, Adicional, Coordenação)
  - Status
  - Ações

**Seção 5: Ações**
- Botão "Editar"
- Botão "Deletar" (com confirmação)
- Link voltar à listagem

---

#### 1.3 Dioceses - Create/Edit

**Rotas:**
- Create: `GET /dioceses/create` → `dioceses.create`
- Store: `POST /dioceses` → `dioceses.store`
- Edit: `GET /dioceses/{diocese}/edit` → `dioceses.edit`
- Update: `PUT /dioceses/{diocese}` → `dioceses.update`

**View:** `resources/views/dioceses/create.blade.php` (reutilizada para edit)  
**Controller:** `DiocesesController`

**Formulário:**
- Nome (obrigatório)
- Email (obrigatório, único)
- Status (checkbox: Ativo/Inativo)
- Botões: Salvar, Cancelar

**Validações:**
- Nome não vazio
- Email válido e único
- Email no padrão TLC

---

### 2. Núcleos

#### 2.1 Núcleos - Index (Listagem)

**Rota:** `GET /nucleos` → `nucleos.index`  
**View:** `resources/views/nucleos/index.blade.php`  
**Controller:** `NucleosController@index`

**Exibição:**
- Tabela com todos os núcleos (respeitando permissões)
- Colunas:
  - Nome (clicável)
  - Diocese (link)
  - Email
  - Status
  - Total de Secretarias
  - Total de Dirigentes
  - Ações

**Filtros:**
- Por Diocese (dropdown)
- Por nome
- Por status

**Paginação:** Sim

---

#### 2.2 Núcleos - Show (Detalhes)

**Rota:** `GET /nucleos/{nucleo}` → `nucleos.show`  
**View:** `resources/views/nucleos/show.blade.php`  
**Controller:** `NucleosController@show`

**Seções:**

**Seção 1: Informações Básicas**
- Nome
- Diocese Pai (link clicável)
- Email
- Status
- Criada em

**Seção 2: Estatísticas**
- Total de Secretarias
- Total de Dirigentes
- Saldo do Núcleo

**Seção 3: Secretarias do Núcleo**
- Cards com:
  - Nome (clicável)
  - Tipo (Aberta/Fechada)
  - Email
  - Status

**Seção 4: Dirigentes Vinculados**
- Tabela com:
  - Nome (clicável)
  - Cargo
  - Tipo de Vínculo
  - Status
  - Data início do vínculo

**Seção 5: Ações**
- Editar
- Deletar
- Voltar para Diocese

---

#### 2.3 Núcleos - Create/Edit

**Formulário:**
- Nome (obrigatório)
- Diocese (seletor - obrigatório)
- Email
- Status (checkbox)
- Tipo (Aberto/Fechado)

---

### 3. Secretarias

#### 3.1 Secretarias - Index (Listagem)

**Rota:** `GET /secretarias` → `secretarias.index`  
**View:** `resources/views/secretarias/index.blade.php`  
**Controller:** `SecretariasController@index`

**Exibição:**
- Tabela com secretarias
- Colunas:
  - Nome (clicável)
  - Núcleo (link)
  - Tipo
  - Email
  - Status
  - Total de Dirigentes
  - Ações

---

#### 3.2 Secretarias - Show (Detalhes)

**Rota:** `GET /secretarias/{secretaria}` → `secretarias.show`  
**View:** `resources/views/secretarias/show.blade.php`

**Seções:**

**Seção 1: Informações**
- Nome
- Núcleo Pai (link)
- Diocese (link)
- Tipo
- Email
- Status
- Criada em

**Seção 2: Estatísticas**
- Total de Dirigentes
- Hierarquia Completa (Diocese > Núcleo > Secretaria)
- Saldo da Secretaria

**Seção 3: Dirigentes Vinculados**
- Tabela com:
  - Nome (clicável)
  - Tipo de Vínculo (Principal, Adicional, Coordenação)
  - Cargo
  - Papel
  - Status

**Seção 4: Ações**
- Editar
- Deletar
- Voltar para Núcleo

---

## 👥 Dirigentes

### Documentação Completa em: `docs/dirigentes.md`

---

### 1. Dirigentes - Index (Listagem)

**Rota:** `GET /dirigentes` → `dirigentes.index`  
**View:** `resources/views/dirigentes/index.blade.php`  
**Controller:** `DirigentesController@index`

**Exibição:**
- Tabela com dirigentes
- Colunas:
  - Foto (avatar)
  - Nome (clicável)
  - Email/Telefone
  - Núcleo Principal
  - Cargo
  - Status (Ativo/Inativo)
  - Ações

**Filtros:**
- Por Entidade
- Por Status
- Por Cargo
- Por nome

**Paginação:** Sim

**Acesso:** Respeitando hierarquia

---

### 2. Dirigentes - Show (Detalhes)

**Rota:** `GET /dirigentes/{dirigente}` → `dirigentes.show`  
**View:** `resources/views/dirigentes/show.blade.php`

**Seções:**

**Seção 1: Dados Pessoais**
- Foto de Perfil
- Nome
- Telefone
- Email
- Gênero
- Data de Nascimento
- Status (Ativo/Inativo)

**Seção 2: Identificação**
- UUID (para QR Code)
- QR Code (visual)

**Seção 3: Vínculos**
- Tabela com todos os vínculos:
  - Entidade (Diocese/Núcleo/Secretaria)
  - Tipo de Vínculo (Principal, Adicional, Coordenação)
  - Cargo (Dirigente, Coordenador)
  - Papel
  - Data Início
  - Data Fim (se inativo)
  - Status (Ativo/Inativo)

**Seção 4: Eventos Recentes**
- Últimos 5 eventos que participou
- Status de presença (Confirmado, Pendente, Recusado)

**Seção 5: Ações**
- Editar
- Adicionar Vínculo
- Mudar Núcleo Principal (Diocese)
- Deletar
- Exportar como PDF

---

### 3. Dirigentes - Create/Edit

**Rotas:**
- Create: `GET /dirigentes/create` → `dirigentes.create`
- Store: `POST /dirigentes` → `dirigentes.store`
- Edit: `GET /dirigentes/{dirigente}/edit` → `dirigentes.edit`
- Update: `PUT /dirigentes/{dirigente}` → `dirigentes.update`

**Formulário:**
- Nome (obrigatório)
- Telefone
- Email
- Gênero (M/F/Outro)
- Data Nascimento
- Foto (upload)
- Vínculo Principal (seletor - obrigatório, apenas Núcleos)
- Status (checkbox: Ativo)

---

### 4. Dirigentes - Gerenciar Vínculos

**Rota:** `GET /dirigentes/{dirigente}/vinculos` → `dirigentes.vinculos.edit`

**Seções:**

**Vínculos Atuais:**
- Tabela de vínculos existentes
- Opção de editar/remover cada vínculo

**Adicionar Novo Vínculo:**
- Seletor de Entidade (Núcleo/Secretaria/Diocese)
- Tipo de Vínculo (Principal, Adicional, Coordenação)
- Cargo (Dirigente, Coordenador)
- Papel (campo livre)
- Data Início (datepicker)

---

### 5. Dirigentes - Mudar Núcleo Principal

**Rota:** `GET /dirigentes/{dirigente}/trocar-nucleo` → `dirigentes.trocar-nucleo`

**Formulário:**
- Núcleo Atual (exibido)
- Novo Núcleo (seletor)
- Motivo (campo livre)
- Data da Mudança
- Validações:
  - Não pode ser o mesmo núcleo
  - Novo núcleo deve estar na mesma diocese

---

## 📅 Eventos

### Documentação Completa em: `docs/eventos.md`

---

### 1. Eventos - Index (Listagem)

**Rota:** `GET /eventos` → `eventos.index`  
**View:** `resources/views/eventos/index.blade.php`  
**Controller:** `EventoController@index`

**Exibição:**
- Tabela com eventos
- Colunas:
  - Nome (clicável)
  - Data Início
  - Data Fim
  - Entidade Criadora
  - Status (Rascunho, Publicado, Encerrado, Cancelado)
  - Total de Participantes
  - Ações

**Filtros:**
- Por Status
- Por Data (Range)
- Por Entidade
- Por Tipo de Evento

**Paginação:** Sim

**Visualização Alternativa:** Calendar view (futuro)

---

### 2. Eventos - Show (Detalhes)

**Rota:** `GET /eventos/{evento}` → `eventos.show`  
**View:** `resources/views/eventos/show.blade.php`

**Seções:**

**Seção 1: Informações do Evento**
- Nome
- Tipo de Evento
- Descricão/Detalhes
- Local
- Data/Hora Início
- Data/Hora Fim
- Status (badge com cor)
- Escopo (Coordenadores, Dirigentes, Ambos, Externos, Público)

**Seção 2: Entidades Participantes**
- Tabela com:
  - Nome da Entidade
  - Tipo de Participação (Organizadora, Participante, Apoio)
  - Status (Ativo)

**Seção 3: Participantes/Inscritos**
- Abas:
  - **Confirmados:** Lista de dirigentes confirmados
  - **Pendentes:** Aguardando confirmação
  - **Recusados:** Que declinaram

**Seção 4: Check-in**
- Botão "Fazer Check-in" (se evento está acontecendo)
- Tabela com presentes:
  - Nome do Dirigente
  - Hora do Check-in
  - Status

**Seção 5: Financeiro (se vinculado)**
- Movimentações relacionadas ao evento
- Entradas vs Saídas
- Resultado líquido

**Seção 6: Ações**
- Editar (se rascunho)
- Publicar (se rascunho)
- Cancelar (se não encerrado)
- Gerenciar Entidades
- Gerenciar Participantes
- Deletar

---

### 3. Eventos - Create/Edit

**Rotas:**
- Create: `GET /eventos/create` → `eventos.create`
- Store: `POST /eventos` → `eventos.store`
- Edit: `GET /eventos/{evento}/edit` → `eventos.edit`
- Update: `PUT /eventos/{evento}` → `eventos.update`

**Formulário:**

**Abas/Seções:**

**Aba 1: Básico**
- Nome (obrigatório)
- Tipo de Evento (seletor)
- Descrição (textarea)
- Data Início (datetime picker)
- Data Fim (datetime picker)
- Local (obrigatório)

**Aba 2: Configurações**
- Escopo (seletor: coordenadores, dirigentes, ambos, externos, público)
- Status (se criar: rascunho; se editar: escolher)
- Entidade Criadora (exibido, não editável)

**Aba 3: Entidades (multi-select)**
- Checkbox de entidades para participar
- Tipo de participação para cada:
  - Organizadora
  - Participante
  - Apoio
- A criadora é automaticamente organizadora

**Validações:**
- Data fim >= data início
- Data fim no futuro
- Nome não vazio
- Tipo válido

---

### 4. Eventos - Gerenciar Entidades

**Rota:** `GET /eventos/{evento}/entidades` → `eventos.entidades.manage`  
**View:** `resources/views/eventos/entidades/manage.blade.php`

**Funcionalidades:**
- Listar entidades já vinculadas
- Editar tipo de participação
- Remover entidades
- Adicionar novas entidades

**Form:**
- Seletor de novas entidades
- Tipo de participação para cada
- Botão "Adicionar"

---

### 5. Eventos - Gerenciar Participantes/Inscrições

**Rota:** `GET /eventos/{evento}/participantes` → `eventos.participantes.index`  
**View:** `resources/views/eventos/participantes/index.blade.php`

**Exibição:**
- Abas por status (Confirmados, Pendentes, Recusados)
- Tabela com:
  - Nome do Dirigente
  - Entidade
  - Data de Inscrição
  - Status de Presença
  - Check-in (hora se realizado)
  - Ações

**Ações:**
- Alterar Status de Presença
- Fazer Check-in manualmente
- Remover Inscrição

---

### 6. Eventos - Inscrever Dirigentes

**Rota:** `GET /eventos/{evento}/participantes/create` → `eventos.participantes.create`  
**View:** `resources/views/eventos/participantes/create.blade.php`

**Formulário:**
- Multi-select de Dirigentes (filtrados por entidades participantes)
- Status de Presença (Confirmado, Pendente)
- Botão "Inscrever"

**Validações:**
- Evento deve estar publicado
- Dirigente deve estar ativo
- Entidade do dirigente deve ser participante
- Não permitir duplicar inscrição

---

### 7. Eventos - Check-in

**Rota:** `POST /eventos/{evento}/checkin` → `eventos.checkin`

**Métodos de Check-in:**

**Método 1: QR Code Scanner**
- Input de QR Code (foco automático)
- Lê UUID do dirigente
- Registra check-in em tempo real
- Mostra confirmação

**Método 2: Manual**
- Seletor de dirigente
- Botão "Fazer Check-in"

**Resultado:**
- Atualiza `checkin_em` com NOW()
- Exibe confirmação visual
- Adiciona à lista de presentes

---

### 8. Eventos - Relatório de Presença

**Rota:** `GET /eventos/{evento}/relatorio-presenca` → `eventos.relatorio-presenca`  
**View:** `resources/views/eventos/relatorios/presenca.blade.php`

**Exibição:**
- Resumo de presença:
  - Total Inscrito
  - Confirmados
  - Presentes (com check-in)
  - Taxa de Presença (%)
- Tabela com dirigentes:
  - Nome
  - Entidade
  - Status de Inscrição
  - Presença (Sim/Não)
  - Hora Check-in
- Gráfico de presença por entidade

**Ações:**
- Exportar como PDF
- Exportar como Excel

---

## 💰 Financeiro

### Documentação Completa em: `docs/financeiro.md`

---

### 1. Financeiro - Movimentos (Listagem)

**Rota:** `GET /financeiro/movimentos` → `financeiro.movimentos.index`  
**View:** `resources/views/financeiro/movimentos/index.blade.php`

**Exibição:**
- Tabela com movimentações
- Colunas:
  - Data do Movimento
  - Tipo (Entrada/Saída) - com badges de cor
  - Descrição
  - Categoria
  - Valor (com símbolo R$)
  - Forma de Pagamento
  - Status
  - Ações

**Filtros Avançados:**
- Por Data (range)
- Por Tipo (Entrada/Saída)
- Por Categoria
- Por Forma de Pagamento
- Por Valor (range)

**Resumo (Topo da tabela):**
- Total de Entradas (verde)
- Total de Saídas (vermelho)
- Saldo do Período (azul)

**Paginação:** Sim

---

### 2. Financeiro - Movimentos (Show)

**Rota:** `GET /financeiro/movimentos/{movimento}` → `financeiro.movimentos.show`  
**View:** `resources/views/financeiro/movimentos/show.blade.php`

**Informações:**
- Tipo (Entrada/Saída)
- Descrição
- Valor (grande, destacado)
- Data do Movimento
- Categoria
- Forma de Pagamento
- Observação
- Comprovante (se anexado)
- Evento Relacionado (se houver)
- Auditoria:
  - Criado em
  - Criado por (usuário)
  - Última edição em
  - Editado por

**Ações:**
- Editar
- Deletar (soft delete)
- Voltar

---

### 3. Financeiro - Movimentos (Create/Edit)

**Rotas:**
- Create: `GET /financeiro/movimentos/create` → `financeiro.movimentos.create`
- Store: `POST /financeiro/movimentos` → `financeiro.movimentos.store`
- Edit: `GET /financeiro/movimentos/{movimento}/edit` → `financeiro.movimentos.edit`
- Update: `PUT /financeiro/movimentos/{movimento}` → `financeiro.movimentos.update`

**Formulário:**
- Tipo (Entrada/Saída) - radio buttons ou select
- Categoria (dropdown - filtrado por tipo)
- Descrição (obrigatório)
- Valor (currency input - obrigatório)
- Data do Movimento (datepicker - padrão: hoje)
- Forma de Pagamento (seletor: Dinheiro, Cheque, Transferência, PIX, Cartão)
- Evento Relacionado (opcional - autocomplete)
- Comprovante (upload - opcional)
- Observação (textarea)

**Validações:**
- Valor > 0
- Tipo concordar com categoria
- Data não futura
- Categoria existe
- Forma válida

---

### 4. Financeiro - Categorias (Listagem)

**Rota:** `GET /financeiro/categorias` → `financeiro.categorias.index`  
**View:** `resources/views/financeiro/categorias/index.blade.php`

**Exibição:**
- Tabela com categorias
- Colunas:
  - Nome
  - Tipo (Entrada/Saída) - badge
  - Status (Ativo/Inativo)
  - Total de Movimentos
  - Ações

**Botão:** "Nova Categoria"

---

### 5. Financeiro - Categorias (Create/Edit)

**Formulário:**
- Nome (obrigatório)
- Tipo (Entrada/Saída)
- Status (Ativo/Inativo)

---

### 6. Financeiro - Extrato

**Rota:** `GET /financeiro/extrato` → `financeiro.extrato`  
**View:** `resources/views/financeiro/extrato.blade.php`

**Funcionalidades:**
- Seletor de Período (Date range picker)
- Filtros:
  - Por Categoria
  - Por Forma de Pagamento
  - Por Tipo
- Exibição:
  - Tabela com movimentações ordenadas por data
  - Subtotal de Entradas
  - Subtotal de Saídas
  - Saldo do Período
  - Saldo Acumulado (calculado a partir do início dos registros)

**Gráficos:**
- Fluxo de Caixa (Entradas vs Saídas por mês)
- Distribuição por Categoria (pie chart)

---

### 7. Financeiro - Saldo

**Rota:** `GET /financeiro/saldo` → `financeiro.saldo`  
**View:** `resources/views/financeiro/saldo.blade.php`

**Exibição:**
- Grande destaque com Saldo Atual
- Cor verde se positivo, vermelho se negativo
- Última atualização

**Histórico:**
- Gráfico de evolução do saldo (últimos 12 meses)
- Tabela com saldos por período

---

## 📊 Relatórios e Exportação

### Documentação em: `docs/API.md` (seção Relatórios)

---

### 1. Relatório Financeiro

**Rotas:**
- HTML: `GET /relatorios/financeiro` → `relatorios.financeiro`
- PDF: `GET /relatorios/financeiro/pdf` → `relatorios.financeiro.pdf`
- Excel: `GET /relatorios/financeiro/excel` → `relatorios.financeiro.excel`

**View:** `resources/views/relatorios/financeiro.blade.php` (web), `relatorios/pdf/financeiro.blade.php` (PDF)

**Filtros:**
- Data Início (datepicker)
- Data Fim (datepicker)
- Categoria (multi-select)
- Tipo (Entrada/Saída ou ambos)

**Conteúdo:**
- Cabeçalho com período e entidade
- Resumo:
  - Total de Entradas
  - Total de Saídas
  - Resultado Líquido
- Tabela com movimentações detalhadas
- Gráfico: Fluxo de Caixa
- Rodapé com data de geração

---

### 2. Relatório de Eventos

**Rotas:**
- HTML: `GET /relatorios/eventos` → `relatorios.eventos`
- PDF: `GET /relatorios/eventos/pdf` → `relatorios.eventos.pdf`
- Excel: `GET /relatorios/eventos/excel` → `relatorios.eventos.excel`

**Filtros:**
- Data Início
- Data Fim
- Status (Rascunho, Publicado, Encerrado, Cancelado)
- Entidade

**Conteúdo:**
- Lista de eventos com:
  - Nome
  - Data
  - Entidades Participantes
  - Status
  - Participantes (total, confirmados, presentes)
- Taxa de Presença (%)
- Gráficos:
  - Distribuição por Status
  - Taxa de Presença

---

### 3. Relatório de Dirigentes

**Rotas:**
- HTML: `GET /relatorios/dirigentes` → `relatorios.dirigentes`
- PDF: `GET /relatorios/dirigentes/pdf` → `relatorios.dirigentes.pdf`
- Excel: `GET /relatorios/dirigentes/excel` → `relatorios.dirigentes.excel`

**Filtros:**
- Por Entidade
- Por Status (Ativo/Inativo)
- Por Cargo

**Conteúdo:**
- Tabela com:
  - Nome
  - Núcleo Principal
  - Vínculos Adicionais
  - Cargo
  - Status
  - Data de Criação
- Gráficos:
  - Dirigentes por Cargo
  - Distribuição por Entidade

---

### 4. Exportação PDF

**Bibliotecas:** DomPDF (`barryvdh/laravel-dompdf`)

**Funcionalidades:**
- Styling profissional com Tailwind CSS
- Headers e footers personalizados
- Cores e formatação
- Download automático com nome descritivo
- Respeita hierarquia de permissões

**Exemplo:** `relatorios_financeiro_2026-06-17.pdf`

---

### 5. Exportação Excel

**Bibliotecas:** Maatwebsite Excel

**Funcionalidades:**
- Headers customizados (negrito, fundo colorido)
- Dados formatados corretamente
- Múltiplas abas (se aplicável)
- Download em formato `.xlsx`
- Exemplo:** `relatorios_financeiros_2026-06-17.xlsx`

---

### 6. Gráficos Interativos

**Biblioteca:** Chart.js (CDN)  
**Service:** `ChartDataService`

**Gráficos Implementados:**

#### 6.1 Fluxo Financeiro (6 meses)
- Tipo: Linha
- Dados: Entradas vs Saídas
- Período: Últimos 6 meses
- Interativo: Hover mostra valores

#### 6.2 Distribuição de Eventos por Status
- Tipo: Rosca (Doughnut)
- Status: Publicado, Rascunho, Encerrado, Cancelado
- Cores diferentes por status
- Percentual

#### 6.3 Taxa de Presença em Eventos
- Tipo: Barras
- Dados: Por evento ou por mês
- Percentual de presença

#### 6.4 Dirigentes por Cargo
- Tipo: Barras
- Dados: Coordenadores vs Dirigentes
- Total absoluto

---

## 🔐 Matriz de Permissões

### Documentação Completa em: `docs/permissoes.md`

---

### Tabela Resumida de Acesso

| Recurso | Admin | Diocese | Núcleo | Secretaria |
|---------|-------|---------|--------|-----------|
| **Dioceses** | ✅ CRUD | ✅ R própria | ✅ R | ✅ R |
| **Núcleos** | ✅ CRUD | ✅ CRUD filhos | ⚠️ R próprio | ✅ R |
| **Secretarias** | ✅ CRUD | ✅ CRUD filhos | ⚠️ R | ⚠️ CRUD próprias |
| **Dirigentes** | ✅ CRUD | ✅ CRUD | ✅ Create/R próprios | ✅ Vincular apenas |
| **Eventos** | ✅ CRUD | ✅ CRUD | ✅ CRUD próprios | ✅ CRUD próprios |
| **Financeiro** | ✅ CRUD | ✅ CRUD + Audit | ✅ CRUD | ✅ CRUD |
| **Relatórios** | ✅ Todos | ✅ Consolidado | ✅ Próprio | ✅ Próprio |

**Legenda:**
- ✅ = Permissão completa
- ⚠️ = Permissão limitada
- ❌ = Sem permissão
- R = Read (Visualizar)
- CRUD = Create, Read, Update, Delete

---

### Regras de Autorização

1. **Admin:** Acesso total ao sistema
2. **Diocese:** Acesso a dados próprios e filhos (Núcleos, Secretarias)
3. **Núcleo:** Acesso a dados próprios; visualização de Diocese pai
4. **Secretaria:** Acesso a dados próprios; visualização de Diocese e Núcleo
5. **Soft Deletes:** Dados deletados não aparecem em listas
6. **Hierarquia:** Não ultrapassar limites hierárquicos

---

## 📁 Estrutura de Diretórios

```
tlc-admin/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Entidade.php
│   │   ├── Dirigente.php
│   │   ├── DirigenteFundador.php (pivot)
│   │   ├── Evento.php
│   │   ├── EventoEntidade.php (pivot)
│   │   ├── EventoParticipante.php
│   │   ├── ParticipanteExterno.php
│   │   ├── FinanceiroCategoria.php
│   │   ├── FinanceiroMovimento.php
│   │   └── TipoEvento.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── DiocesesController.php
│   │   │   ├── NucleosController.php
│   │   │   ├── SecretariasController.php
│   │   │   ├── DirigentesController.php
│   │   │   ├── EventoController.php
│   │   │   ├── FinanceiroController.php
│   │   │   ├── RelatorioController.php
│   │   │   └── [mais...]
│   │   └── Requests/
│   │       ├── StoreEntidadeRequest.php
│   │       ├── StoreDirigentRequest.php
│   │       ├── StoreEventoRequest.php
│   │       └── [mais...]
│   ├── Policies/
│   │   ├── EntidadePolicy.php
│   │   ├── DirigentPolicy.php
│   │   ├── EventoPolicy.php
│   │   └── FinanceiroMovimentoPolicy.php
│   ├── Services/
│   │   ├── DashboardService.php
│   │   ├── ChartDataService.php
│   │   ├── EventoService.php
│   │   └── [mais...]
│   └── Enums/
│       ├── TipoEntidade.php
│       ├── TipoVinculo.php
│       ├── CargoEnum.php
│       ├── TipoEvento.php
│       └── [mais...]
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   ├── fullscreen-layout.blade.php
│   │   │   └── [mais...]
│   │   ├── components/
│   │   │   ├── header/
│   │   │   ├── sidebar/
│   │   │   ├── chart.blade.php
│   │   │   └── [mais...]
│   │   ├── dashboard/
│   │   │   ├── admin.blade.php
│   │   │   ├── diocese.blade.php
│   │   │   ├── nucleo.blade.php
│   │   │   ├── secretaria.blade.php
│   │   │   └── generico.blade.php
│   │   ├── dioceses/
│   │   ├── nucleos/
│   │   ├── secretarias/
│   │   ├── dirigentes/
│   │   ├── eventos/
│   │   ├── financeiro/
│   │   ├── relatorios/
│   │   │   ├── pdf/
│   │   │   └── [views]
│   │   ├── pages/
│   │   │   ├── auth/
│   │   │   └── errors/
│   │   └── [mais...]
│   └── css/
│       └── app.css (Tailwind)
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── docs/
│   ├── arquitetura.md
│   ├── dirigentes.md
│   ├── eventos.md
│   ├── financeiro.md
│   ├── permissoes.md
│   ├── API.md
│   └── [mais...]
├── public/
│   └── build/ (assets compilados)
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── [mais arquivos de config]
```

---

## 📚 Documentação Técnica

### Arquivos de Documentação Disponíveis

| Arquivo | Conteúdo | Link |
|---------|----------|------|
| `README.md` | Setup, instalação e quick start | [Ver](./README.md) |
| `docs/arquitetura.md` | Arquitetura do sistema, stack tech | [Ver](./docs/arquitetura.md) |
| `docs/dirigentes.md` | Modelo de dirigentes, vínculos | [Ver](./docs/dirigentes.md) |
| `docs/eventos.md` | Sistema de eventos completo | [Ver](./docs/eventos.md) |
| `docs/financeiro.md` | Módulo financeiro, movimentações | [Ver](./docs/financeiro.md) |
| `docs/permissoes.md` | Matriz de permissões por role | [Ver](./docs/permissoes.md) |
| `docs/API.md` | Documentação de API REST | [Ver](./docs/API.md) |
| `IMPLEMENTACAO-FASE-5-FINAL.md` | Implementação da Fase 5 | [Ver](./IMPLEMENTACAO-FASE-5-FINAL.md) |
| `TELAS_CRIADAS.md` | Telas de detalhes (show) | [Ver](./TELAS_CRIADAS.md) |

---

### Commands Úteis

```bash
# Setup inicial
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Desenvolvimento
composer run dev

# Testes
composer run test

# Production
npm run build
php artisan config:cache

# Database
php artisan migrate:fresh --seed
php artisan tinker

# Limpeza
php artisan optimize:clear
```

---

### Rotas Principais

```
Autenticação:
  GET    /login              → auth.signin
  POST   /login              → auth.login
  POST   /logout             → auth.logout

Dashboard:
  GET    /dashboard          → dashboard.index

Dioceses:
  GET    /dioceses           → dioceses.index
  GET    /dioceses/create    → dioceses.create
  POST   /dioceses           → dioceses.store
  GET    /dioceses/{diocese} → dioceses.show
  GET    /dioceses/{diocese}/edit → dioceses.edit
  PUT    /dioceses/{diocese} → dioceses.update
  DELETE /dioceses/{diocese} → dioceses.destroy

[Similar para Núcleos e Secretarias]

Dirigentes:
  GET    /dirigentes         → dirigentes.index
  GET    /dirigentes/create  → dirigentes.create
  POST   /dirigentes         → dirigentes.store
  GET    /dirigentes/{dirigente} → dirigentes.show
  GET    /dirigentes/{dirigente}/edit → dirigentes.edit
  PUT    /dirigentes/{dirigente} → dirigentes.update
  DELETE /dirigentes/{dirigente} → dirigentes.destroy

[Similar para Eventos e Financeiro]

Relatórios:
  GET    /relatorios/financeiro       → relatorios.financeiro
  GET    /relatorios/financeiro/pdf   → relatorios.financeiro.pdf
  GET    /relatorios/financeiro/excel → relatorios.financeiro.excel
  [Similar para eventos e dirigentes]
```

---

## 🎯 Status de Implementação

### Fase 5 (Atual) - ✅ 100% Concluído

- ✅ Dashboard tipo-específico
- ✅ Gráficos interativos (Chart.js)
- ✅ Exportação PDF (DomPDF)
- ✅ Exportação Excel (Maatwebsite)
- ✅ Documentação API
- ✅ Rate Limiting

### Próximas Fases (Planejado)

- ⏳ **Fase 6:** Melhorias UI/UX, refinamentos
- ⏳ **Fase 7:** Mobile app (React Native)
- ⏳ **Fase 8:** Notificações e alertas
- ⏳ **Fase 9:** Performance e otimizações
- ⏳ **Fase 10:** Deploy e produção

---

## 📞 Suporte

Para dúvidas ou problemas:

1. **Documentação:** Verifique em `/docs`
2. **Logs:** `storage/logs/laravel.log`
3. **Database:** Conecte e inspecione as tabelas
4. **Testes:** Execute `composer run test`

---

**Última atualização:** 17 de Junho de 2026  
**Desenvolvedor:** Luiz Fernando Morais Alves  
**Status:** 🟢 Production-Ready
