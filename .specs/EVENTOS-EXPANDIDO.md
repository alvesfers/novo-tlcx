# 📋 Sistema de Eventos Expandido - TLC Admin

**Data**: 2026-06-19  
**Status**: ✅ Pronto para Implementação  
**Responsável**: Luiz Fernando Morais Alves

**OBS**: Estrutura validada contra codebase existente (18 migrations + models já presentes)

---

## 📌 Contexto

Sistema expandido para gerenciar eventos da TLC com suporte a:
- Dirigentes (participantes internos)
- Cursistas (participantes externos)
- Funções de dirigentes
- Barzinho/Loja de vendas
- Camisetas (normal e plus)
- Valores customizados por evento
- Formulários dinâmicos por evento

---

## 🏗️ Tabelas do Sistema Expandido

### **1. `funcoes_dirigentes`** (NOVA - Funções Gerais)

Funções que um dirigente pode ter **de forma geral** (não por evento).

```sql
CREATE TABLE funcoes_dirigentes (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  nome              VARCHAR(255) NOT NULL,              -- "Coordenador", "Formador", "Palestrante"
  descricao         TEXT,
  tipo              ENUM('interna', 'externa'),          -- Interna (casa) ou Externa (fora da casa)
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | Identificador único | 1, 2, 3... |
| `nome` | String | Nome da função | "Coordenador", "Formador", "Palestrante" |
| `descricao` | Text | Descrição | "Responsável pela coordenação..." |
| `tipo` | Enum | `interna` ou `externa` | `interna` = funções dentro da casa |
| `ativo` | Boolean | Disponível para uso | true |

---

### **2. `dirigentes`** (MODIFICADO - Adicionar campo)

⚠️ **JÁ EXISTE**: `casas_de_retiro` (model: `CasasDeRetiro`)

Adicionar campo `id_casa_retiro` para referenciar a casa do dirigente.

```sql
ALTER TABLE dirigentes ADD COLUMN id_casa_retiro BIGINT NULL;
ALTER TABLE dirigentes ADD FOREIGN KEY (id_casa_retiro) REFERENCES casas_de_retiro(id);
```

**Novo campo:**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_casa_retiro` | BigInt (FK, nullable) | Casa de retiro do dirigente → `casas_de_retiro` |

---

### **3. `funcoes_dirigentes`** (NOVA - Funções Gerais)

Funções que um dirigente pode ter **de forma geral** (não por evento).

Renomear as `habilidades` existentes para `funcoes_dirigentes` ou criar nova tabela.

```sql
CREATE TABLE funcoes_dirigentes (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  nome              VARCHAR(255) NOT NULL,              -- "Coordenador", "Formador", "Palestrante"
  descricao         TEXT,
  tipo              ENUM('interna', 'externa'),          -- Interna (casa) ou Externa (fora da casa)
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | Identificador único | 1, 2, 3... |
| `nome` | String | Nome da função | "Coordenador", "Formador", "Palestrante" |
| `descricao` | Text | Descrição | "Responsável pela coordenação..." |
| `tipo` | Enum | `interna` ou `externa` | `interna` = funções dentro da casa |
| `ativo` | Boolean | Disponível para uso | true |

---

### **4. `dirigente_funcoes`** (NOVA - Pivot: Dirigentes x Funções)

Relaciona dirigentes com suas funções (um dirigente pode ter múltiplas funções).

```sql
CREATE TABLE dirigente_funcoes (
  id                    BIGINT PRIMARY KEY AUTO_INCREMENT,
  dirigente_id          BIGINT NOT NULL,
  funcao_dirigente_id   BIGINT NOT NULL,
  created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (dirigente_id) REFERENCES dirigentes(id),
  FOREIGN KEY (funcao_dirigente_id) REFERENCES funcoes_dirigentes(id),
  UNIQUE KEY unique_dirigente_funcao (dirigente_id, funcao_dirigente_id)
);
```

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | BigInt (PK) | ID único |
| `dirigente_id` | BigInt (FK) | → `dirigentes` |
| `funcao_dirigente_id` | BigInt (FK) | → `funcoes_dirigentes` |

**Exemplo:**
- João Silva (dirigente_id: 5) tem funções: Coordenador (funcao_id: 1) e Formador (funcao_id: 2)

---

### **5. `fornecedores_camisetas`** (NOVA - Fornecedores)

Cadastro de fornecedores de camisetas com seus tipos e tamanhos pré-configurados.

```sql
CREATE TABLE fornecedores_camisetas (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  nome              VARCHAR(255) NOT NULL,      -- "Camisetaria TLC", "Estampa Brasil"
  descricao         TEXT,
  contato           VARCHAR(20),
  email             VARCHAR(100),
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_at        TIMESTAMP NULL
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `nome` | String | Nome do fornecedor | "Camisetaria TLC" |
| `descricao` | Text | Observações | "Fornecedor com melhor preço" |
| `contato` | String | Telefone | "(11) 98765-4321" |
| `email` | String | Email | "contato@camisetaria.com" |
| `ativo` | Boolean | Ativo | true |

---

### **5b. `fornecedor_camiseta_tipos`** (NOVA - Tipos por Fornecedor)

Quais tipos de camiseta cada fornecedor oferece.

```sql
CREATE TABLE fornecedor_camiseta_tipos (
  id                      BIGINT PRIMARY KEY AUTO_INCREMENT,
  fornecedor_id           BIGINT NOT NULL,
  tipo_camiseta           ENUM('infantil', 'normal', 'plus', 'babylook', 'oversized'),
  ordem                   INT DEFAULT 0,
  ativo                   BOOLEAN DEFAULT TRUE,
  created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (fornecedor_id) REFERENCES fornecedores_camisetas(id),
  UNIQUE KEY unique_fornecedor_tipo (fornecedor_id, tipo_camiseta)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `fornecedor_id` | BigInt (FK) | → `fornecedores_camisetas` | 1 |
| `tipo_camiseta` | Enum | Tipo oferecido | `normal` |
| `ordem` | Int | Ordem de exibição | 1 |
| `ativo` | Boolean | Disponível | true |

---

### **5c. `fornecedor_camiseta_tamanhos`** (NOVA - Tamanhos e Medidas)

Tamanhos disponíveis com medidas detalhadas.

```sql
CREATE TABLE fornecedor_camiseta_tamanhos (
  id                          BIGINT PRIMARY KEY AUTO_INCREMENT,
  fornecedor_camiseta_tipo_id BIGINT NOT NULL,
  tamanho                     VARCHAR(10) NOT NULL,    -- P, M, G, GG, GGG
  medidas                     JSON NOT NULL,           -- Altura, largura, comprimento
  ordem                       INT DEFAULT 0,
  ativo                       BOOLEAN DEFAULT TRUE,
  created_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (fornecedor_camiseta_tipo_id) REFERENCES fornecedor_camiseta_tipos(id),
  UNIQUE KEY unique_tipo_tamanho (fornecedor_camiseta_tipo_id, tamanho)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `fornecedor_camiseta_tipo_id` | BigInt (FK) | → `fornecedor_camiseta_tipos` | 5 |
| `tamanho` | String | Tamanho | `G` |
| `medidas` | JSON | Medidas detalhadas | `{"altura": "72cm", "largura": "55cm", "comprimento": "75cm"}` |
| `ordem` | Int | Ordem | 3 |
| `ativo` | Boolean | Disponível | true |

**Exemplo de Medidas JSON:**
```json
{
  "altura": "72cm",
  "largura": "55cm",
  "comprimento": "75cm",
  "peso_aproximado": "180g"
}
```

---

### **5d. `evento_tipos_camiseta`** (MODIFICADO - Agora referencia Fornecedor)

Define qual fornecedor será usado no evento.

```sql
CREATE TABLE evento_tipos_camiseta (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  evento_id         BIGINT NOT NULL,
  fornecedor_id     BIGINT NOT NULL,            -- Qual fornecedor usar
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (evento_id) REFERENCES eventos(id),
  FOREIGN KEY (fornecedor_id) REFERENCES fornecedores_camisetas(id),
  UNIQUE KEY unique_evento_fornecedor (evento_id, fornecedor_id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `evento_id` | BigInt (FK) | → `eventos` | 5 |
| `fornecedor_id` | BigInt (FK) | → `fornecedores_camisetas` | 1 |
| `ativo` | Boolean | Ativo | true |

**Agora é simples:**
- Admin seleciona fornecedor
- Sistema carrega automaticamente todos os tipos e tamanhos daquele fornecedor
- Pronto!

---

### **5e. `evento_participante_camiseta_medidas`** (NOVA - Armazena Medidas na Inscrição)

Armazena as medidas da camiseta selecionada no momento da inscrição (para referência futura).

```sql
CREATE TABLE evento_participante_camiseta_medidas (
  id                        BIGINT PRIMARY KEY AUTO_INCREMENT,
  evento_participante_id    BIGINT NOT NULL,
  fornecedor_camiseta_tamanho_id BIGINT NOT NULL,
  medidas_snapshot          JSON NOT NULL,           -- Cópia das medidas no momento
  criado_em                 TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (evento_participante_id) REFERENCES evento_participantes(id),
  FOREIGN KEY (fornecedor_camiseta_tamanho_id) REFERENCES fornecedor_camiseta_tamanhos(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `evento_participante_id` | BigInt (FK) | → `evento_participantes` | 50 |
| `fornecedor_camiseta_tamanho_id` | BigInt (FK) | → tamanho selecionado | 10 |
| `medidas_snapshot` | JSON | Cópia das medidas na inscrição | `{...}` |

---

### **5b. `evento_valores`** (NOVA - Preços por Evento)

Valores customizados para cada evento (inscrição, camiseta, combo, etc).

```sql
CREATE TABLE evento_valores (
  id                      BIGINT PRIMARY KEY AUTO_INCREMENT,
  evento_id               BIGINT NOT NULL,
  tipo_valor              ENUM(
    'inscricao_dirigente_interna',          -- Dirigente com função interna
    'inscricao_dirigente_externa',          -- Dirigente com função externa
    'inscricao_cursista',                   -- Participante externo
    'camiseta_infantil_p', 'camiseta_infantil_m', 'camiseta_infantil_g',
    'camiseta_normal_p', 'camiseta_normal_m', 'camiseta_normal_g', 'camiseta_normal_gg', 'camiseta_normal_ggg',
    'camiseta_plus_p', 'camiseta_plus_m', 'camiseta_plus_g', 'camiseta_plus_gg', 'camiseta_plus_ggg',
    'camiseta_babylook_p', 'camiseta_babylook_m', 'camiseta_babylook_g',
    'camiseta_oversized_gg', 'camiseta_oversized_ggg',
    'combo_inscricao_camiseta_infantil',
    'combo_inscricao_camiseta_normal',
    'combo_inscricao_camiseta_plus',
    'combo_inscricao_camiseta_babylook',
    'combo_inscricao_camiseta_oversized'
  ),
  valor                   DECIMAL(10,2) NOT NULL,
  descricao               VARCHAR(255),
  ativo                   BOOLEAN DEFAULT TRUE,
  created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (evento_id) REFERENCES eventos(id),
  UNIQUE KEY unique_evento_tipo (evento_id, tipo_valor)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `evento_id` | BigInt (FK) | → `eventos` | 5 |
| `tipo_valor` | Enum | Tipo + Tamanho/Estilo | `camiseta_normal_g` |
| `valor` | Decimal(10,2) | Valor em reais | 30.00 |
| `descricao` | String | Descrição adicional | "Camiseta Normal tamanho G" |
| `ativo` | Boolean | Ativo | true |

**Exemplos de Preços em um Evento:**
```
Evento: Seminário Diocesano 2026

INSCRIÇÃO:
- inscricao_dirigente_interna: R$ 80,00
- inscricao_dirigente_externa: R$ 120,00
- inscricao_cursista: R$ 150,00

CAMISETAS INFANTIL:
- camiseta_infantil_p: R$ 25,00
- camiseta_infantil_m: R$ 25,00
- camiseta_infantil_g: R$ 25,00

CAMISETAS NORMAL:
- camiseta_normal_p: R$ 30,00
- camiseta_normal_m: R$ 30,00
- camiseta_normal_g: R$ 30,00
- camiseta_normal_gg: R$ 30,00
- camiseta_normal_ggg: R$ 35,00

CAMISETAS PLUS:
- camiseta_plus_p: R$ 45,00
- camiseta_plus_m: R$ 45,00
- camiseta_plus_g: R$ 50,00
- camiseta_plus_gg: R$ 50,00
- camiseta_plus_ggg: R$ 55,00

CAMISETAS BABYLOOK:
- camiseta_babylook_p: R$ 28,00
- camiseta_babylook_m: R$ 28,00
- camiseta_babylook_g: R$ 28,00

CAMISETAS OVERSIZED:
- camiseta_oversized_gg: R$ 40,00
- camiseta_oversized_ggg: R$ 40,00

COMBOS:
- combo_inscricao_camiseta_infantil: R$ 100,00
- combo_inscricao_camiseta_normal: R$ 105,00
- combo_inscricao_camiseta_plus: R$ 125,00
- combo_inscricao_camiseta_babylook: R$ 105,00
- combo_inscricao_camiseta_oversized: R$ 115,00
```

---

### **6. `eventos`** (MODIFICADO - Adicionar campos JSON)

Adicionar dois campos JSON para formulários dinâmicos.

```sql
ALTER TABLE eventos ADD COLUMN formulario_dirigentes JSON NULL;
ALTER TABLE eventos ADD COLUMN formulario_participantes JSON NULL;
```

**Novos campos:**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `formulario_dirigentes` | JSON | Campos customizados para dirigentes | 
| `formulario_participantes` | JSON | Campos customizados para cursistas |

**Exemplo de JSON:**
```json
// formulario_dirigentes
{
  "campos": [
    {
      "id": "tipo_camiseta",
      "nome": "Qual tipo de camiseta?",
      "tipo": "select",
      "opcoes": ["infantil", "normal", "plus", "babylook", "oversized"],
      "obrigatorio": true,
      "ordem": 1
    },
    {
      "id": "tamanho_camiseta",
      "nome": "Qual o tamanho?",
      "tipo": "select",
      "opcoes": ["P", "M", "G", "GG", "GGG"],
      "obrigatorio": true,
      "ordem": 2
    },
    {
      "id": "alergia",
      "nome": "Tem alguma alergia?",
      "tipo": "text",
      "obrigatorio": false,
      "ordem": 3
    },
    {
      "id": "experiencia_anterior",
      "nome": "Participou de eventos anteriores?",
      "tipo": "radio",
      "opcoes": ["Sim", "Não"],
      "obrigatorio": true,
      "ordem": 4
    }
  ]
}

// formulario_participantes
{
  "campos": [
    {
      "id": "tipo_camiseta",
      "nome": "Qual tipo de camiseta?",
      "tipo": "select",
      "opcoes": ["infantil", "normal", "plus", "babylook", "oversized"],
      "obrigatorio": true,
      "ordem": 1
    },
    {
      "id": "tamanho_camiseta",
      "nome": "Qual o tamanho?",
      "tipo": "select",
      "opcoes": ["P", "M", "G", "GG", "GGG"],
      "obrigatorio": true,
      "ordem": 2
    },
    {
      "id": "telefone_contato",
      "nome": "Telefone para contato",
      "tipo": "phone",
      "obrigatorio": true,
      "ordem": 3
    }
  ]
}
```

---

### **7. `evento_participantes`** (MODIFICADO - Expandido)

Tabela que conecta dirigentes/cursistas aos eventos (expandida com novos campos).

```sql
ALTER TABLE evento_participantes ADD COLUMN dirigente_funcao_id BIGINT NULL;
ALTER TABLE evento_participantes ADD COLUMN tipo_camiseta VARCHAR(50) NULL;
ALTER TABLE evento_participantes ADD COLUMN tamanho_camiseta VARCHAR(5) NULL;
ALTER TABLE evento_participantes ADD COLUMN respostas_formulario JSON NULL;
ALTER TABLE evento_participantes ADD FOREIGN KEY (dirigente_funcao_id) REFERENCES dirigente_funcoes(id);
```

**Campos (originais + novos):**
| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `evento_id` | BigInt (FK) | → `eventos` | 5 |
| `tipo_participante` | Enum | `dirigente` ou `participante_externo` | `dirigente` |
| `dirigente_id` | BigInt (FK, nullable) | → `dirigentes` | 10 |
| `participante_externo_id` | BigInt (FK, nullable) | → `participante_externos` | NULL |
| `presenca` | Enum | `confirmado`, `pendente`, `recusado` | `confirmado` |
| `checkin_em` | DateTime (nullable) | Timestamp do check-in | "2026-08-10 09:15:00" |
| **`dirigente_funcao_id`** | BigInt (FK, nullable) | → `dirigente_funcoes` (qual função) | 2 |
| **`tipo_camiseta`** | String (nullable) | Tipo/Estilo | `normal`, `plus`, `infantil`, `babylook`, `oversized` |
| **`tamanho_camiseta`** | String (nullable) | Tamanho | `P`, `M`, `G`, `GG`, `GGG` |
| **`respostas_formulario`** | JSON (nullable) | Respostas do formulário | `{...}` |
| `observacao` | Text | Notas | "Alergia" |
| `created_at` | Timestamp | Criação | "2026-06-19 10:00:00" |
| `updated_at` | Timestamp | Atualização | "2026-06-19 14:30:00" |

---

### **8. `barzinhos`** (NOVA - Loja de Vendas no Evento)

Loja/barzinho que pode estar presente no evento.

```sql
CREATE TABLE barzinhos (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  evento_id         BIGINT NOT NULL,
  nome              VARCHAR(255) NOT NULL,      -- "Barzinho", "Loja de Camisetas"
  descricao         TEXT,
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_at        TIMESTAMP NULL,
  FOREIGN KEY (evento_id) REFERENCES eventos(id)
);
```

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | BigInt (PK) | ID único |
| `evento_id` | BigInt (FK) | → `eventos` |
| `nome` | String | Nome da loja/barzinho |
| `descricao` | Text | Descrição |
| `ativo` | Boolean | Ativa |
| `deleted_at` | Timestamp | Soft delete |

---

### **9. `barzinho_produtos`** (NOVA - Produtos do Barzinho)

Produtos disponíveis no barzinho.

```sql
CREATE TABLE barzinho_produtos (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  barzinho_id       BIGINT NOT NULL,
  nome              VARCHAR(255) NOT NULL,
  descricao         TEXT,
  preco_custo       DECIMAL(10,2) NOT NULL,
  preco_venda       DECIMAL(10,2) NOT NULL,
  quantidade        INT DEFAULT 0,
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_at        TIMESTAMP NULL,
  FOREIGN KEY (barzinho_id) REFERENCES barzinhos(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `barzinho_id` | BigInt (FK) | → `barzinhos` | 1 |
| `nome` | String | Nome do produto | "Refrigerante Lata" |
| `descricao` | Text | Descrição | "Refrigerante 350ml" |
| `preco_custo` | Decimal(10,2) | Preço de custo | 2.50 |
| `preco_venda` | Decimal(10,2) | Preço de venda | 5.00 |
| `quantidade` | Int | Quantidade disponível | 100 |
| `ativo` | Boolean | Produto ativo | true |

---

### **10. `barzinho_combos`** (NOVA - Combos de Produtos)

Agrupamentos de produtos com desconto.

```sql
CREATE TABLE barzinho_combos (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  barzinho_id       BIGINT NOT NULL,
  nome              VARCHAR(255) NOT NULL,
  descricao         TEXT,
  preco_venda       DECIMAL(10,2) NOT NULL,
  ativo             BOOLEAN DEFAULT TRUE,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (barzinho_id) REFERENCES barzinhos(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `barzinho_id` | BigInt (FK) | → `barzinhos` | 1 |
| `nome` | String | Nome do combo | "Combo Bebida + Lanche" |
| `descricao` | Text | Composição | "1 refrigerante + 1 lanche" |
| `preco_venda` | Decimal(10,2) | Preço combo | 12.00 |
| `ativo` | Boolean | Ativo | true |

---

### **11. `barzinho_combo_itens`** (NOVA - Itens do Combo)

Relaciona produtos ao combo.

```sql
CREATE TABLE barzinho_combo_itens (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  combo_id          BIGINT NOT NULL,
  produto_id        BIGINT NOT NULL,
  quantidade        INT DEFAULT 1,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (combo_id) REFERENCES barzinho_combos(id),
  FOREIGN KEY (produto_id) REFERENCES barzinho_produtos(id)
);
```

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | BigInt (PK) | ID único |
| `combo_id` | BigInt (FK) | → `barzinho_combos` |
| `produto_id` | BigInt (FK) | → `barzinho_produtos` |
| `quantidade` | Int | Quantidade deste produto no combo |

---

### **12. `formas_pagamento`** (NOVA - Formas de Pagamento com Taxas)

Diferentes máquinas/formas de pagamento com suas taxas.

```sql
CREATE TABLE formas_pagamento (
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  entidade_id       BIGINT NOT NULL,            -- Máquina da entidade ou diocese
  nome              VARCHAR(255) NOT NULL,      -- "Maquininha X", "Dinheiro"
  tipo              ENUM('dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'outra'),
  taxa_credito      DECIMAL(5,2) DEFAULT 0,    -- Taxa para crédito (1.1 = 1.1%)
  taxa_debito       DECIMAL(5,2) DEFAULT 0,    -- Taxa para débito (0.5 = 0.5%)
  taxa_pix          DECIMAL(5,2) DEFAULT 0,    -- Taxa para PIX
  ativa             BOOLEAN DEFAULT TRUE,
  observacao        TEXT,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (entidade_id) REFERENCES entidades(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID única | 1 |
| `entidade_id` | BigInt (FK) | → `entidades` (dona da máquina) | 1 (Diocese) |
| `nome` | String | Nome da máquina/forma | "Maquininha Ton" |
| `tipo` | Enum | `dinheiro`, `cartao_credito`, `cartao_debito`, `pix`, `outra` | `cartao_credito` |
| `taxa_credito` | Decimal(5,2) | Taxa em % para crédito | 1.10 (1.1%) |
| `taxa_debito` | Decimal(5,2) | Taxa em % para débito | 0.50 (0.5%) |
| `taxa_pix` | Decimal(5,2) | Taxa em % para PIX | 0.00 |
| `ativa` | Boolean | Máquina ativa | true |
| `observacao` | Text | Notas | "Máquina da diocese" |

---

### **13. `barzinho_produtos_consignados`** (NOVA - Consignação de Produtos)

Relaciona produtos do barzinho com itens do almoxarifado (origem do produto).

```sql
CREATE TABLE barzinho_produtos_consignados (
  id                          BIGINT PRIMARY KEY AUTO_INCREMENT,
  barzinho_produto_id         BIGINT NOT NULL,
  almoxarifado_item_id        BIGINT NOT NULL,
  tipo_comissao               ENUM('percentual', 'valor_fixo'),
  comissao_valor              DECIMAL(10,2) NOT NULL,     -- % ou valor
  comissao_vai_para_entidade_id BIGINT NOT NULL,         -- Quem recebe comissão
  preco_custo_original        DECIMAL(10,2),              -- Preço de custo do almoxarifado
  data_consignacao            DATETIME,
  ativa                       BOOLEAN DEFAULT TRUE,
  created_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (barzinho_produto_id) REFERENCES barzinho_produtos(id),
  FOREIGN KEY (almoxarifado_item_id) REFERENCES almoxarifado_itens(id),
  FOREIGN KEY (comissao_vai_para_entidade_id) REFERENCES entidades(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID única | 1 |
| `barzinho_produto_id` | BigInt (FK) | → `barzinho_produtos` | 5 |
| `almoxarifado_item_id` | BigInt (FK) | → `almoxarifado_itens` (origem) | 10 |
| `tipo_comissao` | Enum | `percentual` ou `valor_fixo` | `percentual` |
| `comissao_valor` | Decimal(10,2) | Valor da comissão (% ou R$) | 10.00 (10% ou R$10) |
| `comissao_vai_para_entidade_id` | BigInt (FK) | → `entidades` (quem ganha) | 2 (Núcleo A) |
| `preco_custo_original` | Decimal(10,2) | Preço original do almoxarifado | 5.00 |
| `data_consignacao` | DateTime | Quando foi consignado | "2026-08-01 10:00:00" |
| `ativa` | Boolean | Consignação ativa | true |

---

### **14. `barzinho_vendas`** (NOVA - Registro de Vendas)

Vendas feitas no barzinho (sistema "pega agora, paga depois").

```sql
CREATE TABLE barzinho_vendas (
  id                      BIGINT PRIMARY KEY AUTO_INCREMENT,
  barzinho_id             BIGINT NOT NULL,
  evento_participante_id  BIGINT NOT NULL,
  forma_pagamento_id      BIGINT NULL,                    -- Qual forma de pagamento
  tipo_pagamento          ENUM('dinheiro', 'credito', 'debito', 'pix') NULL,  -- Tipo específico
  descricao               VARCHAR(255),
  subtotal                DECIMAL(10,2) NOT NULL,
  desconto                DECIMAL(10,2) DEFAULT 0,
  taxa_pagamento          DECIMAL(10,2) DEFAULT 0,        -- Taxa da máquina
  total                   DECIMAL(10,2) NOT NULL,
  status_pagamento        ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
  data_pagamento          DATETIME NULL,
  observacao              TEXT,
  created_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (barzinho_id) REFERENCES barzinhos(id),
  FOREIGN KEY (evento_participante_id) REFERENCES evento_participantes(id),
  FOREIGN KEY (forma_pagamento_id) REFERENCES formas_pagamento(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID única da venda | 1 |
| `barzinho_id` | BigInt (FK) | → `barzinhos` | 1 |
| `evento_participante_id` | BigInt (FK) | → `evento_participantes` | 5 |
| `forma_pagamento_id` | BigInt (FK, nullable) | → `formas_pagamento` | 3 |
| `tipo_pagamento` | Enum (nullable) | `dinheiro`, `credito`, `debito`, `pix` | `credito` |
| `descricao` | String | Descrição da venda | "2x Refrigerante + 1x Lanche" |
| `subtotal` | Decimal(10,2) | Valor sem desconto | 22.00 |
| `desconto` | Decimal(10,2) | Desconto aplicado | 2.00 |
| `taxa_pagamento` | Decimal(10,2) | Taxa da máquina (R$) | 0.22 (1.1% de 20) |
| `total` | Decimal(10,2) | Valor final | 20.22 |
| `status_pagamento` | Enum | `pendente`, `pago`, `cancelado` | `pendente` |
| `data_pagamento` | DateTime (nullable) | Quando foi pago | "2026-08-10 17:30:00" |
| `observacao` | Text | Observações | "Cliente pediu desconto" |

---

### **15. `barzinho_venda_itens`** (NOVA - Itens de uma Venda)

Produtos/combos vendidos em cada venda.

```sql
CREATE TABLE barzinho_venda_itens (
  id                              BIGINT PRIMARY KEY AUTO_INCREMENT,
  venda_id                        BIGINT NOT NULL,
  tipo_item                       ENUM('produto', 'combo'),
  produto_id                      BIGINT NULL,
  combo_id                        BIGINT NULL,
  quantidade                      INT NOT NULL,
  preco_unitario                  DECIMAL(10,2) NOT NULL,
  subtotal                        DECIMAL(10,2) NOT NULL,
  consignado                      BOOLEAN DEFAULT FALSE,         -- Se é produto consignado
  barzinho_produto_consignado_id  BIGINT NULL,                   -- Referência à consignação
  comissao_tipo                   ENUM('percentual', 'valor_fixo') NULL,
  comissao_valor                  DECIMAL(10,2) NULL,            -- Comissão deste item
  comissao_vai_para_entidade_id   BIGINT NULL,                   -- Quem recebe
  created_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (venda_id) REFERENCES barzinho_vendas(id),
  FOREIGN KEY (produto_id) REFERENCES barzinho_produtos(id),
  FOREIGN KEY (combo_id) REFERENCES barzinho_combos(id),
  FOREIGN KEY (barzinho_produto_consignado_id) REFERENCES barzinho_produtos_consignados(id),
  FOREIGN KEY (comissao_vai_para_entidade_id) REFERENCES entidades(id)
);
```

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | BigInt (PK) | ID único | 1 |
| `venda_id` | BigInt (FK) | → `barzinho_vendas` | 5 |
| `tipo_item` | Enum | `produto` ou `combo` | `produto` |
| `produto_id` | BigInt (FK, nullable) | → `barzinho_produtos` | 10 |
| `combo_id` | BigInt (FK, nullable) | → `barzinho_combos` | NULL |
| `quantidade` | Int | Quantidade comprada | 2 |
| `preco_unitario` | Decimal(10,2) | Preço no momento | 5.00 |
| `subtotal` | Decimal(10,2) | Quantidade × Preço | 10.00 |
| `consignado` | Boolean | É consignado? | true |
| `barzinho_produto_consignado_id` | BigInt (FK, nullable) | → `barzinho_produtos_consignados` | 1 |
| `comissao_tipo` | Enum | `percentual` ou `valor_fixo` | `percentual` |
| `comissao_valor` | Decimal(10,2) | Valor da comissão | 1.00 (10% de 10.00) |
| `comissao_vai_para_entidade_id` | BigInt (FK, nullable) | → `entidades` (quem ganha) | 2 (Núcleo A) |

---

## 🔗 Diagrama de Relações Completo

```
casas_retiro
    ↓
dirigentes (+ id_casa_retiro)
    ↓
dirigente_funcoes (pivot)
    ↑
funcoes_dirigentes

eventos (+ formulario_dirigentes, formulario_participantes)
    ├── evento_valores (preços por evento)
    ├── evento_entidades (multi-entidade)
    ├── evento_participantes (inscrição)
    │   ├── dirigentes (tipo_participante='dirigente')
    │   ├── participante_externos (tipo_participante='externo')
    │   └── dirigente_funcoes (função na inscrição)
    │
    └── barzinhos (loja)
        ├── formas_pagamento (máquinas de pagamento por entidade)
        │
        ├── barzinho_produtos
        │   ├── barzinho_combo_itens
        │   │   └── barzinho_combos
        │   │
        │   └── barzinho_produtos_consignados
        │       ├── almoxarifado_itens (origem)
        │       └── entidades (quem recebe comissão)
        │
        └── barzinho_vendas (forma_pagamento)
            └── barzinho_venda_itens (detalhe com consignação)
                └── barzinho_produtos_consignados (se consignado)
```

---

## 📊 Fluxos de Negócio

### **Fluxo 1: Configuração + Inscrição de Dirigente com Camiseta**

```
PASSO 1: CADASTRO DE FORNECEDORES (faz uma única vez)
════════════════════════════════════════════════════════

Admin cadastra fornecedor "Camisetaria TLC":
├─ Nome: "Camisetaria TLC"
├─ Telefone: (11) 98765-4321
├─ Email: contato@camisetaria.com
└─ Tipos oferecidos com tamanhos e medidas:
   
   INFANTIL:
   ├─ P: {altura: "50cm", largura: "40cm", comprimento: "45cm"}
   ├─ M: {altura: "60cm", largura: "45cm", comprimento: "55cm"}
   └─ G: {altura: "70cm", largura: "50cm", comprimento: "65cm"}
   
   NORMAL:
   ├─ P: {altura: "68cm", largura: "48cm", comprimento: "70cm"}
   ├─ M: {altura: "70cm", largura: "52cm", comprimento: "72cm"}
   ├─ G: {altura: "72cm", largura: "55cm", comprimento: "75cm"}
   ├─ GG: {altura: "74cm", largura: "58cm", comprimento: "78cm"}
   └─ GGG: {altura: "76cm", largura: "62cm", comprimento: "81cm"}
   
   PLUS:
   ├─ G: {altura: "70cm", largura: "60cm", comprimento: "72cm"}
   ├─ GG: {altura: "72cm", largura: "65cm", comprimento: "75cm"}
   └─ GGG: {altura: "74cm", largura: "70cm", comprimento: "78cm"}


PASSO 2: CRIAÇÃO DO EVENTO
═════════════════════════════

Admin cria Evento "Seminário 2026":

2a. Seleciona Fornecedor de Camisetas:
    [Camisetaria TLC ▼]
    
    ✓ Sistema carrega AUTOMATICAMENTE:
    ├─ Tipos: Infantil, Normal, Plus
    └─ Tamanhos com medidas de cada tipo

2b. Define Preços do Evento para cada tipo/tamanho:
    - inscricao_dirigente_interna: R$ 80,00
    - camiseta_infantil_p/m/g: R$ 25,00
    - camiseta_normal_p/m/g/gg: R$ 30,00
    - camiseta_normal_ggg: R$ 35,00
    - camiseta_plus_g: R$ 50,00
    - camiseta_plus_gg/ggg: R$ 55,00

2c. Sistema gera Formulário Dinâmico AUTOMATICAMENTE:
    {
      "campos": [
        {
          "id": "tipo_camiseta",
          "nome": "Qual tipo de camiseta?",
          "opcoes": ["infantil", "normal", "plus"]
        },
        {
          "id": "tamanho_camiseta",
          "nome": "Qual tamanho?",
          "opcoes_por_tipo": {
            "infantil": ["P", "M", "G"],
            "normal": ["P", "M", "G", "GG", "GGG"],
            "plus": ["G", "GG", "GGG"]
          }
        },
        {
          "id": "mostrar_medidas",
          "tipo": "info",
          "conteudo": "Medidas da camiseta selecionada (atualiza dinamicamente)"
        }
      ]
    }


PASSO 3: INSCRIÇÃO DO DIRIGENTE
═════════════════════════════════

Dirigente acessa formulário de inscrição:
┌────────────────────────────────────────────────┐
│ Tipo de camiseta: [infantil ▼]                │
│ Qual tamanho? [P, M, G ▼]                     │
│                                                │
│ Medidas da P: 50cm × 40cm × 45cm              │
│ (atualiza quando muda tipo/tamanho)           │
└────────────────────────────────────────────────┘

Seleciona: Infantil + P

3a. Sistema cria evento_participantes:
    {
      evento_id: 5,
      tipo_participante: 'dirigente',
      dirigente_id: 10,
      tipo_camiseta: 'infantil',
      tamanho_camiseta: 'P',
      presenca: 'confirmado'
    }

3b. Sistema armazena medidas em evento_participante_camiseta_medidas:
    {
      evento_participante_id: 50,
      fornecedor_camiseta_tamanho_id: 1,
      medidas_snapshot: {
        altura: "50cm",
        largura: "40cm",
        comprimento: "45cm"
      }
    }
    (Cópia da medida no momento da inscrição, para não quebrar se mudar depois)

3c. Valor da inscrição:
    - Inscrição: R$ 80,00
    - Camiseta infantil P: R$ 25,00
    - Total: R$ 105,00
```

---

### **Fluxo 2: Venda no Barzinho (Produto Normal)**

```
1. Admin cria Barzinho "Loja do Evento"

2. Admin adiciona Produtos (próprios):
   - Refrigerante: custo R$ 2,50, venda R$ 5,00
   - Lanche: custo R$ 5,00, venda R$ 10,00

3. Admin cria Combo:
   - "Combo Lanche + Bebida": R$ 12,00
   - Itens: Lanche (1x) + Refrigerante (1x)

4. Durante o evento, Dirigente pede:
   - 2x Refrigerante
   - 1x Combo
   
5. Sistema registra em barzinho_vendas:
   {
     barzinho_id: 1,
     evento_participante_id: 50,
     forma_pagamento_id: null,           -- Sem máquina (dinheiro)
     tipo_pagamento: null,
     subtotal: 22.00,                    -- (2×5 + 12)
     desconto: 0,
     taxa_pagamento: 0,
     total: 22.00,
     status_pagamento: 'pendente'        -- Paga depois
   }

6. Itens registrados em barzinho_venda_itens:
   [
     {
       produto_id: 1, 
       quantidade: 2, 
       preco_unitario: 5.00, 
       subtotal: 10.00,
       consignado: false
     },
     {
       combo_id: 1, 
       quantidade: 1, 
       preco_unitario: 12.00, 
       subtotal: 12.00,
       consignado: false
     }
   ]

7. No final do evento, dirigente paga com crédito:
   UPDATE barzinho_vendas 
   SET forma_pagamento_id = 1,           -- Maquininha Ton
       tipo_pagamento = 'credito',
       taxa_pagamento = 0.24,            -- 1.1% de 22
       total = 22.24,
       status_pagamento = 'pago', 
       data_pagamento = NOW()
   WHERE evento_participante_id = 50
```

---

### **Fluxo 2b: Venda de Produto Consignado com Comissão**

```
1. Diocese registra itens no almoxarifado:
   - Camiseta TLC: custo R$ 12,00, quantidade: 100

2. Diocese consigna camisetas para o Núcleo A vender:
   - Cria em barzinho_produtos_consignados:
     {
       barzinho_produto_id: 50,
       almoxarifado_item_id: 5,
       tipo_comissao: 'percentual',
       comissao_valor: 20,                 -- 20%
       comissao_vai_para_entidade_id: 2,   -- Núcleo A ganha
       preco_custo_original: 12.00,
       data_consignacao: NOW(),
       ativa: true
     }
   
3. Barzinho_produto fica:
   {
     barzinho_id: 1,
     nome: "Camiseta TLC",
     preco_custo: 12.00,
     preco_venda: 30.00,                 -- Preço final
     quantidade: 100
   }

4. Cursista compra camiseta:
   - Venda registrada com forma_pagamento = Dinheiro
   {
     barzinho_id: 1,
     evento_participante_id: 60,
     forma_pagamento_id: 5,              -- Dinheiro
     tipo_pagamento: 'dinheiro',
     subtotal: 30.00,
     taxa_pagamento: 0,                  -- Sem taxa dinheiro
     total: 30.00,
     status_pagamento: 'pago'
   }

5. Item da venda registrado:
   {
     venda_id: 100,
     tipo_item: 'produto',
     produto_id: 50,
     quantidade: 1,
     preco_unitario: 30.00,
     subtotal: 30.00,
     consignado: true,                           -- É consignado
     barzinho_produto_consignado_id: 1,
     comissao_tipo: 'percentual',
     comissao_valor: 6.00,                       -- 20% de 30 = 6
     comissao_vai_para_entidade_id: 2            -- Núcleo A ganha
   }

6. No fechamento, Núcleo A recebe:
   - R$ 6.00 de comissão
   - Diocese fica com R$ 24.00 - custo R$ 12.00 = R$ 12.00 (lucro)
```

---

### **Fluxo 3: Inscrição de Cursista (Participante Externo)**

```
1. Admin cria Evento "Curso de Formação"

2. Admin define Valores:
   - inscricao_cursista: R$ 150,00
   - camiseta_plus: R$ 50,00

3. Admin configura Formulário de Cursistas:
   {
     "campos": [
       {"id": "tamanho_camiseta", "tipo": "select"},
       {"id": "telefone_contato", "tipo": "phone"}
     ]
   }

4. Cursista (externo) se inscreve:
   - Preenche formulário
   - Escolhe camiseta plus
   
5. Sistema cria registro em evento_participantes:
   {
     evento_id: 6,
     tipo_participante: 'participante_externo',
     participante_externo_id: 15,
     tamanho_camiseta: 'M',
     respostas_formulario: {
       "tamanho_camiseta": "M",
       "telefone_contato": "(11) 98765-4321"
     }
   }
```

---

## 💰 Tabelas de Preços - Exemplo Real

**Evento: Seminário Diocesano 2026**

```
evento_id: 5

Valores:
├── inscricao_dirigente_interna: R$ 80,00
├── inscricao_dirigente_externa: R$ 120,00
├── inscricao_cursista: R$ 150,00
├── camiseta_normal: R$ 30,00
├── camiseta_plus: R$ 50,00
├── combo_inscricao_camiseta_normal: R$ 100,00
└── combo_inscricao_camiseta_plus: R$ 150,00
```

---

## 📋 Campos JSON - Exemplos Detalhados

### **Formulário de Dirigentes**
```json
{
  "campos": [
    {
      "id": "tamanho_camiseta",
      "nome": "Tamanho da Camiseta",
      "tipo": "select",
      "opcoes": ["P", "M", "G", "GG", "GGG"],
      "obrigatorio": true,
      "ordem": 1
    },
    {
      "id": "alergia_alimentar",
      "nome": "Tem alguma alergia alimentar?",
      "tipo": "text",
      "obrigatorio": false,
      "ordem": 2
    },
    {
      "id": "participacao_anterior",
      "nome": "Participou de eventos anteriores?",
      "tipo": "radio",
      "opcoes": ["Sim", "Não"],
      "obrigatorio": true,
      "ordem": 3
    },
    {
      "id": "obs_saude",
      "nome": "Observações sobre saúde?",
      "tipo": "textarea",
      "obrigatorio": false,
      "ordem": 4
    }
  ]
}
```

### **Formulário de Participantes Externos**
```json
{
  "campos": [
    {
      "id": "tamanho_camiseta",
      "nome": "Tamanho da Camiseta",
      "tipo": "select",
      "opcoes": ["P", "M", "G", "GG"],
      "obrigatorio": true,
      "ordem": 1
    },
    {
      "id": "emergencia_contato",
      "nome": "Contato em emergência",
      "tipo": "phone",
      "obrigatorio": true,
      "ordem": 2
    },
    {
      "id": "alergias",
      "nome": "Alergias ou restrições",
      "tipo": "textarea",
      "obrigatorio": false,
      "ordem": 3
    }
  ]
}
```

### **Respostas do Formulário (Armazenadas em evento_participantes)**
```json
{
  "tamanho_camiseta": "G",
  "alergia_alimentar": "Intolerante a lactose",
  "participacao_anterior": "Sim",
  "obs_saude": "Tomar medicamento de 4 em 4 horas"
}
```

---

## ✅ Validações de Negócio

### **Ao inscrever dirigente:**
- ✅ Dirigente existe e está ativo
- ✅ Evento está publicado
- ✅ Entidade do dirigente é participante
- ✅ Não duplicar inscrição
- ✅ Preencher campos obrigatórios do formulário
- ✅ Tamanho de camiseta válido

### **Ao fazer venda no barzinho:**
- ✅ Participante está inscrito no evento
- ✅ Evento está acontecendo
- ✅ Produto/combo existe e está ativo
- ✅ Quantidade disponível suficiente
- ✅ Atualizar estoque

### **Ao registrar produto consignado:**
- ✅ Produto existe em barzinho_produtos
- ✅ Item existe em almoxarifado_itens
- ✅ Entidade que recebe comissão existe
- ✅ Tipo de comissão válido (percentual ou valor_fixo)
- ✅ Comissão não maior que preço de venda

### **Ao vender produto consignado:**
- ✅ Produto está ativo e consignado
- ✅ Calcular comissão corretamente:
  - Se `percentual`: (preco_venda × comissao_valor) / 100
  - Se `valor_fixo`: comissao_valor direto
- ✅ Registrar comissão em barzinho_venda_itens
- ✅ Atualizar estoque do almoxarifado

### **Ao aplicar desconto:**
- ✅ Desconto não maior que total
- ✅ Registrar motivo do desconto (observação)
- ✅ Apenas admin/coordenador pode aplicar

### **Ao registrar pagamento com máquina:**
- ✅ Forma de pagamento existe e está ativa
- ✅ Tipo de pagamento válido (crédito, débito, pix)
- ✅ Taxa aplicada corretamente:
  - Crédito: utiliza `taxa_credito`
  - Débito: utiliza `taxa_debito`
  - PIX: utiliza `taxa_pix`
- ✅ Taxa calculada: (subtotal - desconto) × taxa / 100
- ✅ Total final: (subtotal - desconto) + taxa

### **Ao marcar pagamento:**
- ✅ Venda tem total > 0
- ✅ Registrar data/hora do pagamento
- ✅ Atualizar status para 'pago'
- ✅ Se consignado, calcular e registrar comissões

---

## 📁 Arquivos a Criar/Modificar

### **Migrations**

**⚠️ JÁ EXISTEM (NÃO CRIAR):**
- ✅ `2026_06_18_155716_create_casas_de_retiro_table.php`
- ✅ `2026_06_18_161655_create_alas_casas_retiro_table.php`
- ✅ `2026_06_18_155720_create_quartos_casas_de_retiro_table.php`
- ✅ `2026_06_18_180835_create_dirigente_habilidades_table.php` (pode usar como base para dirigente_funcoes)
- ✅ `2026_06_18_180826_create_habilidades_table.php` (pode renomear/usar como funcoes_dirigentes)

**CRIAR NOVAS:**

**Fornecedores de Camisetas:**
- ⏳ `*_create_fornecedores_camisetas_table.php`
- ⏳ `*_create_fornecedor_camiseta_tipos_table.php`
- ⏳ `*_create_fornecedor_camiseta_tamanhos_table.php`

**Eventos e Inscrições:**
- ⏳ `*_create_evento_tipos_camiseta_table.php` (referencia fornecedor)
- ⏳ `*_create_evento_participante_camiseta_medidas_table.php` (armazena medidas)
- ⏳ `*_create_evento_valores_table.php`
- ⏳ `*_modify_evento_participantes_table.php` (adicionar tipo_camiseta, tamanho_camiseta, dirigente_funcao_id, respostas_formulario)
- ⏳ `*_modify_eventos_table.php` (adicionar formulario_dirigentes, formulario_participantes JSON)
- ⏳ `*_modify_dirigentes_table.php` (adicionar id_casa_retiro)

**Funções:**
- ⏳ `*_create_funcoes_dirigentes_table.php` (nova tabela)
- ⏳ `*_create_dirigente_funcoes_table.php` (pivot existente como dirigente_habilidades)

**Formas de Pagamento:**
- ⏳ `*_create_formas_pagamento_table.php`

**Barzinho:**
- ⏳ `*_create_barzinhos_table.php`
- ⏳ `*_create_barzinho_produtos_table.php`
- ⏳ `*_create_barzinho_combos_table.php`
- ⏳ `*_create_barzinho_combo_itens_table.php`
- ⏳ `*_create_barzinho_produtos_consignados_table.php`
- ⏳ `*_create_barzinho_vendas_table.php`
- ⏳ `*_create_barzinho_venda_itens_table.php`

**Total a criar: 17 migrations (5 já existem)**

### **Models**

**⚠️ JÁ EXISTEM (NÃO CRIAR):**
- ✅ `CasasDeRetiro.php`
- ✅ `AlasRetiro.php`
- ✅ `QuartosCasasDeRetiro.php`
- ✅ `DirigenteHabilidade.php` (pode ser base para DirigenteFunc)
- ✅ `Habilidade.php` (pode renomear para FuncaoDirigente)

**CRIAR NOVOS:**

**Fornecedores:**
- ⏳ `FornecedorCamiseta.php`
- ⏳ `FornecedorCamisetaTipo.php`
- ⏳ `FornecedorCamisetaTamanho.php`

**Eventos:**
- ⏳ `EventoTipoCamiseta.php`
- ⏳ `EventoParticipanteCamisetaMedida.php`
- ⏳ `EventoValor.php`

**Dirigentes:**
- ⏳ `FuncaoDirigente.php` (novo ou usar Habilidade)
- ⏳ `DirigenteFuncao.php` (novo ou usar DirigenteHabilidade)

**Pagamento:**
- ⏳ `FormaPagamento.php`

**Barzinho:**
- ⏳ `Barzinho.php`
- ⏳ `BarzinhoProduto.php`
- ⏳ `BarzinhoCombo.php`
- ⏳ `BarzinhoCombItem.php`
- ⏳ `BarzinhoProdutoConsignado.php`
- ⏳ `BarzinhoVenda.php`
- ⏳ `BarzinhoVendaItem.php`

**Total a criar: 14 models (5 já existem)**

### **Controllers**

**CRIAR NOVOS:**

**Fornecedores:**
- ⏳ `FornecedorCamisetaController.php` (CRUD fornecedores)
- ⏳ `FornecedorCamisetaTipoController.php` (Tipos por fornecedor)
- ⏳ `FornecedorCamisetaTamanhoController.php` (Tamanhos com medidas)

**Eventos:**
- ⏳ `EventoTipoCamisetaController.php` (Selecionar fornecedor para evento)
- ⏳ `EventoValorController.php` (Gerenciar preços)

**Dirigentes:**
- ⏳ `FuncaoDirigenteController.php` (novo ou usar HabilidadeController)

**Pagamento:**
- ⏳ `FormaPagamentoController.php`

**Barzinho:**
- ⏳ `BarzinhoController.php`
- ⏳ `BarzinhoProdutoController.php`
- ⏳ `BarzinhoCombController.php`
- ⏳ `BarzinhoProdutoConsignadoController.php`
- ⏳ `BarzinhoVendaController.php`

**Total: 14 controllers a criar**

### **Form Requests**
- `StoreFuncaoDirigenteDRequest.php`
- `StoreEventoTipoCamisetaRequest.php` (NOVO - Validar tipos e tamanhos)
- `StoreEventoValorRequest.php`
- `StoreFormaPagamentoRequest.php` (NOVO)
- `StoreBarzinhoRequest.php`
- `StoreBarzinhoProdutoRequest.php`
- `StoreBarzinhoProdutoConsignadoRequest.php` (NOVO)
- `StoreBarzinhoVendaRequest.php`

### **Services**
- `BarzinhoService.php` (Gerenciar vendas, descontos, estoque, comissões)
- `EventoValorService.php` (Gerenciar preços por evento)
- `FormaPagamentoService.php` (NOVO - Calcular taxas)
- `ConsignacaoService.php` (NOVO - Gerenciar produtos consignados)

### **Views**
- `funcoes-dirigentes/` (CRUD)
- `casas-retiro/` (CRUD)
- `eventos/tipos-camiseta/` (Configurar tipos disponíveis por evento)
- `eventos/valores/` (Gerenciar preços)
- `eventos/participantes/formulario-dinamico.blade.php`
- `formas-pagamento/` (CRUD)
- `barzinhos/` (CRUD)
- `barzinhos/produtos/` (CRUD)
- `barzinhos/combos/` (CRUD)
- `barzinhos/consignados/` (Gerenciar consignações)
- `barzinhos/vendas/` (Registrar venda)
- `barzinhos/relatorios/` (Vendas + receita + comissões)

---

## 🎯 Prioridades de Implementação

### **Fase 1 (Crítica)**
1. ✅ `funcoes_dirigentes` (simples CRUD)
2. ✅ `casas_retiro` (simples CRUD)
3. ✅ `dirigente_funcoes` (relação)
4. ✅ `evento_tipos_camiseta` (configurar tipos/tamanhos por evento) ← NOVO
5. ✅ `evento_valores` (preços por evento)
6. ✅ `formas_pagamento` (máquinas e formas de pagamento)
7. ✅ Modificar `evento_participantes` (adicionar tipo_camiseta, tamanho_camiseta, etc)
8. ✅ Modificar `eventos` (adicionar JSON formulários)
9. ✅ Modificar `dirigentes` (adicionar id_casa_retiro)

### **Fase 2 (Sistema Barzinho Básico)**
1. ✅ `barzinhos`, `barzinho_produtos`, `barzinho_combos`
2. ✅ `barzinho_combo_itens`
3. ✅ `barzinho_vendas` (sem consignação)
4. ✅ `barzinho_venda_itens` (sem consignação)
5. ✅ Controllers e views básicas de vendas
6. ✅ Relatórios de vendas

### **Fase 3 (Sistema de Consignação)**
1. ✅ `barzinho_produtos_consignados` (relação com almoxarifado)
2. ✅ Modificar `barzinho_venda_itens` (adicionar campos de consignação)
3. ✅ `ConsignacaoService` (calcular comissões)
4. ✅ Controller de consignação
5. ✅ Views de gerenciar consignações

### **Fase 4 (Integração de Pagamento)**
1. ✅ Integrar `formas_pagamento` em `barzinho_vendas`
2. ✅ `FormaPagamentoService` (calcular taxas)
3. ✅ Views para selecionar forma de pagamento
4. ✅ Relatórios com análise de taxas

### **Fase 5 (Refinamento)**
1. ✅ Formulários dinâmicos JSON completos
2. ✅ Validações avançadas de consignação
3. ✅ Testes automatizados
4. ✅ API REST para barzinho
5. ✅ Relatórios avançados (comissões, taxas, lucro líquido)

---

**Status**: 🟡 Pronto para iniciar implementação  
**Próximo Passo**: Criar migrations e models da Fase 1
