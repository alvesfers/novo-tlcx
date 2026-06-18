# Entidades do Sistema

## Conceito Geral

Entidades são as estruturas organizacionais do TLC. Todas possuem autonomia operacional com seus próprios dados de dirigentes, eventos e financeiro.

### Tabela: `entidades`

```
Coluna                  Tipo              Descrição
─────────────────────────────────────────────────────────────────
id                      BigInt PK         Identificador único
user_id                 BigInt FK         Usuário com acesso à entidade (nullable)
entidade_pai_id         BigInt FK         Referência à entidade pai (nullable)
tipo_entidade           Enum              diocese, nucleo, secretaria
nome                    String            Nome da entidade
email                   String            Email de contato
tipo_secretaria         Enum              'aberta', 'fechada' ou null
ativo                   Boolean           Status ativo/inativo
created_at              Timestamp         Data de criação
updated_at              Timestamp         Data de atualização
deleted_at              Timestamp         Soft delete
```

## Diocese

### Definição
A Diocese é a entidade de topo da hierarquia. Ela representa a estrutura central de uma diocese e gerencia toda a organização abaixo dela.

### Características
- **Tipo**: Diocese
- **Pai**: Nenhum (entidade_pai_id = null)
- **Responsabilidades**:
  - Gerenciar seus próprios núcleos
  - Gerenciar suas próprias secretarias
  - Manter financeiro diocesano
  - Organizar eventos diocesanos
  - Visualizar toda a estrutura
  - Gerenciar dirigentes de coordenação

### Autonomia
- Financeiro próprio com categorias customizáveis
- Eventos próprios ou multi-núcleos
- Dirigentes que exercem coordenação diocesana
- Relatórios consolidados
- Usuário de acesso (user_id)

### Regras
- Diocese é entidade normal do sistema
- Pode ter tudo: financeiro, eventos, dirigentes
- Acessa visualiza tudo que pertence à sua estrutura
- Não pertence a nenhuma entidade pai

## Núcleo

### Definição
Núcleo é a entidade de base, sempre pertence a uma Diocese. Representa um agrupamento local de dirigentes.

### Características
- **Tipo**: Núcleo
- **Pai**: Diocese obrigatoriamente
- **Responsabilidades**:
  - Gerenciar seus próprios dirigentes (vínculo principal)
  - Gerenciar seu financeiro local
  - Organizar seus eventos
  - Participar de eventos multi-entidade
  - Relatar para a Diocese

### Autonomia
- Financeiro próprio independente da Diocese
- Eventos próprios e participação em eventos diocesanos
- Dirigentes com vínculo principal no núcleo
- Categorias de financeiro próprias
- Usuário de acesso (user_id)

### Regras
- Todo dirigente deve ter vínculo principal com um núcleo
- Núcleos só podem ter Diocese como pai
- Um núcleo não pode ter filhos
- Acessa dados apenas de sua entidade e dirigentes vinculados
- Não acessa núcleos irmãos sem permissão diocesana

## Secretaria

### Definição
Secretaria é uma entidade especializada que pertence a uma Diocese. Pode ser aberta ou fechada e funciona como um grupo de trabalho temático.

### Características
- **Tipo**: Secretaria
- **Pai**: Diocese obrigatoriamente (por enquanto)
- **Tipo Secretaria**: 'aberta' ou 'fechada'
- **Responsabilidades**:
  - Gerenciar seus próprios dirigentes
  - Gerenciar seu financeiro local
  - Organizar seus eventos
  - Participar de eventos multi-entidade
  - Relatar para a Diocese

### Autonomia
- Financeiro próprio independente
- Eventos próprios
- Dirigentes vinculados (sem vínculo principal)
- Categorias de financeiro próprias
- Usuário de acesso (user_id)

### Diferença: Secretaria Aberta vs Fechada

**Secretaria Aberta**
- Dirigentes podem participar com menos restrições
- Mais flexibilidade de participação
- Eventos podem incluir outros dirigentes

**Secretaria Fechada**
- Dirigentes são formalmente vinculados
- Participação mais restrita
- Eventos focados em dirigentes pertencentes

### Regras
- Secretaria não pode ter filhos
- Secretaria sempre pertence a uma Diocese
- Futura: Pode ser vinculada também a outras estruturas
- Dirigentes em secretarias têm vínculo adicional (não principal)
- Acessa dados apenas de sua entidade

## Relacionamentos

### Hierarquia

```
Diocese (id)
├── Núcleo (entidade_pai_id = Diocese.id)
│   └── Dirigentes com vínculo principal
└── Secretaria (entidade_pai_id = Diocese.id)
    └── Dirigentes com vínculo adicional
```

### Com Usuários

```
users (id)
│
└── entidades (user_id = users.id)
    ├── Diocese
    ├── Núcleo
    └── Secretaria
```

**Nota**: Um usuário pode ter acesso a múltiplas entidades (via múltiplas linhas na tabela entidades ou através de papéis - a ser definido).

## Decisões de Design

### Por que Diocese também é entidade "normal"?
Diocese poderia ter sido um tipo especial (não entidade), mas mantê-la como entidade oferece:
- Simetria: todas as estruturas funcionam igual
- Financeiro diocesano: precisa ser tratado como entidade
- Extensibilidade: preparado para estruturas acima da diocese no futuro
- Simplicidade: um modelo de dados único

### Por que não Secretaria como filha de Núcleo?
Secretarias pertencem à Diocese porque:
- São estruturas temáticas, não geográficas/hierárquicas
- Núcleos devem manter sua autonomia
- Secretaria funciona "ao lado" de núcleos, não "dentro"
- Facilita eventos multi-núcleo + secretaria

### Tipo de Secretaria (aberta/fechada)
Permite flexibilidade:
- Secretarias temáticas fechadas: grupos de trabalho especializados
- Secretarias abertas: grupos de participação mais flexível
- Regras podem ser ajustadas no futuro conforme necessário

## Tabelas Relacionadas

- **users**: Acesso ao sistema (1 usuário pode acessar múltiplas entidades)
- **dirigente_entidades**: Vinculação de dirigentes a entidades
- **eventos**: Eventos criados por entidades
- **evento_entidades**: Participação de entidades em eventos
- **financeiro_categorias**: Categorias próprias de cada entidade
- **financeiro_movimentos**: Movimentações financeiras por entidade

## Queries Comuns Esperadas

```sql
-- Diocese e seus núcleos e secretarias
SELECT * FROM entidades 
WHERE tipo_entidade = 'diocese' AND id = ?

SELECT * FROM entidades 
WHERE tipo_entidade IN ('nucleo', 'secretaria') 
AND entidade_pai_id = ?

-- Estrutura completa de uma diocese
SELECT * FROM entidades 
WHERE id = ? OR entidade_pai_id = ?

-- Núcleos de uma diocese
SELECT * FROM entidades 
WHERE tipo_entidade = 'nucleo' AND entidade_pai_id = ?

-- Secretarias de uma diocese
SELECT * FROM entidades 
WHERE tipo_entidade = 'secretaria' AND entidade_pai_id = ?
```

## Status

### Ativo/Inativo
- `ativo = true`: Entidade operacional
- `ativo = false`: Entidade desativada (histórico mantido)
- Soft delete (`deleted_at`): Entidade removida logicamente

## Próximas Considerações

- Implementar Policy com verificação de parentesco
- Adicionar scopes aos Models para queries comuns
- Considerar cache para estrutura hierárquica
- Implementar audit trail para modificações estruturais
