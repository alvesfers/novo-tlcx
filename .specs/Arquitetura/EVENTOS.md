# Sistema de Eventos

**Status:** ✅ IMPLEMENTADO (Fase 3 Concluída - 2026-06-16)

## Conceito Geral

Eventos são atividades que podem envolver uma ou múltiplas entidades. Um evento é criado por uma entidade mas pode ter participantes de outras entidades, dirigentes e participantes externos.

Este documento descreve a arquitetura, modelo de dados e regras de negócio do sistema de eventos totalmente implementado no TLC Admin.

## Tabela: `tipo_eventos`

Tipos pré-definidos de eventos (ou categorias).

```
Coluna          Tipo              Descrição
────────────────────────────────────────────────────
id              BigInt PK         Identificador único
nome            String            Nome do tipo (ex: "Reunião", "Encontro")
descricao       Text              Descrição do tipo
ativo           Boolean           Tipo disponível para uso
created_at      Timestamp         Data de criação
updated_at      Timestamp         Data de atualização
```

## Tabela: `eventos`

Eventos do sistema.

```
Coluna                  Tipo              Descrição
──────────────────────────────────────────────────────────
id                      BigInt PK         Identificador único
tipo_evento_id          BigInt FK         Tipo de evento
entidade_criadora_id    BigInt FK         Entidade que criou o evento
nome                    String            Nome do evento
descricao               Text              Descrição/detalhes
data_inicio             DateTime          Início do evento
data_fim                DateTime          Fim do evento
local                   String            Local do evento
escopo                  Enum              coordenadores, dirigentes, ambos, externos, publico
status                  Enum              rascunho, publicado, encerrado, cancelado
created_at              Timestamp         Data de criação
updated_at              Timestamp         Data de atualização
deleted_at              Timestamp         Soft delete
```

### Detalhes de Campos

- **tipo_evento_id**: Categorização do evento (padrões pré-definidos)
- **entidade_criadora_id**: Qual entidade criou o evento
- **escopo**: Define quem pode ver/participar:
  - `coordenadores`: Apenas coordenadores de entidades
  - `dirigentes`: Apenas dirigentes das entidades
  - `ambos`: Coordenadores e dirigentes
  - `externos`: Permite participantes externos
  - `publico`: Sem restrição (futura integração)

- **status**: Ciclo de vida do evento:
  - `rascunho`: Sendo preparado, não publicado
  - `publicado`: Disponível para inscrição
  - `encerrado`: Evento finalizou
  - `cancelado`: Evento cancelado (histórico)

## Tabela: `evento_entidades`

Tabela pivô que indica quais entidades participam de um evento.

```
Coluna              Tipo              Descrição
────────────────────────────────────────────────────
id                  BigInt PK         Identificador único
evento_id           BigInt FK         Referência ao evento
entidade_id         BigInt FK         Referência à entidade
tipo_participacao   Enum              organizadora, participante, apoio
created_at          Timestamp         Data de criação
updated_at          Timestamp         Data de atualização
```

### Detalhes de Campos

- **tipo_participacao**:
  - `organizadora`: Entidade que organiza/gerencia o evento
  - `participante`: Entidade que participa (enviará dirigentes)
  - `apoio`: Entidade que fornece apoio (recursos, local, etc)

**Nota**: A entidade_criadora também deve ter registro em evento_entidades com tipo_participacao = 'organizadora'

## Tabela: `participante_externos`

Participantes que não são dirigentes do sistema.

```
Coluna              Tipo              Descrição
────────────────────────────────────────────────────
id                  BigInt PK         Identificador único
nome                String            Nome completo
telefone            String            Telefone de contato
email               String            Email
documento           String            Documento (CPF/RG)
genero              Enum              m, f, outro
data_nascimento     Date              Data de nascimento
created_at          Timestamp         Data de criação
updated_at          Timestamp         Data de atualização
deleted_at          Timestamp         Soft delete
```

**Nota**: Foco inicial é dirigentes. Externos serão integrados conforme necessário.

## Tabela: `evento_participantes`

Participação de dirigentes ou externos em eventos com presença.

```
Coluna                  Tipo              Descrição
──────────────────────────────────────────────────────
id                      BigInt PK         Identificador único
evento_id               BigInt FK         Referência ao evento
tipo_participante       Enum              dirigente, externo
dirigente_id            BigInt FK         Referência a dirigente (nullable)
participante_externo_id BigInt FK         Referência a externo (nullable)
presenca                Enum              confirmado, pendente, recusado
checkin_em              DateTime          Timestamp do check-in
observacao              Text              Notas sobre participação
created_at              Timestamp         Data de criação
updated_at              Timestamp         Data de atualização
```

### Detalhes de Campos

- **tipo_participante**: Indica se é dirigente do sistema ou externo
- **dirigente_id** e **participante_externo_id**: Exatamente um deve ter valor (validação)
- **presenca**:
  - `confirmado`: Dirigente confirmou presença
  - `pendente`: Aguardando confirmação
  - `recusado`: Dirigente recusou participação
- **checkin_em**: Registra quando o dirigente chegou (presença física)

## Regras de Eventos

### Regra 1: Evento Pertence a Entidades
```
Um evento sempre é criado por uma entidade e pode envolver múltiplas entidades.
```

**Implicações**:
- entidade_criadora_id é obrigatório
- Deve existir registro em evento_entidades com tipo_participacao = 'organizadora'
- Outras entidades podem ser adicionadas como 'participante' ou 'apoio'

### Regra 2: Flexibilidade de Participação
```
Um evento pode envolver:
- Apenas uma entidade (evento local)
- Múltiplos núcleos (evento regional)
- Núcleos + Secretarias (evento multi-entidade)
- Diocese + suas estruturas (evento diocesano)
```

**Exemplos**:
```
1. Reunião do Núcleo X
   evento_entidades: Núcleo X (organizadora)

2. Encontro diocesano
   evento_entidades: Diocese (organizadora), Núcleo A (participante), Núcleo B (participante)

3. Seminário temático
   evento_entidades: Secretaria Y (organizadora), Núcleo X (participante), Diocese (apoio)
```

### Regra 3: Escopos Definem Visibilidade
```
O escopo do evento define quem pode ver e participar.
```

**Implementação**:
- `coordenadores`: Apenas usuários com cargo = coordenador veem
- `dirigentes`: Todos com vínculo dirigente veem
- `ambos`: Dirigentes e coordenadores veem
- `externos`: Permite participantes externos (futura validação)
- `publico`: Sem restrição (futura integração web)

### Regra 4: Ciclo de Vida
```
Evento progride: rascunho -> publicado -> encerrado/cancelado
```

**Validações**:
- Só se pode publicar se evento está completo (tem datas, local, entidades)
- Não editar datas/local se publicado
- Cancelar mantém histórico (soft delete possível com status)
- Evento encerrado é de leitura

### Regra 5: Participação de Dirigentes
```
Dirigentes são adicionados a eventos através de suas entidades.
Quando um evento tem uma entidade como participante, seus dirigentes podem ser inscritos.
```

**Fluxo**:
1. Diocese cria evento com múltiplas entidades
2. Núcleo A é adicionado como 'participante'
3. Liderança do Núcleo A inscreve seus dirigentes
4. Dirigentes do Núcleo A aparecem em evento_participantes

## Tabelas Relacionadas

- **entidades**: Criadora e participantes
- **tipo_eventos**: Categorização
- **dirigentes**: Participantes que são dirigentes
- **participante_externos**: Participantes que são externos
- **financeiro_movimentos**: Evento pode estar vinculado a movimentações (despesas, arrecadação)

## Queries Comuns

```sql
-- Eventos de uma entidade (como organizadora)
SELECT e.* FROM eventos e
WHERE e.entidade_criadora_id = ? AND e.deleted_at IS NULL
ORDER BY e.data_inicio DESC

-- Eventos em que uma entidade participa (com qualquer tipo_participacao)
SELECT e.* FROM eventos e
JOIN evento_entidades ee ON e.id = ee.evento_id
WHERE ee.entidade_id = ? AND e.deleted_at IS NULL
ORDER BY e.data_inicio DESC

-- Próximos eventos (não encerrados)
SELECT e.* FROM eventos e
WHERE e.data_inicio > NOW() AND e.status != 'encerrado'
ORDER BY e.data_inicio ASC

-- Dirigentes inscritos em um evento
SELECT d.*, ep.presenca, ep.checkin_em FROM dirigentes d
JOIN evento_participantes ep ON d.id = ep.dirigente_id
WHERE ep.evento_id = ? AND ep.tipo_participante = 'dirigente'

-- Check-in de dirigentes (presentes)
SELECT d.* FROM dirigentes d
JOIN evento_participantes ep ON d.id = ep.dirigente_id
WHERE ep.evento_id = ? AND ep.checkin_em IS NOT NULL

-- Entidades de um evento
SELECT e.*, ee.tipo_participacao FROM entidades e
JOIN evento_entidades ee ON e.id = ee.entidade_id
WHERE ee.evento_id = ?
```

## Estados de Evento

### Rascunho
- Sendo preparado
- `status = 'rascunho'`
- Pode ser editado livremente
- Não visível para participantes
- Pode ser publicado ou descartado

### Publicado
- Disponível para inscrição
- `status = 'publicado'`
- Dirigentes podem se inscrever
- Edição limitada (não data/local)
- Pode ser cancelado

### Encerrado
- Evento finalizou
- `status = 'encerrado'`
- Leitura apenas
- Histórico mantido
- Relatórios gerados

### Cancelado
- Evento cancelado
- `status = 'cancelado'`
- Não aparece em listas ativas
- Histórico mantido (soft delete possível)
- Movimentações financeiras mantêm-se

## Políticas de Autorização

### Quem pode criar evento?
- **Admin**: Sim
- **Diocese**: Sim (eventos diocesanos)
- **Núcleo**: Sim (eventos do núcleo)
- **Secretaria**: Sim (eventos da secretaria)

### Quem pode ver evento?
- Criador: Sim
- Entidades participantes: Sim (conforme escopo)
- Diocese da criadora: Sim (supervisão)
- Admin: Sim

### Quem pode editar evento?
- Criador: Sim (se rascunho)
- Diocese: Sim (supervisão)
- Admin: Sim

### Quem pode inscrever dirigente?
- Liderança da entidade participante: Sim
- Diocese: Sim
- Admin: Sim

### Quem pode fazer check-in?
- Liderança da entidade: Sim
- Coordenadores: Sim
- Admin: Sim

## Casos de Uso

### UC1: Criar evento diocesano
```
Ator: Liderança da Diocese
Fluxo:
1. Acessa "Novo Evento"
2. Preenche: nome, tipo, datas, local, descrição
3. Seleciona escopo (ex: 'dirigentes')
4. Seleciona entidades participantes (Diocese + núcleos)
5. Define tipo_participacao para cada:
   - Diocese = organizadora
   - Núcleos = participante
6. Publica evento
7. Núcleos recebem notificação
```

### UC2: Inscrever dirigentes em evento
```
Ator: Liderança do Núcleo
Pré-condição: Evento publicado, núcleo é participante
Fluxo:
1. Acessa evento
2. Visualiza formulário de inscrição
3. Seleciona dirigentes do núcleo
4. Inscreve (status = confirmado ou pendente)
5. Sistema cria evento_participantes
6. Dirigentes recebem notificação
```

### UC3: Check-in de dirigente
```
Ator: Coordenador na entrada
Pré-condição: Evento está ocorrendo
Fluxo:
1. Scanneia QR code do dirigente
2. Sistema identifica dirigente
3. Procura evento_participantes
4. Atualiza checkin_em = NOW()
5. Exibe confirmação
```

### UC4: Listar eventos com filtros
```
Ator: Qualquer usuário
Fluxo:
1. Acessa "Meus Eventos"
2. Filtra por:
   - Data (próximos, passados)
   - Entidade
   - Status
   - Tipo
3. Ordena por data_inicio
4. Exibe resumo com participação
```

## Validações do Negócio

### Ao criar evento
- [ ] Entidade criadora existe
- [ ] Nome não vazio
- [ ] Data início <= data fim
- [ ] Data fim no futuro
- [ ] Tipo evento válido
- [ ] Escopo válido

### Ao publicar evento
- [ ] Evento está em rascunho
- [ ] Tem ao menos uma entidade participante
- [ ] Datas estão preenchidas
- [ ] Local preenchido

### Ao adicionar entidade
- [ ] Entidade existe
- [ ] Tipo de participação válido
- [ ] Não duplicar entidade em mesmo evento

### Ao inscrever dirigente
- [ ] Dirigente existe e está ativo
- [ ] Evento está publicado
- [ ] Entidade do dirigente é participante
- [ ] Não duplicar inscrição

### Ao fazer check-in
- [ ] Evento está ocorrendo (data_inicio <= NOW <= data_fim)
- [ ] Dirigente está inscrito
- [ ] Conferir presença = confirmado

## Implementação Sugerida

### Models
```php
// Evento.php
class Evento extends Model {
    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];
    
    public function entidades() { ... }
    public function criadora() { ... }
    public function participantes() { ... }
    public function dirigentes() { ... }
    
    public function scopeProximos($query) { ... }
    public function scopePublicados($query) { ... }
}

// EventoEntidade.php (Pivot)
class EventoEntidade extends Pivot { }

// EventoParticipante.php
class EventoParticipante extends Model { }
```

### Services
- `EventoService`: Criar, publicar, gerenciar entidades
- `InscricaoService`: Inscrever dirigentes, check-in
- `EventoAuthorizationService`: Permissões

### Policies
- `EventoPolicy`: Criar, ver, editar, deletar
- `EventoParticipantePolicy`: Inscrever, check-in

### Form Requests
- `StoreEventoRequest`: Criação
- `UpdateEventoRequest`: Edição
- `StoreInscricaoRequest`: Inscrição

---

## ✅ Status de Implementação da Fase 3

### Arquivos Implementados

**Migrations:**
- ✅ `*_create_tipo_eventos_table.php`
- ✅ `*_create_eventos_table.php`
- ✅ `*_create_evento_entidades_table.php`
- ✅ `*_create_evento_participantes_table.php`
- ✅ `*_create_participante_externos_table.php`

**Models:**
- ✅ `app/Models/TipoEvento.php`
- ✅ `app/Models/Evento.php`
- ✅ `app/Models/EventoEntidade.php`
- ✅ `app/Models/EventoParticipante.php`
- ✅ `app/Models/ParticipanteExterno.php`

**Controllers:**
- ✅ `app/Http/Controllers/EventoController.php`
- ✅ `app/Http/Controllers/EventoEntidadeController.php`
- ✅ `app/Http/Controllers/EventoParticipanteController.php`
- ✅ `app/Http/Controllers/TipoEventoController.php`
- ✅ `app/Http/Controllers/ParticipanteExternoController.php`

**Form Requests:**
- ✅ `app/Http/Requests/StoreEventoRequest.php`
- ✅ `app/Http/Requests/UpdateEventoRequest.php`
- ✅ `app/Http/Requests/StoreEventoEntidadeRequest.php`
- ✅ `app/Http/Requests/StoreParticipanteRequest.php`

**Policies:**
- ✅ `app/Policies/EventoPolicy.php`
- ✅ `app/Policies/EventoParticipantePolicy.php`

**Services:**
- ✅ `app/Services/EventoService.php`
- ✅ `app/Services/ParticipanteService.php`

**Views:**
- ✅ `resources/views/eventos/index.blade.php`
- ✅ `resources/views/eventos/create.blade.php`
- ✅ `resources/views/eventos/edit.blade.php`
- ✅ `resources/views/eventos/show.blade.php`
- ✅ `resources/views/eventos/entidades/` (manage.blade.php)
- ✅ `resources/views/eventos/participantes/` (index, create, checkin)
- ✅ `resources/views/eventos/relatorios/` (presenca.blade.php)
- ✅ `resources/views/tipo-eventos/` (CRUD básico)
- ✅ `resources/views/participante-externos/` (CRUD básico)

**Seeders:**
- ✅ `database/seeders/EventoSeeder.php`

**Testes:**
- ✅ `tests/Feature/EventoTest.php`

**Enums:**
- ✅ `app/Enums/` - Enums de eventos (EscopoEvento, StatusEvento, TipoParticipacao, PresencaParticipante, etc.)

### Funcionalidades Implementadas

**CRUD de Eventos:**
- ✅ Criar evento (com entidade criadora)
- ✅ Editar evento (em rascunho)
- ✅ Publicar evento
- ✅ Listar eventos (com filtros e paginação)
- ✅ Visualizar detalhes do evento
- ✅ Deletar evento (soft delete)

**Gerenciar Entidades em Eventos:**
- ✅ Adicionar entidades ao evento (multi-entidade)
- ✅ Definir tipo_participacao (organizadora, participante, apoio)
- ✅ Remover entidades do evento
- ✅ Validação: entidade criadora é organizadora automática

**Inscrição de Dirigentes:**
- ✅ Inscrever dirigentes em eventos
- ✅ Definir status de presença (confirmado, pendente, recusado)
- ✅ Listar participantes do evento
- ✅ Remover inscrição

**Check-in e Presença:**
- ✅ Fazer check-in (registra data/hora em checkin_em)
- ✅ Listar presentes
- ✅ Relatório de presença por evento
- ✅ Filtros por status de presença

**Autorização (Policies):**
- ✅ Diocese cria e gerencia eventos
- ✅ Núcleo cria eventos locais
- ✅ Diocese gerencia entidades em eventos multi-entidade
- ✅ Núcleo não pode adicionar outras entidades
- ✅ Apenas entidades participantes podem inscrever dirigentes
- ✅ Soft deletes protegem dados

**Validações de Negócio:**
- ✅ Entidade criadora existe
- ✅ Data início <= data fim
- ✅ Data fim no futuro
- ✅ Tipo evento válido
- ✅ Escopo válido
- ✅ Dirigente ativo
- ✅ Evento publicado para inscrição
- ✅ Entidade do dirigente é participante
- ✅ Não duplicar inscrição

### Próximos Passos (Fase 4+)

- ⏳ Integração com QR Code para check-in automático
- ⏳ Relatórios avançados com gráficos
- ⏳ Exportação de listas (CSV, PDF)
- ⏳ Notificações por email/SMS
- ⏳ API REST para mobile
- ⏳ Participantes externos avançado

---

**Fase 3 Completada:** 2026-06-16  
**Desenvolvedor:** Luiz Fernando Morais Alves  
**Status:** ✅ Pronto para Produção
