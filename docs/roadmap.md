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

### Fase 4: Financeiro (Semana 7-8)

Sistema completo de movimentações e relatórios.

#### 4.1: Tabelas Financeiras
- [ ] FinanceiroCategoria table
- [ ] FinanceiroMovimento table
- [ ] Índices para otimização
- [ ] Soft deletes

**Arquivos**:
- `database/migrations/*_create_financeiro_categorias_table.php`
- `database/migrations/*_create_financeiro_movimentos_table.php`

#### 4.2: Categorias Financeiras
- [ ] CRUD de categorias (por entidade)
- [ ] Tipos: entrada, saída
- [ ] Listar categorias ativas
- [ ] Desativar categoria (soft delete)

**Arquivos**:
- `app/Http/Controllers/FinanceiroCategoriaController.php`
- `app/Http/Requests/StoreFinanceiroCategoriaRequest.php`

#### 4.3: Movimentações Financeiras
- [ ] Registrar entrada
- [ ] Registrar saída
- [ ] Editar movimentação
- [ ] Deletar movimentação (soft delete)
- [ ] Listar movimentações (com filtros)

**Arquivos**:
- `app/Http/Controllers/FinanceiroMovimentoController.php`
- `app/Http/Requests/StoreMovimentoRequest.php`
- `app/Http/Requests/UpdateMovimentoRequest.php`

**Validações**:
- Valor > 0
- Tipo = entrada ou saída
- Categoria.tipo = movimento.tipo
- Data não futura
- Forma de pagamento válida

#### 4.4: Vínculo com Eventos
- [ ] Associar movimento com evento (opcional)
- [ ] Listar movimentações de evento
- [ ] Totalizar entradas/saídas por evento

**Requisito**: evento_id é nullable, permite movimentação ordinária ou vinculada

#### 4.5: Relatórios Básicos
- [ ] Extrato por período
- [ ] Saldo do período
- [ ] Entradas vs Saídas (visão rápida)
- [ ] Movimentações por categoria
- [ ] Movimentações por forma de pagamento

**Implementação**: Service FinanceiroService calcula, view exibe

#### 4.6: Relatório Consolidado (Diocese)
- [ ] Diocese vê consolidado de todos filhos
- [ ] Comparativo entre entidades
- [ ] Tendências
- [ ] Exportação básica (CSV, PDF futura)

#### 4.7: Policies e Autorização
- [ ] FinanceiroPolicy: quem vê, cria, edita
- [ ] Diocese vê próprio + filhos (supervisão)
- [ ] Núcleo/Secretaria vê apenas próprio
- [ ] Admin vê tudo

**Testes**:
```bash
- Diocese não vê financeiro de outra diocese
- Núcleo cria movimentação
- Diocese edita para auditoria
- Relatórios consolidados funcionam
- Soft deletes mantêm histórico
```

**Deliverable da Fase 4**:
- CRUD completo de movimentações
- Categorias por entidade
- Relatórios básicos
- Consolidado para diocese
- Autorização funcionando

---

### Fase 5: Polish e Otimização (Semana 9-10)

Refinamentos, testes, documentação.

#### 5.1: Testes Automatizados
- [ ] Unit tests para Services
- [ ] Feature tests para Controllers
- [ ] Policy tests para autorizações
- [ ] Cobertura mínima: 70%

#### 5.2: Validações Completas
- [ ] Form Requests para todos os formulários
- [ ] Validações customizadas
- [ ] Mensagens de erro em português
- [ ] Frontend validation (Alpine.js)

#### 5.3: UI/UX Polish
- [ ] Design review com TailAdmin
- [ ] Responsividade em mobile
- [ ] Acessibilidade básica (WCAG)
- [ ] Confirmações de ações críticas
- [ ] Feedback visual (toasts, loading states)

#### 5.4: Performance
- [ ] Eager loading (evitar N+1)
- [ ] Índices no banco
- [ ] Cache se necessário
- [ ] Query optimization

#### 5.5: Documentação
- [ ] Code comments (apenas necessário)
- [ ] README atualizado
- [ ] Guia de setup para dev
- [ ] Documentação de API (futura)

#### 5.6: DevOps Básico
- [ ] .env.example correto
- [ ] Migrations testadas
- [ ] Seeders para dados de teste
- [ ] CI/CD básico (GitHub Actions ou similar)

**Deliverable da Fase 5**:
- Sistema confiável e bem testado
- Código limpo e documentado
- Pronto para uso em produção pequena

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

### Fase 4 ✓
- [ ] Movimentações registradas e editadas
- [ ] Categorias por entidade
- [ ] Relatórios básicos funcionam
- [ ] Consolidado para diocese funciona

### Fase 5 ✓
- [ ] 70% cobertura de testes
- [ ] UI responsiva
- [ ] Sem warnings/erros nos logs
- [ ] Documentação completa

## Risk & Mitigation

| Risk | Impact | Mitigation |
|------|--------|-----------|
| Regras de vínculo complexas | Alto | Tests detalhados, Service bem estruturado |
| Multi-entidade em eventos | Médio | Testes de integração, policy clara |
| Performance com relatórios | Médio | Eager loading, índices desde cedo |
| Autorização granular | Alto | Policies testadas, casos de uso claros |
| Soft deletes complicarem queries | Médio | Trait global scope, tests |

## Estimativas

| Fase | Duração | Pessoas | Esforço |
|------|---------|---------|--------|
| 1 | 2 sem | 1 | 80h |
| 2 | 2 sem | 1 | 100h |
| 3 | 2 sem | 1 | 120h |
| 4 | 2 sem | 1 | 100h |
| 5 | 2 sem | 1 | 80h |
| **Total** | **10 sem** | **1** | **480h** |

## Próximas Etapas (Após Fase 5)

1. **API com Sanctum**: Preparar para mobile/web apps futuros
2. **App Mobile**: Flutter/React Native para check-in
3. **QR Code Scanner**: Integração com gerador/scanner
4. **Relatórios Avançados**: Gráficos interativos, exportações customizadas
5. **Integração Financeira**: Connection com conta bancária
6. **Auditoria Completa**: Log de todas as ações
7. **Notificações**: Email/SMS para eventos, lembretes
8. **Analytics**: Dashboard executivo com KPIs

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

## Notas

- **Não overengineer**: Começar simples, refatorar conforme necessário
- **MVP mindset**: Fase 1-3 já entrega valor significativo
- **Feedback loop**: Testar com usuários reais regularmente
- **Tech debt**: Documentar itens de melhoria para depois de Phase 5
