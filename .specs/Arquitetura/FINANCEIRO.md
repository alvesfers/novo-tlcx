# Sistema Financeiro

## Conceito Geral

Financeiro é gerenciado por entidade. Cada Diocese, Núcleo e Secretaria tem seu próprio fluxo de caixa, categorias e relatórios. Uma movimentação financeira pertence sempre a uma entidade e opcionalmente pode estar vinculada a um evento.

## Tabela: `financeiro_categorias`

Categorias de receita e despesa, configuradas por entidade.

```
Coluna          Tipo              Descrição
─────────────────────────────────────────────────
id              BigInt PK         Identificador único
entidade_id     BigInt FK         Entidade proprietária
nome            String            Nome da categoria (ex: "Dízimos", "Aluguel")
tipo            Enum              entrada, saida
ativo           Boolean           Categoria disponível
created_at      Timestamp         Data de criação
updated_at      Timestamp         Data de atualização
deleted_at      Timestamp         Soft delete
```

### Detalhes de Campos

- **entidade_id**: Cada entidade define suas próprias categorias
  - Diocese pode ter "Dízimos Diocesanos", "Doações"
  - Núcleo pode ter "Contribuição de Membros", "Ofertas"
  - Secretaria pode ter "Mensalidade", "Taxa de Inscrição"

- **tipo**: 
  - `entrada`: Receita/Renda
  - `saida`: Despesa/Gastos

- **ativo**: Permite desativar categorias sem perder histórico

## Tabela: `financeiro_movimentos`

Movimentações financeiras (entradas e saídas).

```
Coluna                  Tipo              Descrição
──────────────────────────────────────────────────────
id                      BigInt PK         Identificador único
entidade_id             BigInt FK         Entidade responsável
evento_id               BigInt FK         Evento relacionado (nullable)
financeiro_categoria_id BigInt FK         Categoria da movimentação
tipo                    Enum              entrada, saida
descricao               String            Descrição da movimentação
valor                   Decimal(10,2)     Valor da movimentação
data_movimento          Date              Data do movimento
forma_pagamento         Enum              dinheiro, cheque, transferencia, pix, cartao
comprovante_url         String            URL do comprovante (nullable)
observacao              Text              Notas adicionais
created_at              Timestamp         Data de criação
updated_at              Timestamp         Data de atualização
deleted_at              Timestamp         Soft delete
```

### Detalhes de Campos

- **entidade_id**: Qual entidade faz a movimentação
- **evento_id**: Opcional - movimentação pode estar vinculada a um evento
  - Arrecadação em evento
  - Despesa para realizar evento
  - Pode ser null se for movimento ordinário

- **tipo**: Deve concordar com categoria
  - Entrada + categoria tipo entrada
  - Saída + categoria tipo saída
  - Validação obrigatória

- **forma_pagamento**:
  - `dinheiro`: Caixa
  - `cheque`: Via cheque
  - `transferencia`: Transferência bancária
  - `pix`: Transferência PIX
  - `cartao`: Cartão de crédito/débito

- **comprovante_url**: Arquivo de comprovante armazenado
  - Armazenar apenas URL
  - Arquivo em storage/cloud
  - Pode ser null para movimentações sem comprovante

- **valor**: Decimal com 2 casas (centavos)

## Regras Financeiras

### Regra 1: Autonomia por Entidade
```
Cada entidade gerencia seu próprio financeiro independentemente.
```

**Implicações**:
- Diocese tem fluxo de caixa separado de seus núcleos e secretarias
- Núcleo A e Núcleo B não compartilham movimentações
- Relatórios consolidados são gerados pela Diocese conforme necessário

### Regra 2: Movimentação Pertence a Uma Entidade
```
Uma movimentação sempre pertence a uma entidade.
Não existe movimento "compartilhado" entre entidades.
```

**Casos**:
- Núcleo arrecada 100: movimento de Núcleo
- Diocese arrecada repasse: movimento de Diocese
- Se Núcleo repassa para Diocese: duas movimentações
  - Saída no Núcleo
  - Entrada na Diocese

### Regra 3: Vínculo Opcional com Evento
```
Uma movimentação pode estar vinculada a um evento ou ser ordinária.
```

**Exemplos**:
- Evento de festas arrecada 500: evento_id preenchido
- Aluguel mensal do núcleo: evento_id = null
- Fornecedor cobra transporte para evento: evento_id preenchido

### Regra 4: Categorias por Entidade
```
Cada entidade define suas próprias categorias de entrada e saída.
```

**Benefícios**:
- Diocese pode ter "Dízimo" como categoria
- Núcleo não precisa dessa categoria
- Flexibilidade para diferentes estruturas
- Relatórios específicos por tipo de receita/despesa

### Regra 5: Auditoria Completa
```
Todas as movimentações têm histórico completo com created_at, updated_at e soft delete.
```

**Implicações**:
- Não deletar movimentação (fazer registro reverso se necessário)
- Timestamps rastreiam quando foi criada/editada
- Soft delete permite "desfazer" sem perder rastreabilidade

## Tabelas Relacionadas

- **entidades**: Proprietária da movimentação
- **financeiro_categorias**: Classificação
- **eventos**: Contexto da movimentação (opcional)

## Queries Comuns

```sql
-- Extrato de uma entidade (período)
SELECT fm.* FROM financeiro_movimentos fm
WHERE fm.entidade_id = ? 
AND DATE(fm.data_movimento) BETWEEN ? AND ?
AND fm.deleted_at IS NULL
ORDER BY fm.data_movimento DESC

-- Entradas de uma entidade
SELECT fm.* FROM financeiro_movimentos fm
WHERE fm.entidade_id = ? AND fm.tipo = 'entrada'
AND fm.deleted_at IS NULL
ORDER BY fm.data_movimento DESC

-- Saídas de uma entidade
SELECT fm.* FROM financeiro_movimentos fm
WHERE fm.entidade_id = ? AND fm.tipo = 'saida'
AND fm.deleted_at IS NULL
ORDER BY fm.data_movimento DESC

-- Saldo de uma entidade (período)
SELECT 
  SUM(CASE WHEN fm.tipo = 'entrada' THEN fm.valor ELSE -fm.valor END) as saldo
FROM financeiro_movimentos fm
WHERE fm.entidade_id = ?
AND DATE(fm.data_movimento) <= ?
AND fm.deleted_at IS NULL

-- Movimentações por categoria
SELECT fc.nome, fc.tipo, 
       SUM(fm.valor) as total
FROM financeiro_movimentos fm
JOIN financeiro_categorias fc ON fm.financeiro_categoria_id = fc.id
WHERE fm.entidade_id = ?
GROUP BY fc.id, fc.nome, fc.tipo

-- Movimentações de um evento
SELECT fm.* FROM financeiro_movimentos fm
WHERE fm.evento_id = ?
AND fm.deleted_at IS NULL

-- Saldo parcial por forma de pagamento
SELECT fm.forma_pagamento, SUM(fm.valor) as total
FROM financeiro_movimentos fm
WHERE fm.entidade_id = ?
AND fm.tipo = 'entrada'
GROUP BY fm.forma_pagamento
```

## Estados de Movimento

### Registrado
- Movimento foi registrado
- Criado com created_at
- Pode ter comprovante anexado
- Disponível para relatórios

### Editado
- Movimento sofreu alteração
- updated_at reflete edição
- Histórico mantido via timestamps
- Auditoria possível

### Deletado (Soft)
- Movimento deletado logicamente
- deleted_at preenchido
- Não aparece em listagens normais
- Mantém histórico
- Pode ser restaurado se necessário

## Políticas de Autorização

### Quem pode criar movimentação?
- **Admin**: Sim
- **Diocese**: Sim (movimentações diocesanas)
- **Núcleo**: Sim (movimentações do núcleo)
- **Secretaria**: Sim (movimentações da secretaria)

### Quem pode ver financeiro?
- Criador: Sim
- Diocese (se entidade filha): Sim (supervisão)
- Admin: Sim

### Quem pode editar movimentação?
- Criador: Sim
- Diocese: Sim (supervisão, para auditoria)
- Admin: Sim

### Quem pode deletar movimentação?
- Admin: Sim (com cuidado)
- Diocese: Não recomendado (usar soft delete)
- Núcleo: Não

### Quem pode ver relatórios?
- Criador: Sim (seus dados)
- Diocese: Sim (consolidado da estrutura)
- Admin: Sim

## Casos de Uso

### UC1: Registrar entrada em caixa
```
Ator: Tesoureiro do Núcleo
Fluxo:
1. Acessa "Novo Movimento"
2. Seleciona tipo = "entrada"
3. Seleciona categoria (ex: "Contribuição de Membros")
4. Preenche:
   - Valor: 500.00
   - Data: hoje
   - Forma pagamento: dinheiro
   - Descrição: "Coleta especial"
5. Sistema valida:
   - Valor > 0
   - Categoria existe e tipo = entrada
   - Data válida
6. Sistema cria movimento
7. Confirmação
```

### UC2: Registrar despesa com evento
```
Ator: Coordenador do evento
Pré-condição: Evento existe
Fluxo:
1. Acessa evento
2. Clica "Registrar Despesa"
3. Preenche:
   - Tipo: saida
   - Categoria: "Transporte"
   - Valor: 200.00
   - Forma: transferencia
   - Descrição: "Frete para evento"
   - Comprovante: upload
4. Sistema cria movimento com evento_id
5. Movimento aparece no relatório do evento
```

### UC3: Visualizar extrato
```
Ator: Liderança do Núcleo
Fluxo:
1. Acessa "Financeiro"
2. Seleciona período (ex: último mês)
3. Sistema exibe:
   - Movimentações listadas
   - Subtotal de entradas
   - Subtotal de saídas
   - Saldo do período
4. Pode filtrar:
   - Por categoria
   - Por forma de pagamento
   - Por tipo (entrada/saída)
```

### UC4: Gerar relatório consolidado (Diocese)
```
Ator: Tesoura da Diocese
Fluxo:
1. Acessa "Relatórios" > "Consolidado"
2. Seleciona período
3. Sistema agrupa por:
   - Entidade (Diocese, Núcleo A, Núcleo B, etc)
   - Categoria
   - Tipo
4. Exibe:
   - Tabela com todos os dados
   - Gráficos de distribuição
   - Comparativos
5. Exporta em PDF ou CSV
```

### UC5: Auditoria de movimento
```
Ator: Admin ou Diocesano
Fluxo:
1. Acessa movimento
2. Visualiza:
   - Data de criação
   - Dados originais
   - Data de última edição
   - Histórico de alterações (futura)
3. Pode reverter se necessário:
   - Criar movimento reverso
   - Anotar motivo
```

## Validações do Negócio

### Ao criar movimento
- [ ] Entidade existe
- [ ] Valor > 0
- [ ] Tipo = entrada ou saida
- [ ] Categoria existe
- [ ] Categoria.tipo = movimento.tipo
- [ ] Data_movimento <= hoje (não futura)
- [ ] Forma_pagamento válida
- [ ] Descrição não vazia

### Ao editar movimento
- [ ] Não mudar entidade
- [ ] Não mudar tipo se movimento existe há muito tempo
- [ ] Validações gerais novamente

### Ao deletar movimento
- [ ] Soft delete obrigatório
- [ ] Auditoria de quem deletou

### Ao vincular com evento
- [ ] Evento existe
- [ ] Evento pertence à mesma entidade (ou diocese se núcleo)

## Relatórios Esperados

### 1. Extrato por Período
- Listagem de movimentações
- Filtros: data, categoria, tipo
- Subtotais

### 2. Fluxo de Caixa
- Entradas vs Saídas
- Gráfico de tendência
- Por categoria

### 3. Consolidado por Entidade
- Diocese visualiza entrada/saída de toda estrutura
- Comparativo entre núcleos
- Identificação de desvios

### 4. Eventos com Impacto Financeiro
- Listagem de eventos com movimentações
- Receita vs Despesa por evento
- Resultado líquido

### 5. Análise de Formas de Pagamento
- Distribuição de dinheiro, transferência, etc
- Identifica padrões
- Facilita controle bancário

## Implementação Sugerida

### Models
```php
// FinanceiroCategoria.php
class FinanceiroCategoria extends Model {
    protected $casts = [
        'ativo' => 'boolean',
    ];
    
    public function movimentos() { ... }
    public function entidade() { ... }
}

// FinanceiroMovimento.php
class FinanceiroMovimento extends Model {
    protected $casts = [
        'valor' => 'decimal:2',
        'data_movimento' => 'date',
    ];
    
    public function categoria() { ... }
    public function entidade() { ... }
    public function evento() { ... }
    
    public function scopeEntradas($query) { ... }
    public function scopeSaidas($query) { ... }
    public function scopePorPeriodo($query, $inicio, $fim) { ... }
}
```

### Services
- `FinanceiroService`: Criar movimento, validações
- `RelatorioFinanceiroService`: Gerar relatórios consolidados
- `SaldoService`: Calcular saldos por período

### Policies
- `FinanceiroMovimentoPolicy`: Ver, criar, editar, deletar

### Form Requests
- `StoreMovimentoRequest`: Criação
- `UpdateMovimentoRequest`: Edição
- `RelatorioRequest`: Filtros de relatório

### Commands (futuros)
- Cálculo diário de saldos (cache)
- Alerta de saldos negativos
- Reconciliação bancária
