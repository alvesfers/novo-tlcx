# Sistema de Permissões e Autorização

## Conceito Geral

Permissões são baseadas em:
1. **Tipo de Usuário**: Admin, Diocese, Núcleo, Secretaria
2. **Entidade**: A qual entidade o usuário tem acesso
3. **Resource**: O que o usuário quer fazer
4. **Política**: Regras de autorização via Policies do Laravel

## Tabela: `users`

```
Coluna          Tipo              Descrição
────────────────────────────────────────────────
id              BigInt PK         Identificador único
name            String            Nome do usuário
email           String            Email único
password        String            Senha (bcrypt)
tipo_usuario    Enum              admin, diocese, nucleo, secretaria
ativo           Boolean           Usuário ativo
created_at      Timestamp         Data de criação
updated_at      Timestamp         Data de atualização
```

### Detalhes de Campos

- **tipo_usuario**:
  - `admin`: Acesso total ao sistema
  - `diocese`: Acesso como liderança de diocese
  - `nucleo`: Acesso como liderança de núcleo
  - `secretaria`: Acesso como liderança de secretaria

**Nota**: Um usuário pode ter acesso a múltiplas entidades do mesmo tipo ou tipos diferentes via tabela `users_entidades` (futura).

## Relacionamento: User -> Entidades

Um usuário acessa o sistema através de uma ou mais entidades.

```
users (id)
│
└── entidades (user_id = users.id)
    ├── Diocese A
    ├── Diocese B (outro estado)
    └── Núcleo X (dentro de Diocese A)
```

**Futuro**: Tabela pivô `users_entidades` permitirá usuário ter múltiplos acessos com papéis diferentes.

## Matriz de Permissões

### Entidades

#### ADMIN
```
Entidade / Recurso              Visualizar  Criar   Editar  Deletar  Notas
────────────────────────────────────────────────────────────────────────
Diocese                         ✓           ✓       ✓       ✓        Acesso total
Núcleo (qualquer)               ✓           ✓       ✓       ✓        Acesso total
Secretaria (qualquer)           ✓           ✓       ✓       ✓        Acesso total
Usuários                        ✓           ✓       ✓       ✓        Criar/editar usuários
Relatórios (todos)              ✓           -       -       -        Acesso total
```

#### DIOCESE
```
Entidade / Recurso              Visualizar  Criar   Editar  Deletar  Notas
────────────────────────────────────────────────────────────────────────
Diocese (própria)               ✓           -       ✓       -        Dados próprios
Núcleos (próprios)              ✓           ✓       ✓       ✓        Filhos diretos
Secretarias (próprias)          ✓           ✓       ✓       ✓        Filhos diretos
Núcleos de outras dioceses      ✗           -       -       -        Não acessa
Secretarias de outras dioceses  ✗           -       -       -        Não acessa
Financeiro (próprio)            ✓           ✓       ✓       -        Movimentações próprias
Financeiro (filhos)             ✓           ✓       ✓       -        Supervisão/auditoria
Eventos (próprios)              ✓           ✓       ✓       ✓        Criar/editar eventos
Eventos (filhos como org)       ✓           -       ✓       -        Supervisão
Relatórios (sua estrutura)      ✓           -       -       -        Consolidados
```

#### NÚCLEO
```
Entidade / Recurso              Visualizar  Criar   Editar  Deletar  Notas
────────────────────────────────────────────────────────────────────────
Diocese (própria)               ✓           -       -       -        Apenas visualizar
Núcleo (próprio)                ✓           -       ✓       -        Dados próprios
Outros Núcleos                  ✗           -       -       -        Não acessa
Secretarias (de diocese)        ✓           -       -       -        Apenas visualizar
Dirigentes (próprios)           ✓           ✓       ✓       ✓        Vínculo principal
Dirigentes (de outros núcleos)  ✓           -       -       -        Apenas visualizar
Dirigentes (de secretarias)     ✓           -       -       -        Apenas visualizar
Financeiro (próprio)            ✓           ✓       ✓       -        Gerenciamento completo
Financeiro (outros)             ✗           -       -       -        Não acessa
Eventos (próprios)              ✓           ✓       ✓       ✓        Criador e organizador
Eventos (diocesanos)            ✓           -       -       -        Apenas participante
Eventos (com múltiplos núcleos) ✓           -       ✓       -        Se é participante
Relatórios (próprio)            ✓           -       -       -        Apenas próprios dados
```

#### SECRETARIA
```
Entidade / Recurso              Visualizar  Criar   Editar  Deletar  Notas
────────────────────────────────────────────────────────────────────────
Diocese (própria)               ✓           -       -       -        Apenas visualizar
Secretaria (própria)            ✓           -       ✓       -        Dados próprios
Outras Secretarias              ✗           -       -       -        Não acessa
Núcleos (de diocese)            ✓           -       -       -        Apenas visualizar
Dirigentes (próprios)           ✓           ✓       ✓       ✓        Vínculo adicional
Dirigentes (de outros núcleos)  ✓           -       -       -        Apenas visualizar
Dirigentes (de outras secret.)  ✓           -       -       -        Apenas visualizar
Financeiro (próprio)            ✓           ✓       ✓       -        Gerenciamento completo
Financeiro (outros)             ✗           -       -       -        Não acessa
Eventos (próprios)              ✓           ✓       ✓       ✓        Criador e organizador
Eventos (diocesanos)            ✓           -       -       -        Apenas participante
Eventos (multi-entidade)        ✓           -       ✓       -        Se é participante
Relatórios (próprio)            ✓           -       -       -        Apenas próprios dados
```

### Dirigentes

#### ADMIN
```
Recurso / Ação                  Permitido
───────────────────────────────────────
Visualizar dirigentes           ✓ Todos
Criar dirigente                 ✓ Qualquer entidade
Editar dirigente                ✓ Qualquer dirigente
Deletar dirigente               ✓ Qualquer dirigente (soft delete)
Vincular dirigente              ✓ Qualquer vínculo
Editar vínculo                  ✓ Qualquer vínculo
Remover vínculo                 ✓ Qualquer vínculo
Mudar núcleo principal          ✓ Qualquer dirigente
```

#### DIOCESE
```
Recurso / Ação                  Permitido  Notas
───────────────────────────────────────────────
Visualizar todos dirigentes     ✓          De toda estrutura
Visualizar de outras dioceses   ✗          Restrição hierárquica
Criar dirigente                 ✓          Com vínculo em núcleo/secretaria próprios
Editar dirigente                ✓          Supervisão
Deletar dirigente               ✓          Soft delete (raro)
Vincular com seus núcleos       ✓          Criar vínculos
Vincular com suas secretarias   ✓          Criar vínculos
Vincular com diocese            ✓          Apenas vínculo de coordenação
Editar vínculo principal        ✓          Mudar de núcleo (cuidado)
Editar vínculo adicional        ✓          Supervisão
Remover vínculo adicional       ✓          Supervisão
Mudar núcleo principal          ✓          Supervisão
```

#### NÚCLEO
```
Recurso / Ação                          Permitido  Notas
─────────────────────────────────────────────────────
Visualizar próprios dirigentes          ✓          Com vínculo neste núcleo
Visualizar de outros núcleos            ✓          Apenas nome/info básica
Visualizar de secretarias               ✓          Apenas nome/info básica
Criar dirigente (vínculo principal)    ✓          Com este núcleo como principal
Editar dirigente                        ✗          Diocese faz
Deletar dirigente                       ✗          Diocese faz
Criar vínculo principal neste núcleo   ✓          Novos dirigentes
Editar vínculo principal neste núcleo  ✗          Diocese faz
Remover vínculo principal neste núcleo ✗          Diocese faz
Criar vínculo adicional neste núcleo   ✓          Dirigentes de outras entidades
Editar vínculo adicional neste núcleo  ✓          Próprios dados
Remover vínculo adicional neste núcleo ✓          Próprios dados
Vincular coordenador com diocese        ✗          Diocese faz
```

#### SECRETARIA
```
Recurso / Ação                          Permitido  Notas
─────────────────────────────────────────────────────
Visualizar próprios dirigentes          ✓          Com vínculo nesta secretaria
Visualizar de outros núcleos            ✓          Apenas nome/info básica
Visualizar de outras secretarias        ✓          Apenas nome/info básica
Criar dirigente                         ✗          Núcleo cria
Editar dirigente                        ✗          Núcleo/Diocese faz
Deletar dirigente                       ✗          Diocese faz
Criar vínculo principal                 ✗          Não cria vínculo principal
Editar vínculo principal                ✗          Não mexe em vínculo principal
Criar vínculo adicional (nesta secret.) ✓          Dirigentes existentes
Editar vínculo adicional (nesta secret.)✓          Próprios dados
Remover vínculo adicional               ✓          Próprios dados
Vincular com diocese                    ✗          Diocese faz
```

### Eventos

#### ADMIN
```
Recurso / Ação                  Permitido
───────────────────────────────────────
Visualizar todos eventos        ✓
Criar evento                    ✓ Qualquer entidade
Editar evento (rascunho)        ✓
Editar evento (publicado)       ✓ Limitado (não datas)
Publicar evento                 ✓
Cancelar evento                 ✓
Adicionar entidade              ✓
Remover entidade                ✓
Inscrever dirigente             ✓
Fazer check-in                  ✓
Ver inscrições                  ✓
Ver relatório de presença       ✓
```

#### DIOCESE
```
Recurso / Ação                              Permitido  Notas
────────────────────────────────────────────────────────
Visualizar eventos de sua estrutura         ✓          Diocese + filhos
Visualizar eventos de outras dioceses       ✗          Restrição hierárquica
Criar evento diocesano                      ✓
Criar evento para seus núcleos/secretarias  ✓
Editar evento próprio (rascunho)           ✓
Editar evento próprio (publicado)          ✓ Limitado
Editar evento de filhos                    ✓ Supervisão
Publicar evento próprio                     ✓
Publicar evento de filhos                   ✓
Cancelar evento                             ✓
Adicionar entidades (filhos)                ✓
Remover entidades                           ✓
Inscrever dirigentes                        ✓
Fazer check-in                              ✓
Ver relatórios de presença                  ✓
```

#### NÚCLEO
```
Recurso / Ação                          Permitido  Notas
──────────────────────────────────────────────────
Visualizar próprios eventos             ✓
Visualizar eventos diocesanos           ✓
Visualizar eventos de outros núcleos    ✗          Exceto se participante
Criar evento do próprio núcleo          ✓
Criar evento compartilhado              ✗          Diocese cria
Editar evento próprio (rascunho)       ✓
Editar evento próprio (publicado)      ✓ Limitado
Editar evento diocesano                ✗          Diocese faz
Publicar evento próprio                ✓
Cancelar evento próprio                ✓
Adicionar entidades                    ✗          Diocese faz
Inscrever dirigentes próprios          ✓
Inscrever dirigentes de outras ent.   ✗          Outra entidade faz
Fazer check-in                         ✓
Ver relatório de presença              ✓
```

#### SECRETARIA
```
Recurso / Ação                          Permitido  Notas
──────────────────────────────────────────────────
Visualizar próprios eventos             ✓
Visualizar eventos diocesanos           ✓
Visualizar eventos de núcleos           ✗          Exceto se participante
Criar evento próprio                    ✓
Criar evento compartilhado              ✗          Diocese cria
Editar evento próprio (rascunho)       ✓
Editar evento próprio (publicado)      ✓ Limitado
Editar evento diocesano                ✗          Diocese faz
Publicar evento próprio                ✓
Cancelar evento próprio                ✓
Adicionar entidades                    ✗          Diocese faz
Inscrever dirigentes próprios          ✓
Inscrever dirigentes de outras ent.   ✗          Outra entidade faz
Fazer check-in                         ✓
Ver relatório de presença              ✓
```

### Financeiro

#### ADMIN
```
Recurso / Ação                  Permitido
───────────────────────────────────────
Visualizar movimentações        ✓ Todas
Criar movimentação              ✓ Qualquer entidade
Editar movimentação             ✓ Qualquer movimento
Deletar movimentação            ✓ (soft delete)
Criar categoria                 ✓ Qualquer entidade
Editar categoria                ✓ Qualquer categoria
Ver relatórios                  ✓ Todos os relatórios
Exportar dados                  ✓ Todos os dados
```

#### DIOCESE
```
Recurso / Ação                          Permitido  Notas
────────────────────────────────────────────────────
Visualizar movimentações próprias        ✓
Visualizar movimentações de filhos       ✓ Supervisão/auditoria
Visualizar de outras dioceses            ✗
Criar movimentação própria               ✓
Editar movimentação própria              ✓
Editar movimentação de filhos            ✓ Auditoria
Deletar movimentação                     ✓ Soft delete (raro)
Criar categoria própria                  ✓
Editar categoria própria                 ✓
Ver relatório próprio                    ✓
Ver relatório consolidado (filhos)       ✓ Visão estratégica
Exportar relatórios                      ✓
```

#### NÚCLEO
```
Recurso / Ação                          Permitido
───────────────────────────────────────
Visualizar movimentações próprias        ✓
Visualizar de outras entidades           ✗
Criar movimentação                       ✓
Editar movimentação                      ✓
Deletar movimentação                     ✓ Soft delete
Criar categoria                          ✓
Editar categoria                         ✓
Ver relatório próprio                    ✓
Ver relatório da diocese                 ✗
Exportar próprios dados                  ✓
```

#### SECRETARIA
```
Recurso / Ação                          Permitido
───────────────────────────────────────
Visualizar movimentações próprias        ✓
Visualizar de outras entidades           ✗
Criar movimentação                       ✓
Editar movimentação                      ✓
Deletar movimentação                     ✓ Soft delete
Criar categoria                          ✓
Editar categoria                         ✓
Ver relatório próprio                    ✓
Ver relatório da diocese                 ✗
Exportar próprios dados                  ✓
```

## Implementação com Policies

### Estrutura de Policies

```php
// Policies/EntidadePolicy.php
public function viewAny(User $user)
public function view(User $user, Entidade $entidade)
public function create(User $user)
public function update(User $user, Entidade $entidade)
public function delete(User $user, Entidade $entidade)

// Policies/DirigentPolicy.php
public function viewAny(User $user)
public function view(User $user, Dirigent $dirigent)
public function create(User $user)
public function update(User $user, Dirigent $dirigent)
public function delete(User $user, Dirigent $dirigent)

// Policies/EventoPolicy.php
public function viewAny(User $user)
public function view(User $user, Evento $evento)
public function create(User $user)
public function update(User $user, Evento $evento)
public function publish(User $user, Evento $evento)
public function delete(User $user, Evento $evento)

// Policies/FinanceiroMovimentoPolicy.php
public function viewAny(User $user)
public function view(User $user, FinanceiroMovimento $movimento)
public function create(User $user)
public function update(User $user, FinanceiroMovimento $movimento)
public function delete(User $user, FinanceiroMovimento $movimento)
```

### Verificações Comuns em Policies

```php
// Usuário é admin?
$user->tipo_usuario === 'admin'

// Usuário é da diocese que contém a entidade?
$user->entidade->id === $entidade->entidade_pai_id

// Usuário pertence à diocese da entidade?
$entidade->entidade_pai_id === $user->entidade->id

// Entidade está deletada?
$entidade->deleted_at !== null

// Evento está publicado?
$evento->status === 'publicado'

// Dirigente pertence à entidade do usuário?
$dirigent->vinculos()->where('entidade_id', $user->entidade_id)->exists()
```

## Casos de Uso de Autorização

### UC1: Diocese editar financeiro de núcleo
```
Ator: Treasurer da Diocese
Pré-condição: Movimento de núcleo filial existe
Fluxo:
1. Diocese acessa financeiro do núcleo
2. Sistema verifica:
   - User.tipo_usuario = diocese
   - User.entidade_id = Diocese
   - Movimento.entidade_id = Núcleo
   - Núcleo.entidade_pai_id = Diocese
3. Policy retorna true
4. Diocese pode visualizar e editar
```

### UC2: Núcleo tentar editar financeiro de outro núcleo
```
Ator: Líder do Núcleo A
Pré-condição: Movimento de Núcleo B existe
Fluxo:
1. Núcleo A acessa financeiro do Núcleo B
2. Sistema verifica:
   - User.tipo_usuario = nucleo
   - User.entidade_id = Núcleo A
   - Movimento.entidade_id = Núcleo B
3. Policy retorna false
4. Acesso negado
```

### UC3: Diocese inscrever dirigente em evento
```
Ator: Liderança da Diocese
Pré-condição: Evento de núcleo, dirigente de outro núcleo
Fluxo:
1. Diocese acessa inscrição
2. Sistema verifica:
   - Evento pertence a entidade filial
   - Diocese é supervisora
3. Policy retorna true
4. Diocese pode inscrever
```

## Boas Práticas

### 1. Sempre Verificar no Policy
- Não confiar em javascript/frontend
- Todas as verificações acontecem no backend
- Policy é fonte de verdade

### 2. Verificar Hierarquia
- Dioceses não acessam dados de outras dioceses
- Núcleos/Secretarias não ultrapassam diocese
- Admin acessa tudo

### 3. Considerar Soft Deletes
- Verificar deleted_at em policies
- Deletados não devem aparecer em listas
- Audit trail mantido

### 4. Logs de Autorização
- Registrar quando acesso é negado
- Registrar edições críticas
- Facilita auditoria

## Futuras Melhorias

1. **Papéis Customizáveis**: Criar papéis além dos 4 tipos
2. **Permissões Granulares**: Ainda mais fino do que policies atuais
3. **Audit Log**: Rastreamento completo de quem fez o quê
4. **Controle de Acesso Temporal**: Permissões ativas por período
5. **Delegação de Permissões**: Usuário delega temporariamente
