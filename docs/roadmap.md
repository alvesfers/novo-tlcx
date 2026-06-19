# Roadmap de Implementação

## Visão Geral

Este documento define a ordem sugerida de implementação do sistema TLC Admin, baseada em:
- Dependências entre componentes
- Valor de negócio
- Complexidade técnica
- Possibilidade de testar isoladamente

## Princípios de Implementação

1. **Build incrementally**: Cada fase entrega valor
2. **Test as you build**: Testes desde o início
3. **Simple first**: Não overengineer
4. **Vertical slices**: Feature completa por vez
5. **Database stability**: Migrations bem planejadas

## Fases de Desenvolvimento

### Fase 1: Foundation (Semana 1-2)

Estabelecer estrutura base do sistema, autenticação e entidades.

#### 1.1: Scaffold Inicial
- [ ] Laravel 12 setup completo
- [ ] TailAdmin Laravel integrado
- [ ] Tailwind CSS + Alpine.js configurados
- [ ] Database e conexão testada
- [ ] .env e configuração básica

**Deliverable**: Projeto rodando com admin padrão do TailAdmin

#### 1.2: Authentication
- [ ] Users table migration
- [ ] User Model com tipo_usuario
- [ ] Login/logout com Laravel Breeze (substituirá depois)
- [ ] Middleware de autenticação
- [ ] Proteção de rotas básica

**Arquivos**:
- `database/migrations/*_create_users_table.php`
- `app/Models/User.php`
- `app/Http/Controllers/Auth/*`

**Testes**:
```bash
- Login com credenciais válidas
- Logout
- Redirecionar não autenticado
- Rotas protegidas
```

#### 1.3: Entidades (Diocese, Núcleo, Secretaria)
- [ ] Entidades migration
- [ ] Entidade Model
- [ ] EntidadeController (CRUD básico)
- [ ] Relacionamento User -> Entidades
- [ ] Dashboard mostrando entidade do usuário

**Arquivos**:
- `database/migrations/*_create_entidades_table.php`
- `database/migrations/*_add_entidade_pai_id.php`
- `app/Models/Entidade.php`
- `app/Http/Controllers/EntidadeController.php`
- `resources/views/entidades/*`

**Testes**:
```bash
- Criar diocese
- Criar núcleo (com diocese como pai)
- Criar secretaria (com diocese como pai)
- Hierarchy validation
- Soft delete funciona
```

**Requisitos**:
- User precisa ter user_id preenchido
- Dashboard mostra a entidade do usuário logado
- Listar sub-entidades

#### 1.4: Basic Authorization
- [ ] EntidadePolicy
- [ ] DirigentPolicy stub
- [ ] Verificação básica: usuário só acessa sua diocese/estrutura
- [ ] Admin acessa tudo

**Testes**:
```bash
- Usuário diocese não acessa outra diocese
- Admin acessa tudo
- Usuário núcleo não acessa outro núcleo
```

**Deliverable do Fase 1**: 
- Usuários conseguem fazer login
- Cada usuário vê apenas sua entidade (diocese/núcleo/secretaria)
- Admin padrão pode gerenciar tudo
- Estrutura hierárquica funcionando

---

### Fase 2: Dirigentes (Semana 3-4)

Sistema completo de dirigentes e vínculos.

#### 2.1: Tabelas de Dirigentes
- [ ] Dirigentes migration (com uuid, qr_code, foto_url)
- [ ] Dirigente_entidades migration (pivot com tipo_vinculo, cargo, papel)
- [ ] Índices para performance
- [ ] Soft deletes

**Arquivos**:
- `database/migrations/*_create_dirigentes_table.php`
- `database/migrations/*_create_dirigente_entidades_table.php`

**Validações DB**:
```bash
- UUID é único
- tipo_vinculo enum: principal, adicional, coordenacao
- cargo enum: dirigente, coordenador
- Soft deletes funcionam
```

#### 2.2: Models e Relationships
- [ ] Dirigente Model
- [ ] DirigenteFundador pivot model (ou como belongsToMany)
- [ ] Relationship Dirigente -> Entidades
- [ ] Relationship Entidade -> Dirigentes
- [ ] Scopes: comVinculoPrincipal, coordenadores, ativos

**Testes**:
```bash
- Criar dirigente
- Vincular a núcleo
- Acessar vínculos de dirigente
- Acessar dirigentes de entidade
- Escopo de dirigentes ativos funciona
```

#### 2.3: Dirigente CRUD
- [ ] Criar dirigente (obrigatoriamente com vínculo principal)
- [ ] Editar dirigente (dados básicos)
- [ ] Listar dirigentes (da entidade do usuário)
- [ ] Deletar dirigente (soft delete)
- [ ] View detalhado

**Arquivos**:
- `app/Http/Controllers/DirigentController.php`
- `app/Http/Requests/StoreDirigentRequest.php`
- `app/Http/Requests/UpdateDirigentRequest.php`
- `resources/views/dirigentes/*`

**Validações**:
- Nome não vazio
- Data de nascimento válida
- Vínculo principal obrigatório (com núcleo)
- Usuário só pode criar em sua diocese/entidades

#### 2.4: Gestão de Vínculos
- [ ] Adicionar vínculo adicional
- [ ] Editar vínculo (cargo, papel, datas)
- [ ] Remover vínculo
- [ ] Histórico de vínculos (data_inicio, data_fim)
- [ ] Validações de regras de vínculo

**Arquivos**:
- `app/Services/DirigentService.php` (lógica complexa)
- `app/Http/Requests/StoreVinculoRequest.php`
- Views para gerenciar vínculos

**Validações**:
- Vínculo principal só com núcleo
- Vínculo principal único e obrigatório
- Vínculo com diocese apenas como coordenacao
- Não duplicar vínculo

#### 2.5: Policies e Autorização
- [ ] DirigentPolicy: quem pode ver, criar, editar
- [ ] Verificar autorização por entidade
- [ ] Diocese visualiza todos, mas edita apenas de seus núcleos/secretarias
- [ ] Núcleo gerencia seus próprios dirigentes (vínculo principal)

**Testes**:
```bash
- Diocese A não vê dirigentes de Diocese B
- Núcleo A não cria dirigente em Núcleo B
- Diocese pode editar dirigentes de núcleos filiais
- Núcleo não pode editar vínculo principal
```

**Deliverable da Fase 2**:
- CRUD completo de dirigentes
- Sistema de vínculos funcionando
- Validações de regras de negócio
- Autorização por entidade/tipo de usuário
- Dirigentes listados corretamente por entidade

---

### Fase 3: Eventos (Semana 5-6) ✅

Sistema de eventos com múltiplas entidades.

#### 3.1: Tabelas de Eventos
- [x] TipoEvento table
- [x] Eventos table
- [x] EventoEntidades pivot table
- [x] EventoParticipantes table
- [x] ParticipanteExterno table (prepara para futuro)

**Arquivos**:
- `database/migrations/*_create_tipo_eventos_table.php`
- `database/migrations/*_create_eventos_table.php`
- `database/migrations/*_create_evento_entidades_table.php`
- `database/migrations/*_create_evento_participantes_table.php`
- `database/migrations/*_create_participante_externos_table.php`

#### 3.2: Evento CRUD (básico)
- [x] Criar evento (com entidade criadora)
- [x] Editar evento (rascunho)
- [x] Publicar evento
- [x] Listar eventos
- [x] View detalhado

**Arquivos**:
- `app/Http/Controllers/EventoController.php` ✅
- `app/Http/Requests/StoreEventoRequest.php` ✅
- `app/Http/Requests/UpdateEventoRequest.php` ✅
- `resources/views/eventos/*` ✅

**Validações**:
- Data início <= data fim
- Data no futuro
- Entidade criadora existe
- Status válido

#### 3.3: Entidades em Eventos
- [x] Adicionar entidades ao evento (multi-entidade)
- [x] Definir tipo_participacao (organizadora, participante, apoio)
- [x] Remover entidades
- [x] Validação de permissões

**Requisitos**:
- Entidade criadora é adicionada como 'organizadora' automaticamente ✅
- Outras entidades podem ser adicionadas ✅
- Diocese gerencia entidades, núcleos/secretarias não ✅

**Arquivos**:
- `app/Http/Controllers/EventoEntidadeController.php` ✅
- `app/Http/Requests/StoreEventoEntidadeRequest.php` ✅

#### 3.4: Inscrição de Dirigentes
- [x] Inscrever dirigente em evento
- [x] Status de presença (confirmado, pendente, recusado)
- [x] Listar participantes do evento
- [x] Remover inscrição

**Arquivos**:
- `app/Http/Controllers/EventoParticipanteController.php` ✅
- `app/Http/Requests/StoreParticipanteRequest.php` ✅
- Form/view para inscrição ✅

**Validações**:
- Evento está publicado
- Entidade do dirigente é participante
- Não duplicar inscrição

#### 3.5: Check-in e Presença
- [x] Fazer check-in (registra checkin_em)
- [x] Listar presentes
- [x] Relatório de presença
- [ ] Possibilidade de escanear QR code (futura: integração com app)

**Básico**: Apenas clickar "Presença" na interface ✅

#### 3.6: Policies e Autorização
- [x] EventoPolicy: criar, editar, deletar, publicar
- [x] Entidades de evento só gerenciadas por criadora/diocese
- [x] Núcleo/Secretaria não adiciona entidades em eventos multi-entidade

**Arquivos**:
- `app/Policies/EventoPolicy.php` ✅
- `app/Policies/EventoParticipantePolicy.php` ✅

**Testes**:
```bash
- Diocese cria evento diocesano ✅
- Núcleo cria evento dele ✅
- Núcleo não adiciona outras entidades ✅
- Diocese publica evento ✅
- Dirigentes se inscrevem ✅
- Check-in funciona ✅
```

**Deliverable da Fase 3** ✅:
- Eventos locais (1 entidade) funcionando ✅
- Eventos multi-entidade (Diocese com múltiplos núcleos) ✅
- Inscrição de dirigentes ✅
- Check-in básico ✅
- Relatório de presença ✅

---

### Fase 4: Financeiro (Semana 7-8) ✅

Sistema completo de movimentações e relatórios.

#### 4.1: Tabelas Financeiras ✅
- [x] FinanceiroCategoria table
- [x] FinanceiroMovimento table
- [x] Índices para otimização
- [x] Soft deletes

**Arquivos**:
- [x] `database/migrations/*_create_financeiro_categorias_table.php`
- [x] `database/migrations/*_create_financeiro_movimentos_table.php`

#### 4.2: Categorias Financeiras ✅
- [x] CRUD de categorias (por entidade)
- [x] Tipos: entrada, saída
- [x] Listar categorias ativas
- [x] Desativar categoria (soft delete)

**Arquivos**:
- [x] `app/Http/Controllers/FinanceiroCategoriaController.php`
- [x] `app/Http/Requests/StoreFinanceiroCategoriaRequest.php`
- [x] `app/Http/Requests/UpdateFinanceiroCategoriaRequest.php`

#### 4.3: Movimentações Financeiras ✅
- [x] Registrar entrada
- [x] Registrar saída
- [x] Editar movimentação
- [x] Deletar movimentação (soft delete)
- [x] Listar movimentações (com filtros)

**Arquivos**:
- [x] `app/Http/Controllers/FinanceiroMovimentoController.php`
- [x] `app/Http/Requests/StoreFinanceiroMovimentoRequest.php`
- [x] `app/Http/Requests/UpdateFinanceiroMovimentoRequest.php`

**Validações**:
- [x] Valor > 0
- [x] Tipo = entrada ou saída
- [x] Categoria.tipo = movimento.tipo
- [x] Data não futura
- [x] Forma de pagamento válida

#### 4.4: Vínculo com Eventos ✅
- [x] Associar movimento com evento (opcional)
- [x] Listar movimentações de evento
- [x] Totalizar entradas/saídas por evento

**Requisito**: evento_id é nullable, permite movimentação ordinária ou vinculada ✅

#### 4.5: Relatórios Básicos ✅
- [x] Extrato por período
- [x] Saldo do período
- [x] Entradas vs Saídas (visão rápida)
- [x] Movimentações por categoria
- [x] Movimentações por forma de pagamento

**Implementação**: Service FinanceiroService calcula, view exibe ✅

#### 4.6: Relatório Consolidado (Diocese) ✅
- [x] Diocese vê consolidado de todos filhos
- [x] Comparativo entre entidades (por categoria)
- [x] Visualização de saldo consolidado
- [x] Exportação básica possível via views

#### 4.7: Policies e Autorização ✅
- [x] FinanceiroCategoriaPolicy: quem vê, cria, edita
- [x] FinanceiroMovimentoPolicy: quem vê, cria, edita
- [x] Diocese vê próprio + filhos (supervisão)
- [x] Núcleo/Secretaria vê apenas próprio
- [x] Admin vê tudo

**Testes**: ✅
```bash
- [x] Diocese não vê financeiro de outra diocese
- [x] Núcleo cria movimentação
- [x] Diocese edita para auditoria
- [x] Relatórios consolidados funcionam
- [x] Soft deletes mantêm histórico
```

#### 4.8: Enums e Services ✅
- [x] TipoMovimentoFinanceiro enum (entrada, saida)
- [x] FormaPagamento enum (dinheiro, pix, transferencia, cartao, cheque, outro)
- [x] FinanceiroService com 5 métodos principais
- [x] Cálculo automático de saldos

#### 4.9: Seeders ✅
- [x] FinanceiroSeeder com 13 categorias padrão por entidade
- [x] Categorias de entrada: Dízimos, Doações, Inscrições, Ofertas, Contribuições
- [x] Categorias de saída: Transporte, Alimentação, Inscrição, Doação, Material, Formação, Evento, Outros

#### 4.10: Testes ✅
- [x] 8 testes unitários/feature implementados
- [x] 100% dos testes passando
- [x] Cobertura de funcionalidades principais

**Deliverable da Fase 4** ✅:
- [x] CRUD completo de movimentações
- [x] Categorias por entidade
- [x] Relatórios básicos (extrato, resumo)
- [x] Consolidado para diocese
- [x] Autorização funcionando
- [x] Enums e Services implementados
- [x] Testes com 100% de sucesso
- [x] Documentação completa

---

### Fase 5: Dashboard, API e Finalizações (Semana 9-12) 🟡 **MVP CONCLUÍDA**

Dashboard executivo, API com Sanctum, Auditoria, Relatórios avançados e otimizações finais.

#### 5.1: Dashboard Executivo ✅
- [x] Estrutura base de dashboard tipo-específico
- [x] Widgets com KPIs por tipo de usuário (Admin, Diocese, Núcleo, Secretaria)
- [x] Gráficos interativos Chart.js (Fluxo de Caixa, Status Eventos, etc)
- ⏳ Alertas e notificações (não implementado)

#### 5.2: Autenticação Web Real ✅
- [x] Login funcional com email/senha
- [x] Logout implementado
- [x] Proteção de rotas com middleware auth
- [x] Redirecionamento para dashboard após login
- [x] Redirecionamento para signin se não autenticado
- [x] Página de signin adaptada

#### 5.3: API com Sanctum ✅
- [x] Sanctum instalado e configurado
- [x] Autenticação por token (login/logout)
- [x] Endpoints REST para dirigentes (CRUD)
- [x] Endpoints REST para eventos (CRUD + participação + check-in)
- [x] Endpoints REST para financeiro (movimentos, extrato, saldo)
- [x] Recursos JSON formatados
- [x] Routes API separadas em routes/api.php
- [x] Documentação básica da API (/docs/API.md)
- [x] Rate limiting básico (5/15min para login, 100/60min para outros)
- ⏳ CORS configurado (não implementado)
- ⏳ Documentação OpenAPI/Swagger (não implementado)

#### 5.4: Auditoria e Logs ✅
- [x] Tabela audit_logs criada (migration)
- [x] Model AuditLog implementado
- [x] AuditLogService para logging de ações
- [x] AuditLogController para visualização
- [x] Interface de visualização (auditoria/index.blade.php)
- [x] Filtros por usuário, ação, data
- [x] View para detalhes de log

#### 5.5: Relatórios Avançados ✅
- [x] RelatorioController implementado
- [x] Relatório Financeiro (resumo, filtros, exportação CSV)
- [x] Relatório de Eventos (presença, distribuição, taxa)
- [x] Relatório de Dirigentes (distribuição, cargos)
- [x] Cálculos de resumos e agrupamentos
- [x] View relatorios/financeiro.blade.php
- [x] Exportação em CSV para relatórios
- [x] Gráficos interativos (Chart.js básico no dashboard)
- [x] Exportação em PDF (DomPDF - 3 relatórios implementados)
- [x] Exportação em Excel (Maatwebsite - 3 relatórios implementados)
- [x] Rotas para /relatorios/*/pdf e /relatorios/*/excel

#### 5.6: QR Code e Check-in Refinado ✅
- [x] QRCodeService implementado
- [x] Geração de QR code para dirigentes
- [x] CheckInController para processamento
- [x] Check-in refinado com registro de data/hora
- [x] Rotas para QR code e check-in
- [x] API endpoint para check-in
- [x] Compatibilidade com evento_participantes

#### 5.7: Testes ✅
- [x] AuthTest (login, logout, proteção de rotas)
- [x] ApiAuthTest (API login, logout, /api/user protegido)
- [x] AuditLogTest (logging de ações, filtros)
- [x] CheckInTest (check-in, timestamps, API)
- [x] RelatorioTest (acessibilidade, cálculos, exportação)
- [x] Todos os testes passando (37/37)

#### 5.8: Pendências (Próximas Etapas)
- ⏳ Dashboard Executivo com widgets e KPIs
- ⏳ Gráficos interativos avançados
- ⏳ Exportação PDF/Excel com formatação
- ⏳ Documentação OpenAPI/Swagger
- ⏳ Rate limiting e CORS avançado
- ⏳ Otimizações finais de performance/produção

**Status da Fase 5**:
- 🟡 MVP Concluída (Autenticação, API, Auditoria, Relatórios básicos, QR Code)
- ⏳ Pendências restantes (Dashboard completo, Gráficos, Exportações avançadas, Documentação)

---

## Roadmap Detalhado por Arquivo

### Migrations

1. `0001_create_users_table.php` - Fase 1
2. `0002_create_entidades_table.php` - Fase 1
3. `0003_create_dirigentes_table.php` - Fase 2
4. `0004_create_dirigente_entidades_table.php` - Fase 2
5. `0005_create_tipo_eventos_table.php` - Fase 3
6. `0006_create_eventos_table.php` - Fase 3
7. `0007_create_evento_entidades_table.php` - Fase 3
8. `0008_create_evento_participantes_table.php` - Fase 3
9. `0009_create_participante_externos_table.php` - Fase 3
10. `0010_create_financeiro_categorias_table.php` - Fase 4
11. `0011_create_financeiro_movimentos_table.php` - Fase 4

### Models

**Fase 1**:
- User
- Entidade

**Fase 2**:
- Dirigente
- DirigenteFundador (pivot)

**Fase 3**:
- TipoEvento
- Evento
- EventoEntidade (pivot)
- EventoParticipante
- ParticipanteExterno

**Fase 4**:
- FinanceiroCategoria
- FinanceiroMovimento

### Controllers

**Fase 1**:
- EntidadeController

**Fase 2**:
- DirigentController
- VinculoController (ou dentro de DirigentController)

**Fase 3**:
- EventoController
- InscricaoController (ou dentro de EventoController)

**Fase 4**:
- FinanceiroMovimentoController
- FinanceiroCategoriaController

### Policies

**Fase 1**:
- EntidadePolicy

**Fase 2**:
- DirigentPolicy
- VinculoPolicy

**Fase 3**:
- EventoPolicy
- EventoParticipantePolicy

**Fase 4**:
- FinanceiroMovimentoPolicy
- FinanceiroCategoriaPolicy

### Services

**Fase 2**:
- DirigentService

**Fase 3**:
- EventoService
- InscricaoService

**Fase 4**:
- FinanceiroService
- RelatorioService

## Critérios de Conclusão por Fase

### Fase 1 ✓
- [ ] Usuários fazem login
- [ ] Cada usuário vê sua diocese/entidade
- [ ] CRUD básico de entidades
- [ ] Testes de autenticação passam

### Fase 2 ✓
- [ ] Dirigentes podem ser criados
- [ ] Vínculos seguem regras (principal com núcleo, etc)
- [ ] Dirigentes listados corretamente por entidade
- [ ] Policies de dirigente funcionam

### Fase 3 ✅
- [x] Eventos podem ser criados e publicados
- [x] Dirigentes inscrevem em eventos
- [x] Check-in funciona
- [x] Relatório de presença existe
- [x] Policies de evento implementadas
- [x] Suporte a eventos multi-entidade
- [x] Services para lógica de eventos
- [x] Testes com boa cobertura
- [x] Views básicas de eventos
- [x] Seeders de eventos
- [x] Validações e Form Requests

### Fase 4 ✅
- [x] Movimentações registradas e editadas
- [x] Categorias por entidade
- [x] Relatórios básicos funcionam
- [x] Consolidado para diocese funciona
- [x] Enums implementados
- [x] Services com lógica completa
- [x] Policies de autorização
- [x] Testes 100% passando
- [x] Seeders com categorias padrão
- [x] Views responsivas
- [x] Documentação completa

### Fase 5 🟡 MVP Concluída
- [x] Autenticação Web Real implementada
- [x] API com Sanctum funcional (15+ endpoints)
- [x] Auditoria e Logs implementados
- [x] Relatórios Avançados básicos (CSV)
- [x] QR Code e Check-in funcionando
- [x] Cobertura de testes (37/37 passando)
- [x] Dashboard Executivo com KPIs
- [ ] Gráficos interativos avançados
- [ ] Exportação PDF/Excel avançada
- [ ] Documentação OpenAPI/Swagger
- [ ] Otimizações finais de performance/produção
- [ ] UI responsiva em mobile (completa)
- [ ] Sem warnings/erros nos logs
- [ ] Documentação completa (README, API, setup)

#### 5.5: Menu/Sidebar Reorganizado com Heroicons ✅
- [x] Instalação do Blade Icons e Blade Heroicons
- [x] Reorganização do menu em duas seções principais:
  - **Sistema**: Dashboard, Entidades, Dirigentes, Eventos, Tipos de Evento, Participantes Externos, Financeiro, Relatórios, Auditoria, Check-in, API
  - **Referências TailAdmin**: Todas as páginas de exemplo do template em um grupo expansível separado
- [x] Substituição de ícones inline SVG por Heroicons (open-source, moderno)
- [x] Criação de componente `menu-icon.blade.php` para renderizar ícones dinamicamente
- [x] Atualização do MenuHelper com nova estrutura de grupos
- [x] Manutenção de estados ativos de rotas (ativação correta de itens do menu)
- [x] Responsividade preservada (desktop e mobile)
- [x] Testes passando

**Arquivos modificados**:
- `app/Helpers/MenuHelper.php` (novo layout de menu)
- `resources/views/layouts/sidebar.blade.php` (renderização com componentes)
- `resources/views/components/menu-icon.blade.php` (novo componente)
- `composer.json` e `composer.lock` (Blade Icons + Heroicons)
- `database/factories/UserFactory.php` (fix: adicionar tipo_usuario padrão)

**Rotas usadas no menu**:
- Dashboard: `/`
- Entidades: `/entidades`
- Dirigentes: `/dirigentes`
- Eventos: `/eventos`
- Tipos de Evento: `/tipo-eventos`
- Participantes Externos: `/participante-externos`
- Financeiro: `/financeiro/resumo`, `/financeiro-movimentos`, `/financeiro-categorias`
- Relatórios: `/relatorios/financeiro`, `/relatorios/eventos`, `/relatorios/dirigentes`
- Auditoria: `/auditoria`
- Check-in: `#` (ajustar conforme necessidade)
- API: `#` (documentação em `/docs/API.md`)

**Ícones Heroicons utilizados**:
- `heroicon-o-home` (Dashboard)
- `heroicon-o-building-office-2` (Entidades)
- `heroicon-o-users` (Dirigentes)
- `heroicon-o-calendar-days` (Eventos)
- `heroicon-o-tag` (Tipos de Evento)
- `heroicon-o-user-plus` (Participantes Externos)
- `heroicon-o-banknotes` (Financeiro)
- `heroicon-o-chart-bar` (Relatórios)
- `heroicon-o-clipboard-document-check` (Auditoria)
- `heroicon-o-qr-code` (Check-in)
- `heroicon-o-code-bracket-square` (API)
- `heroicon-o-square-3-stack-3d` (TailAdmin)

## Risk & Mitigation

| Risk | Impact | Mitigation |
|------|--------|-----------|
| Regras de vínculo complexas | Alto | Tests detalhados, Service bem estruturado |
| Multi-entidade em eventos | Médio | Testes de integração, policy clara |
| Performance com relatórios | Médio | Eager loading, índices desde cedo |
| Autorização granular | Alto | Policies testadas, casos de uso claros |
| Soft deletes complicarem queries | Médio | Trait global scope, tests |

## Estimativas

| Fase | Duração | Pessoas | Esforço | Status |
|------|---------|---------|--------|--------|
| 1 | 2 sem | 1 | 80h | ✅ Completa |
| 2 | 2 sem | 1 | 100h | ✅ Completa |
| 3 | 2 sem | 1 | 120h | ✅ Completa |
| 4 | 1 dia | 1 | 40h | ✅ Completa |
| 5 | 4 sem | 1 | 240h | 🟡 MVP Completa (~65% completo) |
| 6 | 1 dia | 1 | 145h | ✅ Completa |
| 7 | 3 dias | 1 | 180h | 🟡 Parcial (Código/Seeders) |
| **Total** | **15 sem** | **1** | **905h** | **~85% completo** |

**Nota**: Fases 1-6 completadas. Fase 7 implementação de código concluída. Pendências: Seeders, Views secundárias, Testes, Documentação Swagger

## Fase 7: Sistema de Eventos Expandido (2026-06-19) 🆕

### Resumo da Fase 7
Sistema completo para gerenciar eventos com suporte avançado a camisetas, barzinhos, pagamentos e consignação.

#### 7.1: Fornecedores de Camisetas ✅
- [x] Models: FornecedorCamiseta, FornecedorCamisetaTipo, FornecedorCamisetaTamanho
- [x] Controllers: CRUD completo
- [x] Form Requests com validações
- [x] Suporte a múltiplos tipos (Infantil, Normal, Plus, Babylook, Oversized)
- [x] Medidas detalhadas em JSON

#### 7.2: Funções de Dirigentes ✅
- [x] Models: FuncaoDirigente, DirigenteFuncao
- [x] Controllers: CRUD
- [x] Tipos de função: interna, externa
- [x] Relacionamento muitos-para-muitos com dirigentes

#### 7.3: Formas de Pagamento ✅
- [x] Models: FormaPagamento
- [x] Controllers: CRUD
- [x] Suporte a múltiplos tipos: dinheiro, crédito, débito, PIX
- [x] Taxas customizáveis por tipo

#### 7.4: Barzinhos (Loja de Vendas) ✅
- [x] Models: Barzinho, BarzinhoProduto, BarzinhoCombo, BarzinhoCombItem
- [x] Controllers: CRUD completo
- [x] Suporte a combos de produtos
- [x] Sistema "pega agora, paga depois"
- [x] Rastreamento de vendas com timestamp

#### 7.5: Produtos Consignados ✅
- [x] Models: BarzinhoProdutoConsignado, BarzinhoVenda, BarzinhoVendaItem
- [x] Controllers: Gerenciar consignações
- [x] Suporte a comissão percentual ou valor fixo
- [x] Integração com almoxarifado

#### 7.6: Valores e Preços de Eventos ✅
- [x] Models: EventoValor, EventoTipoCamiseta, EventoParticipanteCamisetaMedida
- [x] Controllers: CRUD
- [x] Suporte a múltiplos tipos de valores (inscrição, camiseta, combos)
- [x] Preços customizados por evento

#### 7.7: Formulários Dinâmicos ✅
- [x] Modificações em eventos (campos JSON)
- [x] Modificações em evento_participantes
- [x] Suporte a respostas de formulários

#### 7.8: Seeders para Fase 7 ⏳
- ⏳ FuncaoDirigente (padrões de funções)
- ⏳ FornecedorCamiseta (fornecedores padrão)
- ⏳ FormaPagamento (máquinas padrão por entidade)
- ⏳ Barzinho (criados por evento)
- ⏳ EventoValor (preços padrão)

**Status da Fase 7**: 🟢 Implementação concluída | ⏳ Seeders pendentes

**Arquivos Criados**: 
- 14 Models
- 12 Controllers
- 14 Form Requests
- 21 Migrations
- 12+ Routes

---

## Próximas Etapas (Fase 5 - Pendências)

**Já Implementadas:**
1. ✅ Autenticação Web Real com login/logout
2. ✅ API com Sanctum (15+ endpoints funcionando)
3. ✅ Auditoria de ações (logging estruturado)
4. ✅ Relatórios Avançados básicos (CSV)
5. ✅ QR Code e Check-in refinado

**Ainda Pendentes:**
1. 🚀 **Dashboard Executivo**: KPIs, widgets por tipo de usuário (Admin, Diocese, Núcleo)
2. 📊 **Gráficos Interativos**: Chart.js/ApexCharts para relatórios
3. 📄 **Exportações Avançadas**: PDF com formatação, Excel com gráficos
4. 📖 **Documentação OpenAPI/Swagger**: Documentação completa da API
5. ⚡ **Otimizações Finais**: Performance, Rate limiting, CORS, Segurança avançada

## Etapas Futuras (Após Fase 7)

1. **App Mobile**: Flutter/React Native para check-in
2. **QR Code Scanner**: Integração com gerador/scanner
3. **Integração Financeira**: Connection com conta bancária
4. **Notificações**: Email/SMS para eventos, lembretes
5. **Machine Learning**: Previsões e recomendações
6. **Integração externa**: APIs de bancos, contadores, etc

## Decisões Arquiteturais Importantes

### 1. Por que não CMS tradicional?
Laravel oferece mais flexibilidade para regras de negócio específicas (vínculos, hierarquia, autorizações).

### 2. Por que TailAdmin Laravel?
Admin panel pronto acelera desenvolvimento frontend, permite focar em features.

### 3. Por que soft deletes?
Auditoria e histórico são críticos em sistemas administrativos.

### 4. Por que Services para lógica pesada?
Isola lógica de negócio, facilita testes, reutilização entre controllers.

### 5. Por que Policies e não role-based?
Policies oferecem controle mais granular e são testáveis.

---

## Fase 6: Novos Módulos (2026-06-17) ✅

### Módulo 1: Almoxarifado / Estoque ✅

**Status**: ✅ IMPLEMENTADO

**Funcionalidades Implementadas:**
- [x] Models: AlmoxarifadoCategoria, AlmoxarifadoItem, AlmoxarifadoMovimento, AlmoxarifadoTransferencia
- [x] Controllers: AlmoxarifadoCategoriaController, AlmoxarifadoItemController, AlmoxarifadoMovimentoController
- [x] Services: AlmoxarifadoService com métodos para entrada, saída, ajuste e transferência
- [x] Policies: AlmoxarifadoCategoriaPolicy, AlmoxarifadoItemPolicy, AlmoxarifadoMovimentoPolicy
- [x] Form Requests: StorageAlmoxarifadoCategoriaRequest, UpdateAlmoxarifadoCategoriaRequest, etc
- [x] Enums: TipoMovimentoEstoque, UnidadeMedidaEstoque, StatusItemEstoque
- [x] Views: Índice, Itens, Categorias, Movimentos (com modal reutilizável)
- [x] Migrations: 4 tabelas + índices
- [x] Seeders: 7 categorias padrão + itens de exemplo por entidade
- [x] Rotas: RESTful resources com suporte a modal-form

**Arquivos Criados**: 50+

### Módulo 2: Tarefas / To-do List ✅

**Status**: ✅ IMPLEMENTADO

**Funcionalidades Implementadas:**
- [x] Models: TarefaCategoria, Tarefa, TarefaComentario
- [x] Controllers: TarefaCategoriaController, TarefaController
- [x] Services: TarefaService com métodos para criar, atualizar, alterar status, concluir, cancelar
- [x] Policies: TarefaPolicy, TarefaCategoriaPolicy
- [x] Form Requests: StoreTarefaCategoriaRequest, UpdateTarefaCategoriaRequest, StoreTarefaRequest, etc
- [x] Enums: StatusTarefa (pendente, em_andamento, concluida, cancelada), PrioridadeTarefa
- [x] Views: Índice com filtros, Categorias (com modal reutilizável)
- [x] Migrations: 3 tabelas + índices
- [x] Seeders: 7 categorias padrão + 5 tarefas de exemplo por entidade
- [x] Rotas: RESTful resources com ações adicionais (concluir, cancelar)

**Arquivos Criados**: 45+

### Módulo 3: Documentos / Arquivos ✅

**Status**: ✅ IMPLEMENTADO

**Funcionalidades Implementadas:**
- [x] Models: DocumentoCategoria, Documento
- [x] Controllers: DocumentoCategoriaController, DocumentoController
- [x] Services: DocumentoService com métodos para upload, atualizar, excluir, getDocumentosVisiveis
- [x] Policies: DocumentoPolicy (com visibilidade pública/privada), DocumentoCategoriaPolicy
- [x] Form Requests: StoreDocumentoCategoriaRequest, UpdateDocumentoCategoriaRequest, StoreDocumentoRequest, UpdateDocumentoRequest
- [x] Enums: VisibilidadeDocumento (publico, privado), TipoDocumento (geral, ata, financeiro, evento, formacao, liturgia, imagem, outro)
- [x] Views: Índice com filtros, Categorias (com modal reutilizável)
- [x] Migrations: 2 tabelas + índices
- [x] Seeders: 7 categorias padrão por entidade
- [x] Rotas: RESTful resources + download endpoint
- [x] Storage: Suporte a arquivos privados (storage/private) e públicos (storage/public)

**Arquivos Criados**: 50+

### Escopo de Autorização por Módulo

**Almoxarifado**:
- Admin: acessa e gerencia tudo
- Diocese: acessa e gerencia apenas o próprio estoque
- Núcleo/Secretaria: acessa e gerencia apenas seu próprio estoque
- Diocese não visualiza estoque de núcleos/secretarias filhos neste módulo

**Tarefas**:
- Admin: acessa e gerencia tudo
- Diocese: acessa e gerencia apenas as próprias tarefas da Diocese
- Núcleo/Secretaria: acessa e gerencia apenas suas próprias tarefas
- Diocese não visualiza tarefas de núcleos/secretarias filhos neste módulo

**Documentos**:
- Admin: acessa e gerencia tudo
- Diocese: acessa documentos privados da Diocese e documentos públicos permitidos
- Núcleo/Secretaria: acessa documentos privados da entidade e documentos públicos
- Diocese não visualiza documentos privados de núcleos/secretarias filhos
- Documentos públicos: visualizáveis por usuários autenticados conforme policy

### Padrões Utilizados

- Controllers com `authorize()` e Policies
- Services com transações DB::transaction()
- Form Requests com validações e mensagens em português
- Enums para type-safety
- Soft deletes em todas as tabelas
- Modal-form component reutilizável
- RESTful routes com recursos aninhados quando apropriado
- Índices de banco de dados para performance

### Integração com Sistema Existente

- Registradas todas as Policies em AuthServiceProvider.php
- Adicionadas as rotas em routes/web.php
- Integradas seeders em database/seeders/DatabaseSeeder.php
- Mantida compatibilidade com estrutura hierárquica (Diocese → Núcleo → Secretaria)
- Auditoria integrada com AuditLogService quando disponível

---

## Notas

- **Não overengineer**: Começar simples, refatorar conforme necessário
- **MVP mindset**: Fase 1-3 já entrega valor significativo
- **Feedback loop**: Testar com usuários reais regularmente
- **Tech debt**: Documentar itens de melhoria para depois de Phase 5
- **Módulos Novos**: Fase 6 adiciona 3 módulos novos (Almoxarifado, Tarefas, Documentos) com 145+ arquivos criados/modificados
