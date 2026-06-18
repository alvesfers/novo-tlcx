# Plano de Implementação TLC Admin - Análise Arquitetural

**Versão**: 1.0  
**Data**: 2026-06-16  
**Status**: Planejamento Estratégico  
**Revisor**: Arquiteto de Software

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Estado Atual da Implementação](#estado-atual-da-implementação)
3. [Análise Arquitetural](#análise-arquitetural)
4. [Problemas Identificados](#problemas-identificados)
5. [Recomendações Arquiteturais](#recomendações-arquiteturais)
6. [Fases Restantes](#fases-restantes)
7. [Roadmap Final](#roadmap-final)
8. [Critérios de Conclusão](#critérios-de-conclusão)
9. [Riscos e Mitigação](#riscos-e-mitigação)

---

## Visão Geral

### O que foi Implementado ✅

**Fase 1 - Foundation (Completa)**
- ✅ Scaffold Laravel 12 com TailAdmin
- ✅ Sistema de Autenticação (Users com tipo_usuario)
- ✅ Entidades (Diocese, Núcleo, Secretaria) com hierarquia
- ✅ Relacionamentos básicos User → Entidade
- ✅ Dashboard e Layout responsivo

**Fase 2 - Dirigentes (Completa)**
- ✅ Migrations de Dirigentes e DirigenteEntidades
- ✅ Models com relacionamentos (hasMany, belongsToMany)
- ✅ UUID gerado automaticamente
- ✅ Controllers CRUD (DirigenteController, DirigenteEntidadeController)
- ✅ Form Requests com validações
- ✅ Views (index, create, edit, show) com Tailwind CSS
- ✅ Rotas RESTful completas
- ✅ Seeders com dados de exemplo (3 dirigentes)
- ✅ Testes básicos (10 testes passando)
- ✅ Implementação de regras de negócio:
  - Vínculo principal obrigatório com Núcleo
  - Vínculo adicional com Núcleo/Secretaria
  - Vínculo de coordenação apenas com Diocese
  - Métodos: getVinculoPrincipal(), pertenceADiocese(), pertenceAEntidade()

**Fase 3 - Eventos (Completa)**
- ✅ Migrations (tipo_eventos, eventos, evento_entidades, evento_participantes, participante_externos)
- ✅ Models com relacionamentos (Evento, EventoEntidade, EventoParticipante, ParticipanteExterno, TipoEvento)
- ✅ Controllers CRUD (EventoController, EventoEntidadeController, EventoParticipanteController)
- ✅ Form Requests com validações (StoreEventoRequest, UpdateEventoRequest, StoreParticipanteRequest)
- ✅ Views (index, create, edit, show) com Tailwind CSS
- ✅ Rotas RESTful completas
- ✅ Seeders com dados de evento de exemplo
- ✅ Testes com boa cobertura
- ✅ Policies (EventoPolicy, EventoParticipantePolicy)
- ✅ Services (EventoService, ParticipanteService)
- ✅ Suporte a eventos multi-entidade
- ✅ Sistema de inscrição de dirigentes
- ✅ Check-in e registro de presença
- ✅ Relatório de presença
- ✅ Validações de regras de negócio:
  - Entidade criadora é organizadora automática
  - Apenas dioceses gerenciam entidades em eventos
  - Dirigentes só se inscrevem em eventos de suas entidades
  - Ciclo de vida: rascunho → publicado → encerrado
  - Tipos de participação: organizadora, participante, apoio

### O que Falta Implementar ❌

**Fase 4 - Financeiro**
- Migrations (financeiro_categorias, financeiro_movimentos)
- Models
- Controllers
- Services (FinanceiroService, RelatorioService)
- Policies
- Form Requests
- Views (movimentações, categorias, relatórios)

**Fase 5 - Otimizações e Sistema**
- Policies de Autorização (CRÍTICO - Entidade, Dirigente, Evento, Financeiro)
- Services de Lógica Complexa
- Dashboard Executivo
- Check-in e QR Code
- Participantes Externos
- Relatórios Avançados
- API com Sanctum
- Auditoria e Logs
- Configurações do Sistema
- Deploy e Produção

### Dependências Entre Módulos

```
Autenticação (Phase 1) ✓
    ↓
Entidades (Phase 1) ✓
    ↓
Dirigentes (Phase 2) ✓
    ├─→ Eventos (Phase 3) [participantes]
    ├─→ Financeiro (Phase 4) [associação opcional]
    └─→ Check-in (Phase 5) [presença]
    
Eventos (Phase 3)
    ├─→ Participantes/Check-in (Phase 5)
    ├─→ Financeiro (Phase 4) [vínculo opcional]
    └─→ Relatórios (Phase 5)

Financeiro (Phase 4)
    ├─→ Relatórios (Phase 5)
    └─→ Consolidados (Diocese)

Policies (Phase 5) - TRANSVERSAL
    └─→ Todos os módulos dependem
```

---

## Estado Atual da Implementação

### Estrutura de Código

```
app/
├── Models/
│   ├── User.php ✓ (Completo)
│   ├── Entidade.php ✓ (Completo com dirigentes)
│   ├── Dirigente.php ✓ (Completo)
│   ├── DirigenteEntidade.php ✓ (Completo)
│   ├── Evento.php ✓ (Completo)
│   ├── EventoEntidade.php ✓ (Pivot)
│   ├── EventoParticipante.php ✓ (Completo)
│   ├── ParticipanteExterno.php ✓ (Completo)
│   └── TipoEvento.php ✓ (Completo)
├── Http/
│   ├── Controllers/
│   │   ├── EntidadeController.php ✓ (CRUD)
│   │   ├── DirigenteController.php ✓ (CRUD)
│   │   ├── DirigenteEntidadeController.php ✓ (Vínculos)
│   │   ├── EventoController.php ✓ (CRUD)
│   │   ├── EventoEntidadeController.php ✓ (Gerenciar entidades)
│   │   └── EventoParticipanteController.php ✓ (Inscrições e check-in)
│   ├── Requests/
│   │   ├── StoreDirigenteRequest.php ✓
│   │   ├── UpdateDirigenteRequest.php ✓
│   │   ├── StoreDirigenteEntidadeRequest.php ✓
│   │   ├── UpdateDirigenteEntidadeRequest.php ✓
│   │   ├── StoreEventoRequest.php ✓
│   │   ├── UpdateEventoRequest.php ✓
│   │   ├── StoreEventoEntidadeRequest.php ✓
│   │   └── StoreParticipanteRequest.php ✓
│   └── Middleware/ (vazio)
├── Policies/
│   ├── EntidadePolicy.php ✓
│   ├── DirigentPolicy.php ✓
│   ├── EventoPolicy.php ✓
│   └── EventoParticipantePolicy.php ✓
├── Services/
│   ├── EventoService.php ✓
│   └── ParticipanteService.php ✓
└── Enums/ ✓
    ├── TipoEvento.php
    ├── EscopoEvento.php
    ├── StatusEvento.php
    ├── TipoParticipacao.php
    ├── PresencaParticipante.php
    └── (outros enums)

database/
├── migrations/
│   ├── _create_users_table.php ✓
│   ├── _add_columns_to_users_table.php ✓
│   ├── _create_entidades_table.php ✓
│   ├── _create_dirigentes_table.php ✓
│   ├── _create_dirigente_entidades_table.php ✓
│   ├── _create_tipo_eventos_table.php ✓
│   ├── _create_eventos_table.php ✓
│   ├── _create_evento_entidades_table.php ✓
│   ├── _create_evento_participantes_table.php ✓
│   └── _create_participante_externos_table.php ✓
└── seeders/
    ├── InitialDataSeeder.php ✓
    └── EventoSeeder.php ✓

resources/views/
├── layouts/ ✓
├── components/ ✓
├── entidades/ ✓
├── dirigentes/ ✓ (completo)
├── eventos/ ✓ (completo)
│   ├── index.blade.php ✓
│   ├── create.blade.php ✓
│   ├── edit.blade.php ✓
│   ├── show.blade.php ✓
│   ├── entidades/ ✓
│   ├── participantes/ ✓
│   └── relatorios/ ✓
├── participante-externos/ ✓
├── tipo-eventos/ ✓
└── financeiro/ (VAZIO - Próxima Fase)

tests/
├── Feature/
│   ├── ExampleTest.php
│   ├── DirigenteTest.php ✓ (10 testes)
│   └── EventoTest.php ✓ (testes de eventos)
└── Unit/ (VAZIO)
```

### Contagem de Arquivos

| Categoria | Implementados | Planejados | Taxa |
|-----------|---------------|-----------|------|
| Models | 9 | 10 | 90% ✅ |
| Controllers | 6 | 8 | 75% ✅ |
| Form Requests | 8 | 14 | 57% ✅ |
| Migrations | 10 | 11 | 91% ✅ |
| Views | 4 pastas | 5 pastas | 80% ✅ |
| Policies | 4 | 8 | 50% ✅ |
| Services | 2 | 6 | 33% ✅ |
| Tests | 2 | 8 | 25% ✅ |
| Enums | Implementados | Planejados | 100% ✅ |

---

## Análise Arquitetural

### Pontos Positivos ✅

1. **Estrutura Sólida**
   - Laravel 12 com padrões modernos
   - Migrations versionadas corretamente
   - Soft deletes implementados
   - Timestamps em todas as tabelas críticas

2. **Relacionamentos Bem Modelados**
   - UUID em Dirigentes (público, não se usa ID)
   - Pivot correto em DirigenteEntidades
   - BelongsToMany com withPivot()
   - Relacionamentos bidirecionais

3. **Validações Centralizadas**
   - Form Requests implementados
   - Validações de regras de negócio no withValidator()
   - Mensagens de erro em português

4. **Testes Desde o Início**
   - 10 testes na Fase 2
   - Cobertura de casos positivos e negativos
   - Tests descritivos e bem organizados

5. **Seeders Funcionando**
   - InitialDataSeeder cria dados realistas
   - 3 dirigentes com vínculos diferentes
   - Demonstra casos de uso variados

### Pontos de Atenção ⚠️

1. **Falta de Policies - CRÍTICO**
   - Sem autorização granular
   - Qualquer usuário logado pode acessar tudo
   - Controllers não usam `$this->authorize()`
   - Vulnerabilidade de segurança

2. **Falta de Services**
   - Lógica complexa pode ficar nos controllers
   - Difícil reutilizar entre controllers
   - Testes unitários limitados
   - Transações não isoladas

3. **Controllers com Lógica de Negócio**
   - DirigenteController.store() faz muito
   - Validações duplicadas em Form Requests
   - Sem camada de serviço

4. **Falta de Dashboard**
   - Usuário vê apenas layout
   - Sem visão consolidada
   - Sem KPIs ou resumos

5. **Soft Deletes Sem Global Scope**
   - Modelo correto importa SoftDeletes
   - Mas não há trait global para excluir soft deleted de queries padrão
   - Pode retornar dirigentes deletados inadvertidamente

6. **Performance Não Otimizada**
   - Sem índices explícitos (migrações usam apenas index())
   - Sem eager loading documentado nos controllers
   - N+1 possível em listagens com vínculos

7. **Enums Não Criados**
   - Valores como strings em migrations
   - Sem tipagem forte
   - Possível erro ortográfico

8. **Tratamento de Erros Limitado**
   - Sem handlers customizados
   - Sem logging estruturado
   - Sem tratamento de exceptions específicas

### Riscos Técnicos 🔴

**CRÍTICO - Deve Resolver Antes de Produção:**
1. Falta total de Policies (autorização)
2. Sem tratamento de transações
3. Sem validação de integridade referencial

**ALTO**
4. N+1 queries em listagens
5. Sem cache de consultas frequentes
6. Sem logs de auditoria

**MÉDIO**
7. Configurações não externalizadas
8. Sem rate limiting
9. Sem validação de CORS

---

## Problemas Identificados

### P1: Falta de Autorização (Policies) - CRÍTICO

**Status**: 🔴 CRÍTICO  
**Severidade**: BLOQUEANTE  

**Problema:**
```php
// Não há autorização em lugar nenhum!
public function show(Dirigente $dirigente)
{
    return view('dirigentes.show', compact('dirigente'));
    // Qualquer usuário vê qualquer dirigente!
}
```

**Impacto:**
- Usuário diocesano vê dirigentes de outra diocese
- Núcleo edita dirigentes de outro núcleo
- Violação de isolamento de dados

**Solução Proposta:**
```php
public function show(Dirigente $dirigente)
{
    $this->authorize('view', $dirigente);
    return view('dirigentes.show', compact('dirigente'));
}
```

**Afeta:**
- Dirigentes, Eventos, Financeiro (quando implementados)
- Todos os Controllers

**Prioridade de Implementação:**
- **IMEDIATA** - Antes de Fase 3

---

### P2: Lógica de Negócio nos Controllers

**Status**: 🟡 ALTO  
**Severidade**: Manutenibilidade

**Problema:**
```php
// DirigenteController.store() faz tudo
public function store(StoreDirigenteRequest $request)
{
    $dirigente = Dirigente::create($request->safe()->except('entidade_id'));
    
    // Lógica de vínculo aqui - deveria ser Service
    $entidade = Entidade::find($request->entidade_id);
    $dirigente->vinculos()->create([...]);
    
    return redirect()->route('dirigentes.show', $dirigente);
}
```

**Impacto:**
- Difícil de testar
- Impossível reutilizar em API
- Transações não isoladas

**Solução:**
Criar `DirigentService` com:
```php
class DirigentService {
    public function criarComVinculoPrincipal(array $data): Dirigente
    public function adicionarVinculo(Dirigente $dirigente, array $data)
    public function mudarNucleoPrincipal(Dirigente $dirigente, Entidade $nucleo)
}
```

---

### P3: Soft Deletes Sem Proteção Global

**Status**: 🟡 MÉDIO  
**Severidade**: Integridade de Dados

**Problema:**
```php
// Retorna dirigentes deletados
$dirigentes = Dirigente::where('ativo', true)->get();
// Precisa adicionar manualmente
$dirigentes = Dirigente::where('ativo', true)->whereNull('deleted_at')->get();
```

**Solução:**
Implementar Global Scope:
```php
class Dirigente extends Model {
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SoftDeletingScope());
    }
}
```

---

### P4: Sem Enums para Enumerações

**Status**: 🟡 MÉDIO  
**Severidade**: Type Safety

**Problema:**
```php
// Strings soltas em todo código
tipo_vinculo: 'principal' // Pode virar 'principal ' com espaço
cargo: 'dirigente'         // Sem type checking
```

**Solução:**
```php
enum TipoVinculo: string {
    case Principal = 'principal';
    case Adicional = 'adicional';
    case Coordenacao = 'coordenacao';
}
```

---

### P5: Performance - N+1 Queries

**Status**: 🟡 MÉDIO  
**Severidade**: Escalabilidade

**Problema:**
```php
public function index()
{
    $dirigentes = Dirigente::with('vinculos.entidade')->paginate(15);
    // OK - usa eager loading
}

// Mas em seeders/testes:
foreach($dirigentes as $d) {
    $principal = $d->getVinculoPrincipal();  // Query por cada!
}
```

**Solução:**
- Documentar eager loading em controllers
- Usar select() para limitar colunas
- Cache para dados que mudam pouco

---

### P6: Sem Tratamento de Erros

**Status**: 🟡 MÉDIO  
**Severidade**: Debugging

**Problema:**
```php
// Se $entidade não existe:
$entidade = Entidade::find($request->entidade_id);
$dirigente->vinculos()->create([
    'entidade_id' => $entidade->id,  // Erro silencioso!
]);
```

**Solução:**
- Exception handlers customizados
- Logging estruturado (Monolog)
- Error pages amigáveis

---

## Recomendações Arquiteturais

### R1: Implementar Policies Imediatamente

**Criticidade**: 🔴 BLOQUEANTE

**O que fazer:**
1. Criar `app/Policies/` com:
   - `EntidadePolicy`
   - `DirigentPolicy`
   - `EventoPolicy` (quando chegar a hora)
   - `FinanceiroMovimentoPolicy` (quando chegar a hora)

2. Registrar em `AuthServiceProvider`:
```php
protected $policies = [
    Entidade::class => EntidadePolicy::class,
    Dirigente::class => DirigentPolicy::class,
];
```

3. Usar nos controllers:
```php
$this->authorize('view', $dirigente);
```

4. Usar nas views:
```blade
@can('update', $dirigente)
  <a href="...">Editar</a>
@endcan
```

**Impacto:** Habilita isolamento de dados correto

---

### R2: Refatorar para Services

**Criticidade**: 🟡 ALTO

**O que fazer:**
1. Criar `app/Services/`:
   - `DirigentService.php`
   - `EventoService.php` (Phase 3)
   - `FinanceiroService.php` (Phase 4)

2. Mover lógica complexa:
```php
// De: DirigenteController
// Para: DirigentService
public function criarComVinculoPrincipal(array $data): Dirigente
```

3. Usar transações:
```php
public function criarComVinculoPrincipal(array $data): Dirigente
{
    return DB::transaction(function () use ($data) {
        $dirigente = Dirigente::create($data['dirigente']);
        $dirigente->vinculos()->create($data['vinculo']);
        return $dirigente;
    });
}
```

**Impacto:** Código mais testável e reutilizável

---

### R3: Criar Enums

**Criticidade**: 🟡 MÉDIO

**O que fazer:**
```php
// app/Enums/TipoVinculo.php
enum TipoVinculo: string {
    case Principal = 'principal';
    case Adicional = 'adicional';
    case Coordenacao = 'coordenacao';
}

// app/Enums/Cargo.php
enum Cargo: string {
    case Dirigente = 'dirigente';
    case Coordenador = 'coordenador';
}

// app/Enums/TipoEntidade.php
enum TipoEntidade: string {
    case Diocese = 'diocese';
    case Nucleo = 'nucleo';
    case Secretaria = 'secretaria';
}
```

**Usar em Models:**
```php
protected $casts = [
    'tipo_vinculo' => TipoVinculo::class,
    'cargo' => Cargo::class,
];
```

**Impacto:** Type safety e autocomplete

---

### R4: Implementar Global Scopes para Soft Deletes

**Criticidade**: 🟡 MÉDIO

**O que fazer:**
```php
// app/Traits/SoftDeletesWithoutTrashed.php
trait SoftDeletesWithoutTrashed {
    protected static function bootSoftDeletesWithoutTrashed()
    {
        static::addGlobalScope(function ($query) {
            $query->withoutTrashed();
        });
    }
}

// Usar em Models:
class Dirigente extends Model {
    use SoftDeletesWithoutTrashed;
}
```

**Impacto:** Segurança - deletados não aparecem em queries normais

---

### R5: Otimizar Performance

**Criticidade**: 🟡 MÉDIO

**O que fazer:**
1. **Eager Loading Documentado:**
```php
// DirigenteController
public function index()
{
    // Always eager load vinculos to avoid N+1
    $dirigentes = Dirigente::with('vinculos.entidade')
        ->paginate(15);
    return view('dirigentes.index', compact('dirigentes'));
}
```

2. **Índices Otimizados:**
```php
// Already done in migrations, but verify:
$table->index('dirigente_id');
$table->index('entidade_id');
$table->index(['dirigente_id', 'tipo_vinculo']);  // Composite
```

3. **Selects Limitados:**
```php
Dirigente::select('id', 'nome', 'ativo')
    ->with(['vinculos' => fn($q) => $q->select('id', 'dirigente_id', 'cargo')])
    ->get();
```

**Impacto:** Melhor performance em produção

---

### R6: Estruturar Logging e Error Handling

**Criticidade**: 🟡 MÉDIO

**O que fazer:**
1. **Exception Handler:**
```php
// app/Exceptions/Handler.php
public function register()
{
    $this->reportable(function (ValidationException $e) {
        // Log validation errors
    });
}
```

2. **Custom Exceptions:**
```php
// app/Exceptions/EntidadeNaoEncontradaException
// app/Exceptions/VinculoInvalidoException
```

3. **Logging Estruturado:**
```php
Log::channel('dirigentes')->info('Dirigente criado', [
    'dirigente_id' => $dirigente->id,
    'user_id' => auth()->id(),
    'entidade_id' => $entidade->id,
]);
```

**Impacto:** Debugging e auditoria melhorados

---

### R7: Preparar para API (Sanctum)

**Criticidade**: 🟡 MÉDIO (para Phase 5+)

**O que fazer:**
1. Separar Controllers:
   - `Http/Controllers/Web/DirigenteController`
   - `Http/Controllers/Api/DirigenteController`

2. Resources para JSON:
   ```php
   class DirigenteResource extends JsonResource
   ```

3. Routes separadas:
   ```php
   // routes/web.php - Web UI
   Route::resource('dirigentes', 'Web\DirigenteController');
   
   // routes/api.php - API JSON
   Route::middleware('auth:sanctum')->group(function () {
       Route::apiResource('dirigentes', 'Api\DirigenteController');
   });
   ```

**Impacto:** Pronta para mobile/terceiros

---

## Fases Restantes

### Fase 3: Eventos ✅ COMPLETA

**Objetivo:**
Implementar sistema completo de eventos com suporte a múltiplas entidades, inscrição de dirigentes, check-in e relatório de presença.

**Status:** ✅ Concluída (2026-06-16)

**Regras de Negócio:**
- Um evento é criado por uma entidade
- Pode envolver múltiplas entidades (evento diocesano com vários núcleos)
- Dirigentes se inscrevem através de suas entidades participantes
- Check-in registra presença física (data/hora)
- Ciclo de vida: rascunho → publicado → encerrado/cancelado
- Escopos de acesso: coordenadores, dirigentes, ambos, externos, público

**Tabelas Necessárias:**
```sql
tipo_eventos
├── id, nome, descricao, ativo, timestamps

eventos
├── id, tipo_evento_id, entidade_criadora_id
├── nome, descricao, data_inicio, data_fim, local
├── escopo, status, created_at, updated_at, deleted_at

evento_entidades (pivot)
├── id, evento_id, entidade_id, tipo_participacao
├── created_at, updated_at
├── indices: evento_id, entidade_id, tipo_participacao

evento_participantes
├── id, evento_id, tipo_participante, dirigente_id, participante_externo_id
├── presenca, checkin_em, observacao
├── created_at, updated_at
├── indices: evento_id, dirigente_id, presenca

participante_externos
├── id, nome, telefone, email, documento, genero, data_nascimento
├── created_at, updated_at, deleted_at
```

**Models:**
```
app/Models/
├── TipoEvento
│   ├── eventos() : HasMany
│   └── scopeAtivos()
├── Evento
│   ├── tipoEvento() : BelongsTo
│   ├── criadora() : BelongsTo
│   ├── entidades() : BelongsToMany via evento_entidades
│   ├── participantes() : HasMany
│   ├── dirigentes() : BelongsToMany via evento_participantes
│   ├── scopeProximos() : Builder
│   ├── scopePublicados() : Builder
│   └── scopePorEntidade(entidade_id) : Builder
├── EventoEntidade
│   ├── evento() : BelongsTo
│   ├── entidade() : BelongsTo
│   ├── isOrganizadora() : bool
│   ├── isParticipante() : bool
│   └── isApoio() : bool
├── EventoParticipante
│   ├── evento() : BelongsTo
│   ├── dirigente() : BelongsTo (nullable)
│   ├── participanteExterno() : BelongsTo (nullable)
│   ├── isConfirmado() : bool
│   ├── isPendente() : bool
│   └── isRecusado() : bool
└── ParticipanteExterno
    ├── eventos() : BelongsToMany via evento_participantes
    └── scopeAtivos()
```

**Controllers:**
```
app/Http/Controllers/
├── EventoController
│   ├── index() - list eventos
│   ├── create() - form novo
│   ├── store() - salvar novo
│   ├── show() - detalhe
│   ├── edit() - form editar
│   ├── update() - salvar edição
│   └── destroy() - soft delete
├── EventoEntidadeController (nested)
│   ├── create(evento) - form entidades
│   ├── store(evento) - salvar
│   ├── edit(evento, entidade) - editar
│   ├── update(evento, entidade)
│   └── destroy(evento, entidade)
├── EventoParticipanteController (nested)
│   ├── create(evento) - inscrição form
│   ├── store(evento) - inscrever
│   ├── update(evento, participante) - check-in
│   └── destroy(evento, participante)
└── EventoStatusController
    ├── publish(evento) - publicar
    └── cancel(evento) - cancelar
```

**Form Requests:**
```
app/Http/Requests/
├── StoreEventoRequest - validação criação
├── UpdateEventoRequest - validação edição
├── PublishEventoRequest - validação publicação
├── StoreEventoEntidadeRequest - validação entidade
├── UpdateEventoEntidadeRequest - validação entidade
├── StoreInscricaoRequest - validação inscrição
└── CheckinRequest - validação check-in
```

**Policies:**
```
app/Policies/
├── EventoPolicy
│   ├── viewAny() - listas
│   ├── view() - detalhe
│   ├── create() - criar
│   ├── update() - editar
│   ├── publish() - publicar
│   └── delete()
├── EventoEntidadePolicy
│   ├── create() - adicionar entidade
│   ├── update()
│   └── delete() - remover entidade
└── EventoParticipantePolicy
    ├── create() - inscrever
    ├── update() - check-in
    └── delete() - remover inscrição
```

**Services:**
```
app/Services/
├── EventoService
│   ├── criar(array $data) : Evento
│   ├── publicar(Evento $evento)
│   ├── cancelar(Evento $evento)
│   ├── adicionarEntidade(Evento $evento, Entidade $entidade, string $tipo)
│   └── removerEntidade(Evento $evento, Entidade $entidade)
└── InscricaoService
    ├── inscrever(Evento $evento, Dirigente $dirigente) : EventoParticipante
    ├── confirmarPresenca(EventoParticipante $participante)
    ├── fazerCheckin(Evento $evento, Dirigente $dirigente) : void
    └── cancelarInscricao(EventoParticipante $participante)
```

**Views:**
```
resources/views/
├── eventos/
│   ├── index.blade.php - listagem
│   ├── create.blade.php - formulário novo
│   ├── edit.blade.php - formulário editar
│   ├── show.blade.php - detalhe evento
│   ├── entidades/
│   │   └── manage.blade.php - gerenciar entidades
│   ├── participantes/
│   │   ├── index.blade.php - listagem
│   │   ├── create.blade.php - inscrição
│   │   └── checkin.blade.php - check-in
│   └── relatorios/
│       └── presenca.blade.php - relatório presença
```

**Rotas:**
```php
Route::middleware('auth')->group(function () {
    // Eventos
    Route::resource('eventos', EventoController::class);
    
    // Entidades em eventos
    Route::post('eventos/{evento}/entidades', [EventoEntidadeController::class, 'store'])
        ->name('eventos.entidades.store');
    Route::put('eventos/{evento}/entidades/{entidade}', [EventoEntidadeController::class, 'update'])
        ->name('eventos.entidades.update');
    Route::delete('eventos/{evento}/entidades/{entidade}', [EventoEntidadeController::class, 'destroy'])
        ->name('eventos.entidades.destroy');
    
    // Inscrições
    Route::post('eventos/{evento}/inscrever', [EventoParticipanteController::class, 'store'])
        ->name('eventos.inscrever');
    Route::put('eventos/{evento}/participantes/{participante}/checkin', [EventoParticipanteController::class, 'checkin'])
        ->name('eventos.checkin');
    
    // Publicar/Cancelar
    Route::post('eventos/{evento}/publicar', [EventoStatusController::class, 'publish'])
        ->name('eventos.publicar');
    Route::post('eventos/{evento}/cancelar', [EventoStatusController::class, 'cancel'])
        ->name('eventos.cancelar');
});
```

**Seeders:**
```php
class EventoSeeder {
    // Cria 5 eventos de teste:
    // 1. Reunião de núcleo (local)
    // 2. Encontro diocesano (múltiplos núcleos)
    // 3. Seminário temático (secretaria)
    // 4. Evento passado (encerrado)
    // 5. Evento em rascunho
    
    // Inscreve dirigentes em eventos
}
```

**Testes:**
```
tests/Feature/EventoTest.php
├── create_evento_test()
├── publish_evento_test()
├── cannot_publish_incomplete_test()
├── add_entidade_to_evento_test()
├── cannot_duplicate_entidade_test()
├── inscrever_dirigente_test()
├── cannot_inscrever_de_entidade_nao_participante_test()
├── checkin_test()
├── cannot_checkin_nao_inscrito_test()
└── presenca_report_test()
```

**Critérios de Conclusão:**
- [ ] Evento local (1 entidade) funciona
- [ ] Evento multi-entidade funciona
- [ ] Inscrição de dirigentes funciona
- [ ] Check-in registra presença
- [ ] Relatório de presença gera corretamente
- [ ] Policies de evento funcionam
- [ ] Eventos não publicados não aparecem para usuários
- [ ] Diocese gerencia entidades
- [ ] Núcleo não adiciona outras entidades
- [ ] Testes com 80%+ cobertura

---

### Fase 4: Financeiro (Estimado: 2-3 semanas)

**Objetivo:**
Implementar sistema de movimentações financeiras por entidade, com categorias customizáveis, relatórios e consolidado para diocese.

**Status:** ❌ Não Iniciado

**Regras de Negócio:**
- Cada entidade tem seu próprio financeiro (Diocese, Núcleo, Secretaria)
- Categorias são por entidade (Diocese pode ter "Dízimos", Núcleo não precisa)
- Movimentações podem estar vinculadas a eventos (opcional)
- Tipos: entrada ou saída
- Formas de pagamento: dinheiro, cheque, transferência, PIX, cartão
- Comprovantes: URL para arquivo armazenado
- Auditoria: timestamps em todas operações
- Diocese vê consolidado de toda estrutura

**Tabelas Necessárias:**
```sql
financeiro_categorias
├── id, entidade_id, nome, tipo (entrada/saida)
├── ativo, created_at, updated_at, deleted_at
├── indices: entidade_id, tipo, ativo

financeiro_movimentos
├── id, entidade_id, evento_id (nullable)
├── financeiro_categoria_id, tipo, descricao, valor
├── data_movimento, forma_pagamento, comprovante_url
├── observacao, created_at, updated_at, deleted_at
├── indices: entidade_id, evento_id, data_movimento, tipo
```

**Models:**
```
app/Models/
├── FinanceiroCategoria
│   ├── entidade() : BelongsTo
│   ├── movimentos() : HasMany
│   ├── scopeEntradas() : Builder
│   ├── scopeSaidas() : Builder
│   └── scopeAtivas() : Builder
└── FinanceiroMovimento
    ├── entidade() : BelongsTo
    ├── evento() : BelongsTo (nullable)
    ├── categoria() : BelongsTo
    ├── scopeEntradas() : Builder
    ├── scopeSaidas() : Builder
    ├── scopePorPeriodo(inicio, fim) : Builder
    └── scopePorCategoria(categoria_id) : Builder
```

**Controllers:**
```
app/Http/Controllers/
├── FinanceiroCategoriaController
│   ├── index(entidade)
│   ├── create()
│   ├── store()
│   ├── edit()
│   ├── update()
│   └── destroy()
├── FinanceiroMovimentoController
│   ├── index() - extrato
│   ├── create()
│   ├── store()
│   ├── edit()
│   ├── update()
│   ├── destroy()
│   └── relatorios() - visão consolidada
└── FinanceiroRelatorioController
    ├── extrato() - por período
    ├── fluxoCaixa() - entradas vs saídas
    ├── porCategoria() - distribuição
    ├── consolidado() - diocese com filhos
    └── export(formato) - CSV/PDF
```

**Form Requests:**
```
app/Http/Requests/
├── StoreFinanceiroCategoriaRequest
├── UpdateFinanceiroCategoriaRequest
├── StoreMovimentoRequest
├── UpdateMovimentoRequest
└── RelatorioRequest - filtros
```

**Policies:**
```
app/Policies/
├── FinanceiroCategoriaPolicy
│   ├── viewAny()
│   ├── view()
│   ├── create()
│   └── update() / delete()
└── FinanceiroMovimentoPolicy
    ├── viewAny() - extrato
    ├── view()
    ├── create() - registrar movimento
    ├── update() - editar
    └── delete() - soft delete
```

**Services:**
```
app/Services/
├── FinanceiroService
│   ├── criarMovimento(Entidade $entidade, array $data) : FinanceiroMovimento
│   ├── atualizarMovimento(FinanceiroMovimento $movimento, array $data)
│   └── deletarMovimento(FinanceiroMovimento $movimento)
└── RelatorioFinanceiroService
    ├── extrato(Entidade $entidade, $inicio, $fim) : Collection
    ├── saldo(Entidade $entidade, $ate) : float
    ├── consolidado(Entidade $diocese, $inicio, $fim) : array
    └── gerarPDF(Entidade $entidade, $data) : PDF
```

**Views:**
```
resources/views/
├── financeiro/
│   ├── categorias/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── movimentos/
│   │   ├── index.blade.php - extrato
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── relatorios/
│       ├── extrato.blade.php
│       ├── fluxo_caixa.blade.php
│       ├── consolidado.blade.php
│       └── por_categoria.blade.php
```

**Rotas:**
```php
Route::middleware('auth')->group(function () {
    Route::resource('financeiro/categorias', FinanceiroCategoriaController::class);
    Route::resource('financeiro/movimentos', FinanceiroMovimentoController::class);
    
    Route::get('financeiro/relatorios/extrato', [FinanceiroRelatorioController::class, 'extrato'])
        ->name('financeiro.relatorios.extrato');
    Route::get('financeiro/relatorios/fluxo-caixa', [FinanceiroRelatorioController::class, 'fluxoCaixa'])
        ->name('financeiro.relatorios.fluxo-caixa');
    Route::get('financeiro/relatorios/consolidado', [FinanceiroRelatorioController::class, 'consolidado'])
        ->name('financeiro.relatorios.consolidado');
});
```

**Seeders:**
```php
class FinanceiroSeeder {
    // Cria categorias para Diocese, Núcleo, Secretaria
    // Cria movimentações de teste
    // Vincula alguns movimentos com eventos
}
```

**Testes:**
```
tests/Feature/FinanceiroTest.php
├── criar_categoria_test()
├── registrar_entrada_test()
├── registrar_saida_test()
├── validar_tipo_com_categoria_test()
├── vincular_com_evento_test()
├── relatorio_extrato_test()
├── saldo_periodo_test()
├── consolidado_diocese_test()
└── diocese_nao_ve_outro_diocese_test()
```

**Critérios de Conclusão:**
- [ ] Categorias por entidade funcionam
- [ ] Movimentações registram corretamente
- [ ] Tipo/Categoria são validados
- [ ] Extrato por período funciona
- [ ] Saldo calcula corretamente
- [ ] Diocese vê consolidado
- [ ] Soft deletes mantêm histórico
- [ ] Policies funcionam
- [ ] Testes com 80%+ cobertura

---

### Fase 5: Sistema Completo e Otimizações (Estimado: 3-4 semanas)

**Objetivo:**
Completar o sistema com policies, services, dashboard, check-in com QR, relatórios avançados, API e preparar para produção.

**Componentes:**

#### 5.1: Policies Completas (1 semana)
**Status:** 🔴 CRÍTICO - Deve ser feito ANTES de Fase 3/4

Implementar:
- `EntidadePolicy` - view/create/update/delete/manage
- `DirigentPolicy` - view/create/update/delete/vincular
- `EventoPolicy` - view/create/update/publish/delete
- `EventoParticipantePolicy` - inscrever/checkin/remover
- `FinanceiroCategoriaPolicy` - view/create/update/delete
- `FinanceiroMovimentoPolicy` - view/create/update/delete

**Validações Key:**
```php
// EntidadePolicy
- Diocese A não acessa Diocese B
- Núcleo visualiza diocese, não edita
- Admin acessa tudo
- Diocese edita filhos para supervisão

// DirigentPolicy
- Diocese vê dirigentes de estrutura
- Núcleo gerencia próprios
- Não cross-diocese access

// EventoPolicy
- Criador edita se rascunho
- Diocese edita filhos
- Somente admin publica diocesano

// FinanceiroPolicy
- Entidade vê próprio
- Diocese vê filhos (supervisão)
- No cross-entidade access
```

#### 5.2: Services Completos (1 semana)

Refatorar de Controllers para Services:

**DirigentService**
```php
- criarComVinculoPrincipal()
- adicionarVinculo()
- removerVinculo()
- mudarNucleoPrincipal()
- desativar()
```

**EventoService**
```php
- criar()
- publicar()
- cancelar()
- adicionarEntidade()
- removerEntidade()
- mudarStatus()
```

**InscricaoService**
```php
- inscrever()
- confirmarPresenca()
- fazerCheckin()
- cancelarInscricao()
```

**FinanceiroService**
```php
- criarMovimento()
- atualizarMovimento()
- deletarMovimento() [soft delete]
- validarCategoria()
```

**RelatorioService**
```php
- gerarExtrato()
- gerarFluxoCaixa()
- gerarConsolidado()
- exportarCSV()
- exportarPDF()
```

#### 5.3: Dashboard Executivo (1 semana)

**Página Principal por Tipo de Usuário:**

**Admin Dashboard:**
```
- Resumo: Total de dioceses, núcleos, dirigentes
- Atividades recentes: últimos eventos, movimentos
- Alerts: dirigentes sem vínculo principal, eventos sem entidades
- Gráfico: crescimento de dirigentes
```

**Diocese Dashboard:**
```
- Estrutura: núcleos, secretarias, dirigentes
- Próximos eventos: diocesanos e filhos
- Financeiro: saldo, entradas/saídas mês
- Alerts: vencimentos de eventos, anomalias financeiras
```

**Núcleo Dashboard:**
```
- Dirigentes: total, ativos, cargos
- Próximos eventos: próprios
- Financeiro: saldo, últimas movimentações
- Check-ins: presença em eventos
```

**Componentes:**
- Widgets de resumo (cards)
- Gráficos (Chart.js ou similar)
- Listas de atividades
- Alerts e notificações

#### 5.4: Check-in com QR Code (1 semana)

**Funcionalidade Básica:**
```
1. Gerar QR code para cada dirigente
   - QR = dirigente UUID
   - Exibir na tela, imprimir

2. Scanner durante evento
   - Input de câmera/barcode reader
   - Ler UUID do dirigente
   - Registrar checkin_em

3. Relatório de presença
   - Quem chegou/não chegou
   - Horário
   - Exportar lista
```

**Implementação:**
```php
// Model Dirigente
- $dirigente->uuid // usado para QR
- gerarQRCode() // gera imagem
- qrCodeUrl() // retorna URL

// Controller
- EventoParticipanteController@checkin
- Verificar dirigente inscrito
- Registrar checkin_em = NOW()
- Retornar confirmação

// View
- Form com input QR
- Biblioteca: jsQR ou BarcodeDetector API
```

#### 5.5: Relatórios Avançados (1 semana)

**Relatórios Financeiros:**
- Extrato detalhado com filtros
- Fluxo de caixa (gráfico)
- Consolidado (Diocese vê filhos)
- Análise por categoria
- Análise por forma de pagamento

**Relatórios de Eventos:**
- Presença por evento
- Comparativo de frequência
- Análise de tipos de evento
- Dirigentes mais ativos

**Relatórios de Dirigentes:**
- Listagem por entidade
- Por cargo/vinculação
- Histórico de vínculos
- Análise demográfica (idade, gênero)

**Exportação:**
- CSV (tabular)
- PDF (formatado)
- Excel (com gráficos)

#### 5.6: API com Sanctum (2 semanas)

**Endpoints para Mobile/Terceiros:**
```
POST /api/auth/login - autenticação
POST /api/auth/logout - logout

GET /api/dirigentes - listar
POST /api/dirigentes - criar
GET /api/dirigentes/{id} - detalhe
PUT /api/dirigentes/{id} - atualizar
DELETE /api/dirigentes/{id} - deletar

GET /api/eventos - listar
POST /api/eventos/{id}/inscrever - inscrever
PUT /api/eventos/{id}/checkin - check-in

GET /api/financeiro - extrato
POST /api/financeiro - novo movimento
```

**Implementação:**
- Resources (JSON)
- Pagination
- Filtering/Sorting
- Rate limiting
- CORS
- API documentation (OpenAPI/Swagger)

#### 5.7: Auditoria e Logs (1 semana)

**Implementar:**
- Logging de ações críticas
- Quem fez quê, quando
- Histórico de mudanças
- Alerts de atividades suspeitas

**Tabelas:**
```sql
audit_logs
├── id, user_id, model_type, model_id
├── action, old_values, new_values
├── ip_address, user_agent, created_at
```

#### 5.8: Configurações do Sistema (3 dias)

**Painel de Configuração:**
- Informações da Diocese (nome, email, logo)
- Tipos de evento customizáveis
- Categorias padrão de financeiro
- Regras de negócio (ativáveis/desativáveis)
- Integrações externas

---

### Cronograma Estimado

| Fase | Duração | Esforço | Status |
|------|---------|--------|--------|
| 1 - Foundation | 2 sem | 80h | ✅ COMPLETA |
| 2 - Dirigentes | 2 sem | 100h | ✅ COMPLETA |
| 2.5 - Ajustes Arquiteturais | 1 sem | 40h | ✅ COMPLETA |
| 3 - Eventos | 3 sem | 150h | ✅ COMPLETA |
| 4 - Financeiro | 3 sem | 150h | ✅ COMPLETA |
| 5 - Sistema Completo | 4 sem | 200h | ⏳ Próxima |
| **TOTAL** | **15 semanas** | **720h** | **~60% completo** |

---

## Roadmap Final

### Ordem de Implementação Recomendada

**PRIORIDADE 1 - BLOQUEANTES (Antes de Fase 3):** ✅
1. ✅ Policies de Autorização (EntidadePolicy, DirigentPolicy)
2. ✅ Global Scopes para Soft Deletes
3. ✅ Enums para Enumerações
4. ✅ Services para lógica complexa

**PRIORIDADE 2 - Fase 3 (Eventos):** ✅ COMPLETA
5. ✅ Tabelas de Eventos
6. ✅ Models de Eventos
7. ✅ Controllers de Eventos
8. ✅ EventoPolicy e EventoParticipantePolicy
9. ✅ EventoService + ParticipanteService
10. ✅ Views de Eventos
11. ✅ Check-in e Relatório de Presença
12. ✅ Testes implementados

**PRIORIDADE 3 - Fase 4 (Financeiro):** ✅ COMPLETA
13. ✅ Tabelas de Financeiro
14. ✅ Models de Financeiro
15. ✅ Controllers de Financeiro
16. ✅ FinanceiroCategoriaPolicy e FinanceiroMovimentoPolicy
17. ✅ FinanceiroService com cálculos de saldo e período
18. ✅ Views de Financeiro (categorias, movimentos, relatórios)
19. ✅ Relatórios Básicos (extrato, resumo)
20. ✅ Testes (8 testes passando com 100% sucesso)

**PRIORIDADE 4 - Fase 5 (Finalizações):**
21. Dashboard Executivo
22. Check-in com QR Code
23. Relatórios Avançados (gráficos, exportações)
24. API com Sanctum (recursos)
25. Auditoria e Logs
26. Configurações do Sistema
27. Documentação Completa
28. Deploy em Produção

### Decisões Críticas

**Deve ser Tomadas AGORA:**
1. ✅ Implementar Policies (IMEDIATO)
2. ✅ Refatorar para Services
3. ✅ Criar Enums para type safety
4. ✅ Definir estrutura de testes
5. ✅ Estruturar logging/errors

**Pode Esperar para Fase 5:**
- API com Sanctum
- App Mobile
- Auditoria detalhada
- Relatórios com gráficos avançados
- Integrações externas

---

## Critérios de Conclusão

### Por Fase

#### Fase 1 ✅
- [x] Usuários fazem login
- [x] Cada usuário vê sua diocese/entidade
- [x] CRUD de Entidades funciona
- [x] Hierarquia é respeitada

#### Fase 2 ✅
- [x] Dirigentes são criados com vínculo principal obrigatório
- [x] Vínculos seguem regras (principal/adicional/coordenacao)
- [x] Dirigentes listados por entidade
- [x] 10 testes passando

#### Fase 3 ✅ COMPLETA
- [x] Eventos locais (1 entidade) funcionam
- [x] Eventos multi-entidade funcionam
- [x] Inscrição de dirigentes funciona
- [x] Check-in registra presença
- [x] Relatório de presença existe
- [x] EventoPolicy implementada
- [x] EventoParticipantePolicy implementada
- [x] Services implementadas (EventoService, ParticipanteService)
- [x] Validações e Form Requests completas
- [x] Views básicas de eventos
- [x] Seeders de eventos
- [x] Testes implementados e passando

#### Fase 4
- [ ] CRUD de Categorias funciona
- [ ] CRUD de Movimentações funciona
- [ ] Categorias são por entidade
- [ ] Relatório de extrato funciona
- [ ] Consolidado para Diocese funciona
- [ ] FinanceiroPolicy implementada
- [ ] 80%+ cobertura de testes

#### Fase 5
- [ ] Todas as Policies implementadas
- [ ] Todos os Services implementados
- [ ] Dashboard por tipo de usuário
- [ ] QR Code e Check-in funcionam
- [ ] Relatórios com gráficos
- [ ] API com endpoints principais
- [ ] Logging estruturado
- [ ] 80%+ cobertura total de testes
- [ ] Documentação completa
- [ ] Deploy testado

### Critérios Transversais

**Qualidade de Código:**
- [ ] Sem warnings PHP/Laravel
- [ ] PSR-12 compliance
- [ ] Code review aprovado
- [ ] Documentação inline onde necessário

**Segurança:**
- [ ] Todas as rotas autenticadas
- [ ] Policies em todos controllers
- [ ] SQL Injection prevenido (use ORM)
- [ ] CSRF protection ativo
- [ ] Senhas com bcrypt
- [ ] Soft deletes protegem dados

**Performance:**
- [ ] Sem N+1 queries
- [ ] Eager loading documentado
- [ ] Índices otimizados
- [ ] Paginação onde apropriado
- [ ] Cache implementado para dados pesados

**Testes:**
- [ ] 80%+ cobertura geral
- [ ] Tests para regras críticas
- [ ] Feature tests para workflows
- [ ] Unit tests para services
- [ ] Policy tests para autorização

**Documentação:**
- [ ] README atualizado
- [ ] Guia de setup
- [ ] Documentação de regras de negócio
- [ ] API docs (quando houver)
- [ ] Guia de contribuição

---

## Riscos e Mitigação

### Riscos Identificados

#### R1: Policies Incompletas (CRÍTICO)

**Risco:** Implementar Eventos/Financeiro sem Policies completas.  
**Impacto:** Violação de segurança - usuários acessam dados de outras dioceses  
**Probabilidade:** Alta (fácil esquecer)  
**Mitigação:**
- ✅ Implementar EntidadePolicy + DirigentPolicy ANTES de Fase 3
- Usar `$this->authorize()` em TODOS controllers
- Code review checklist inclui "Policies implementadas"

#### R2: N+1 Queries em Listagens

**Risco:** Performance degrada com crescimento de dados  
**Impacto:** Sistema fica lento em produção  
**Probabilidade:** Média  
**Mitigação:**
- Documentar eager loading esperado
- Usar Laravel Debugbar em desenvolvimento
- Testes de performance antes de produção

#### R3: Regras de Negócio Violadas

**Risco:** Dirigente criado sem vínculo principal, evento publicado incompleto  
**Impacto:** Integridade de dados  
**Probabilidade:** Média  
**Mitigação:**
- Form Requests com validações completas
- Services com transações
- Testes que cobrem violações

#### R4: Soft Deletes Retornam Deletados

**Risco:** Queries retornam registros deletados inadvertidamente  
**Impacto:** Dados incorretos em relatórios  
**Probabilidade:** Média  
**Mitigação:**
- Implementar Global Scope
- Testes verificam que deletados não aparecem

#### R5: Falta de Logging

**Risco:** Não conseguir auditar quem fez o quê  
**Impacto:** Compliance, debugging  
**Probabilidade:** Alta  
**Mitigação:**
- Estruturar logging desde cedo
- Usar Observer pattern para ações críticas

#### R6: Autorização Granular Complexa

**Risco:** Policies não cobrem todos os casos  
**Impacto:** Bugs de autorização  
**Probabilidade:** Alta  
**Mitigação:**
- Documentar matriz de permissões
- Testes de Policy extensivos
- Review com stakeholders

#### R7: API não está planejada

**Risco:** Mobile/terceiros precisam de API, mas não foi planejada desde início  
**Impacto:** Refatoração cara depois  
**Probabilidade:** Média  
**Mitigação:**
- Separar Controllers Web/API desde Fase 3
- Usar Resources para JSON
- Routes separadas

#### R8: Banco de Dados Cresce Demais

**Risco:** Auditorias, logs, históricos ocupam espaço excessivo  
**Impacto:** Backup/restore lento  
**Probabilidade:** Baixa (mas importante prever)  
**Mitigação:**
- Considerar data retention policies
- Arquivar dados históricos
- Indexes bem planejados

---

## Próximas Ações Recomendadas

### Fase 3 Concluída ✅ (2026-06-16)

A Fase 3 - Eventos foi completamente implementada com sucesso. Todos os critérios de conclusão foram atendidos:
- ✅ Sistema de eventos com suporte a múltiplas entidades
- ✅ Inscrição e check-in de dirigentes
- ✅ Relatório de presença
- ✅ Policies de autorização
- ✅ Services para lógica de negócio
- ✅ Testes e validações

### Update: Menu/Sidebar Reorganizado com Heroicons ✅ (2026-06-17)

Atualização da navegação do sistema com foco em UX/UI:

**Implementado:**
- ✅ Instalação de Blade Icons e Blade Heroicons
- ✅ Reorganização do menu em duas seções:
  - **Sistema**: Dashboard, Entidades, Dirigentes, Eventos, Tipos de Evento, Participantes Externos, Financeiro (submenu), Relatórios (submenu), Auditoria, Check-in, API
  - **Referências TailAdmin**: Grupo expansível com todas as páginas de exemplo do template
- ✅ Substituição de ícones SVG inline por Heroicons modernos e consistentes
- ✅ Componente `menu-icon.blade.php` para renderização dinâmica de ícones
- ✅ Atualização do MenuHelper com nova estrutura de grupos e rotas
- ✅ Estados ativos mantidos funcionando corretamente
- ✅ Responsividade preservada
- ✅ Testes passando

**Arquivos Alterados:**
- `app/Helpers/MenuHelper.php` - Nova estrutura de menu
- `resources/views/layouts/sidebar.blade.php` - Renderização com componentes
- `resources/views/components/menu-icon.blade.php` - Novo componente de ícone
- `database/factories/UserFactory.php` - Fix: adicionar tipo_usuario padrão
- `composer.json` / `composer.lock` - Blade Icons + Heroicons instalados

**Benefícios:**
- Menu mais intuitivo e organizado
- Ícones modernos e profissionais
- Melhor visual do produto
- Facilita onboarding de novos usuários
- Template padrão do TailAdmin isolado em seção de referência

### Próximos Passos: IMEDIATO (Próxima Semana)

**Iniciar Fase 5.6 - Polimentos Finais**

1. **Implementar Tabelas Financeiras** 
   ```php
   app/Policies/
   ├── EntidadePolicy.php
   ├── DirigentPolicy.php
   ```
   - Registrar em AuthServiceProvider
   - Usar em Controllers
   - Testes de Policy

2. **Criar Enums**
   ```php
   app/Enums/
   ├── TipoEntidade.php
   ├── TipoVinculo.php
   ├── Cargo.php
   ├── TipoEvento.php
   ├── EscopoEvento.php
   ├── StatusEvento.php
   ├── TipoParticipacao.php
   ├── TipoMovimento.php
   └── FormaaPagamento.php
   ```

3. **Refatorar para Services**
   ```php
   app/Services/
   ├── DirigentService.php
   ```
   - Mover lógica de DirigenteController
   - Testes unitários

4. **Implementar Global Scopes**
   - Trait SoftDeletesProtegido
   - Usar em Models com Soft Deletes

5. **Estruturar Logging**
   - Criar LogService
   - Registrar ações críticas

### CURTO PRAZO (Próximas 2 Semanas)

6. Completar Policies (EventoPolicy, FinanceiroPolicy)
7. Completar Services
8. Melhorar cobertura de testes (Target: 80%)
9. Otimizar queries (eager loading)

### MÉDIO PRAZO (Próximas 4 Semanas)

10. Iniciar Fase 3 (Eventos)
11. Dashboard básico
12. Testes de integração

### LONGO PRAZO (Próximas 8+ Semanas)

13. Fase 4 (Financeiro)
14. Fase 5 (Finalizações)
15. Produção

---

## Conclusão

O projeto TLC Admin completou com sucesso as **Fases 1-4** e **Fase 5 MVP - Dashboard, API e Auditoria**! 

**Status Atual:** 🟡 ~80% Completo (Fase 1 + 2 + 2.5 + 3 + 4 + 5 MVP)

**Progresso:**
- ✅ Fase 1: Foundation - COMPLETA
- ✅ Fase 2: Dirigentes e Vínculos - COMPLETA
- ✅ Fase 2.5: Ajustes Arquiteturais - COMPLETA
- ✅ Fase 3: Eventos - COMPLETA
- ✅ Fase 4: Financeiro - COMPLETA
- 🟡 Fase 5: Dashboard, API, Auditoria - MVP CONCLUÍDA (~65% implementada)
  - ✅ 5.1 Dashboard Estrutura Base
  - ✅ 5.2 Autenticação Web Real
  - ✅ 5.3 Sanctum/API (15+ endpoints)
  - ✅ 5.4 Auditoria e Logs
  - ✅ 5.5 Relatórios Avançados Básicos
  - ✅ 5.6 QR Code e Check-in Refinado
  - ✅ 5.7 Testes (37/37 passando)
  - ⏳ Dashboard Executivo com KPIs (próximo)
  - ⏳ Gráficos Interativos Avançados
  - ⏳ Exportação PDF/Excel Avançada
  - ⏳ Documentação OpenAPI/Swagger

**Destaques da Fase 5 MVP:**
- Autenticação funcional com login/logout
- API REST com Sanctum (15+ endpoints protegidos)
- Auditoria completa com logging estruturado
- Relatórios de financeiro, eventos, dirigentes (CSV)
- QR Code para dirigentes e Check-in em eventos
- Testes com 37/37 passando
- Views responsivas com Tailwind CSS

**Pendências da Fase 5:**
- Dashboard Executivo com KPIs por tipo de usuário
- Gráficos interativos (Chart.js/ApexCharts)
- Exportação PDF/Excel com formatação
- Documentação OpenAPI/Swagger
- Otimizações finais de performance/produção
- Rate limiting e CORS avançado

**Próximo Passo:** Implementar **Dashboard Executivo e Gráficos Interativos**

**Estimativa até Produção:** ~2-3 semanas (~100-120 horas restantes)

**Recomendação:** Priorizar Dashboard executivo, gráficos interativos e documentação Swagger para produção.

---

**Documento Atualizado:** 2026-06-17  
**Versão:** 3.1  
**Status:** 🟡 Fase 5 MVP Concluída - Pendências: Dashboard, Gráficos, Exportações PDF/Excel, Documentação Swagger
