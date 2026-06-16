# Sistema de Dirigentes

## Conceito Geral

Dirigentes são os atores principais do sistema TLC. Todo dirigente deve ter pelo menos um vínculo principal com um núcleo e pode ter múltiplos vínculos adicionais com núcleos, secretarias ou a diocese.

## Tabela: `dirigentes`

```
Coluna              Tipo              Descrição
──────────────────────────────────────────────────────
id                  BigInt PK         Identificador único
uuid                String            UUID público e único (para integração QR Code)
qr_code             String            Dados do QR Code (nullable inicialmente)
nome                String            Nome completo
telefone            String            Contato telefônico
genero              Enum              m, f, outro
data_nascimento     Date              Data de nascimento
foto_url            String            URL da foto de perfil (nullable)
ativo               Boolean           Status ativo/inativo
created_at          Timestamp         Data de criação
updated_at          Timestamp         Data de atualização
deleted_at          Timestamp         Soft delete
```

### Detalhes de Campos

- **uuid**: UUID público usado para identificação simples (QR codes, links públicos)
- **qr_code**: Dados/conteúdo do QR code, pode ser gerado à partir do uuid
- **foto_url**: Armazenar apenas URL, arquivo em storage/cloud
- **ativo**: Permite desativar dirigente sem perder histórico

## Tabela: `dirigente_entidades`

Tabela pivô que gerencia os vínculos entre dirigentes e entidades.

```
Coluna              Tipo              Descrição
──────────────────────────────────────────────────────
id                  BigInt PK         Identificador único
dirigente_id        BigInt FK         Referência ao dirigente
entidade_id         BigInt FK         Referência à entidade
tipo_vinculo        Enum              principal, adicional, coordenacao
cargo               Enum              dirigente, coordenador
papel               String            Descrição do papel (ex: "Líder", "Tesoureiro")
data_inicio         Date              Quando o vínculo começou
data_fim            Date              Quando o vínculo terminou (nullable)
ativo               Boolean           Vínculo está ativo
created_at          Timestamp         Data de criação
updated_at          Timestamp         Data de atualização
deleted_at          Timestamp         Soft delete
```

### Detalhes de Campos

- **tipo_vinculo**:
  - `principal`: Vínculo primário do dirigente (obrigatoriamente com um núcleo)
  - `adicional`: Vínculo secundário (com núcleo ou secretaria)
  - `coordenacao`: Vínculo especial com diocese para funções de coordenação

- **cargo**:
  - `dirigente`: Cargo comum de dirigente
  - `coordenador`: Cargo de coordenação/liderança

- **papel**: Campo livre para descrever a função (ex: "Líder de Jovens", "Tesoureiro", "Secretário")

- **data_inicio** e **data_fim**: Permite histórico de quando dirigente pertenceu a entidades

- **ativo**: Vínculo pode estar inativo sem ser deletado (histórico)

## Regras de Vinculação

### Regra 1: Vínculo Principal Obrigatório
```
Todo dirigente DEVE ter EXATAMENTE UM vínculo principal.
```

**Implementação**:
- Na criação de dirigente, exigir vínculo principal
- Validar que tipo_vinculo = 'principal' existe
- Validar que entidade_id aponta para um Núcleo
- Não permitir excluir vínculo principal se for o único principal
- Não permitir mudar tipo_vinculo = 'principal' para outro sem criar novo principal

### Regra 2: Vínculo Principal com Núcleo
```
O vínculo principal de um dirigente OBRIGATORIAMENTE aponta para uma entidade do tipo Núcleo.
```

**Validação**:
```php
// Pseudo-código
public function validarVinculoPrincipal($dirigente, $entidade)
{
    if ($tipo_vinculo === 'principal') {
        $entidade->tipo_entidade must === 'nucleo'
    }
}
```

### Regra 3: Múltiplos Vínculos Adicionais
```
Um dirigente pode ter 0 ou mais vínculos adicionais com Núcleos ou Secretarias.
```

**Casos de uso**:
- Dirigente participa de eventos de múltiplos núcleos
- Dirigente é membro de secretaria temática além de seu núcleo
- Dirigente está em transição entre núcleos

### Regra 4: Vínculo com Diocese é Apenas Coordenação
```
Um dirigente pode ter vínculo com a Diocese APENAS para coordenação.
```

**Implicações**:
- Não existe vínculo 'principal' ou 'adicional' com diocese
- Tipo de vínculo com diocese deve ser 'coordenacao'
- Dirigente coordenador diocesano continua pertencendo a um núcleo

**Exemplo**:
```
Dirigente "João"
├── Vínculo principal: Núcleo X (tipo: principal, cargo: dirigente)
└── Vínculo: Diocese Y (tipo: coordenacao, cargo: coordenador)
```

### Regra 5: Pertencimento Herdado à Diocese
```
O pertencimento à Diocese é HERDADO pelo Núcleo.
Não duplicar vínculo para indicar pertencimento indireto.
```

**O que significa**:
- Dirigente vinculado a Núcleo X pertence implicitamente à Diocese D (pai de X)
- Não criar vínculo separado 'Dirigente -> Diocese' apenas para indicar isso
- Vínculo com Diocese só existe se dirigente exerce função real (coordenação)

**Exemplo**:
```
Diocese "Brasília"
└── Núcleo "Centro"
    └── Dirigente "Maria"
        Pertence a Diocese "Brasília" (implícito)
        Pertence a Núcleo "Centro" (explícito: vínculo principal)
```

## Scopes e Queries Comuns

```sql
-- Todos os dirigentes ativos
SELECT * FROM dirigentes WHERE ativo = true AND deleted_at IS NULL

-- Dirigentes de uma entidade específica
SELECT d.* FROM dirigentes d
JOIN dirigente_entidades de ON d.id = de.dirigente_id
WHERE de.entidade_id = ? AND d.ativo = true AND de.ativo = true

-- Dirigentes com vínculo principal em um núcleo
SELECT d.* FROM dirigentes d
JOIN dirigente_entidades de ON d.id = de.dirigente_id
WHERE de.entidade_id = ? AND de.tipo_vinculo = 'principal'

-- Dirigentes que podem coordenar (coordenadores)
SELECT d.* FROM dirigentes d
JOIN dirigente_entidades de ON d.id = de.dirigente_id
WHERE de.cargo = 'coordenador' AND de.ativo = true

-- Estrutura completa de um dirigente
SELECT d.*, de.* FROM dirigentes d
LEFT JOIN dirigente_entidades de ON d.id = de.dirigente_id
WHERE d.id = ? AND d.deleted_at IS NULL
ORDER BY de.tipo_vinculo = 'principal' DESC, de.data_inicio DESC

-- Núcleo de pertencimento de um dirigente
SELECT e.* FROM entidades e
JOIN dirigente_entidades de ON e.id = de.entidade_id
WHERE de.dirigente_id = ? AND de.tipo_vinculo = 'principal'
AND e.tipo_entidade = 'nucleo'
LIMIT 1
```

## Estados de Dirigente

### Ativo
- Dirigente está participando ativamente
- `ativo = true`, `deleted_at = null`
- Vínculo com entidade também `ativo = true`

### Inativo
- Dirigente foi desativado (temporariamente ou permanent)
- `ativo = false`, `deleted_at = null`
- Histórico mantido
- Pode ser reativado

### Deletado
- Dirigente foi removido do sistema (raro)
- `deleted_at` NOT NULL
- Soft delete mantém histórico
- Pode ser restaurado com cauidado

## Políticas de Autorização

### Quem pode ver dirigentes?
- **Admin**: Todos
- **Diocese**: Todos da sua estrutura
- **Núcleo**: Seus dirigentes + dirigentes de secretarias e outros núcleos (visualização)
- **Secretaria**: Seus dirigentes + dirigentes de outros núcleos (visualização)

### Quem pode editar vínculo de dirigente?
- **Admin**: Qualquer vínculo
- **Diocese**: Vínculos de seus núcleos e secretarias
- **Núcleo**: Apenas vínculo principal de seus dirigentes
- **Secretaria**: Apenas vínculos adicionais de seus dirigentes

### Quem pode criar dirigente?
- **Admin**: Sim
- **Diocese**: Sim (para seus núcleos/secretarias)
- **Núcleo**: Sim (para criar dirigente com vínculo principal nele)
- **Secretaria**: Não (apenas vincula dirigentes existentes)

## Casos de Uso

### UC1: Criar novo dirigente
```
Ator: Liderança do Núcleo
Pré-condição: Usuário autenticado, autorizado
Fluxo:
1. Usuário acessa formulário de novo dirigente
2. Preenche: nome, telefone, gênero, data nascimento
3. Seleciona o núcleo como vínculo principal
4. Sistema valida:
   - Dirigente não existe
   - Núcleo existe e pertence à diocese do usuário
   - Usuário tem permissão para criar vínculo
5. Sistema cria dirigente + vínculo principal
6. UUID gerado automaticamente
7. Confirmação e redirecionamento
```

### UC2: Mudar núcleo de dirigente
```
Ator: Diocese
Pré-condição: Dirigente existe, está ativo
Fluxo:
1. Acessa dirigente
2. Clica "Mudar de núcleo"
3. Sistema valida:
   - Dirigente tem vínculo principal ativo
   - Novo núcleo existe
   - Diocese autoriza
4. Sistema:
   - Define data_fim do vínculo anterior
   - Cria novo vínculo principal com novo núcleo
   - Mantém histórico
5. Confirma mudança
```

### UC3: Adicionar vínculo adicional
```
Ator: Núcleo ou Diocese
Pré-condição: Dirigente existe
Fluxo:
1. Acessa dirigente
2. Clica "Adicionar vínculo"
3. Seleciona entidade (núcleo ou secretaria)
4. Seleciona cargo e papel
5. Sistema valida:
   - Dirigente já tem vínculo com essa entidade?
   - Autorização
6. Sistema cria vínculo adicional
7. Confirma
```

### UC4: Listar dirigentes de uma entidade
```
Ator: Qualquer usuário autorizado
Pré-condição: Usuário autenticado
Fluxo:
1. Acessa página de dirigentes da entidade
2. Sistema carrega dirigentes com:
   - Vínculo principal destacado
   - Todos os vínculos listados
   - Status (ativo/inativo)
3. Filtra por:
   - Ativo/Inativo
   - Tipo de vínculo
   - Cargo
4. Ordena por: nome, data_inicio
```

## Validações do Negócio

### Ao criar dirigente
- [ ] Nome não pode estar vazio
- [ ] Data de nascimento válida
- [ ] Vínculo principal obrigatório
- [ ] Vínculo principal com núcleo válido
- [ ] Entidade_pai do núcleo = Diocese do usuário

### Ao editar dirigente
- [ ] Nome não pode estar vazio
- [ ] Não permitir remover vínculo principal se for único
- [ ] Respeitar soft delete

### Ao adicionar vínculo
- [ ] Dirigente existe e ativo
- [ ] Entidade existe
- [ ] Não duplicar vínculo com mesma entidade
- [ ] Se tipo_vinculo = 'coordenacao', entidade deve ser diocese
- [ ] Se tipo_vinculo = 'principal', entidade deve ser núcleo

## Implementação Sugerida

### Models
```php
// Dirigente.php
class Dirigente extends Model {
    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];
    
    public function vinculos() { ... }
    public function vinculoPrincipal() { ... }
    public function nucleoPrincipal() { ... } // Relationship para núcleo
}

// DirigenteFundador.php (Pivot)
class DirigenteFundador extends Pivot {
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];
}
```

### Services
- `DirigentService`: Lógica de criação, vínculo, mudança de núcleo
- `DirigentAuthorizationService`: Verificação de permissões

### Policies
- `DirigentPolicy`: Quem pode ver, criar, editar
- `DirigentFundadorPolicy`: Quem pode vincular/desvincular

### Form Requests
- `StoreDigirentRequest`: Validação de criação
- `UpdateDirigentRequest`: Validação de edição
- `StoreVinculoRequest`: Validação de vínculo
