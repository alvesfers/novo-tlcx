# Sistema de Habilidades (Skills)

**Data de Implementação:** 2026-06-18  
**Status:** ✅ IMPLEMENTADO E TESTADO  
**Commit:** b991da5, 6599235, ae2df25

---

## 📋 Visão Geral

Sistema que permite às secretarias definir habilidades específicas (ex: Violão, Canto na Secretaria de Música) e aos dirigentes declarar suas competências com um nível de proficiência (Iniciante até Profissional).

### Exemplo de Uso

```
Secretaria de Música
├── Violão (habilidade)
├── Canto (habilidade)
├── Teclado (habilidade)
└── Percussão (habilidade)

Dirigente "Fernando"
├── Violão (nível: Intermediário)
├── Canto (nível: Básico)
└── Limpeza (habilidade de outra secretaria)
```

---

## 🏗️ Arquitetura

### Tabelas do Banco

#### `habilidades`
Armazena habilidades específicas de cada secretaria.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | BigInt PK | ID único |
| entidade_id | BigInt FK | ID da secretaria |
| nome | String(255) | Nome da habilidade (ex: "Violão") |
| descricao | Text | Descrição detalhada (opcional) |
| ativo | Boolean | Status (padrão: true) |
| created_at | Timestamp | Data de criação |
| updated_at | Timestamp | Data de atualização |
| deleted_at | Timestamp | Soft delete |

**Índices:**
- `(entidade_id, ativo)` - para carregar habilidades de uma secretaria

#### `dirigente_habilidades`
Pivot que relaciona dirigentes com suas habilidades.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | BigInt PK | ID único |
| dirigente_id | BigInt FK | ID do dirigente |
| habilidade_id | BigInt FK | ID da habilidade |
| nivel | String(50) | Nível (enum: iniciante, basico, etc) |
| observacao | Text | Notas adicionais (opcional) |
| created_at | Timestamp | Data de criação |
| updated_at | Timestamp | Data de atualização |

**Índices:**
- `UNIQUE(dirigente_id, habilidade_id)` - Um dirigente não pode ter a mesma habilidade 2x
- `(dirigente_id, habilidade_id)` - para queries de busca

---

## 🎯 Enum: NivelHabilidade

```php
namespace App\Enums;

enum NivelHabilidade: string {
    case Iniciante = 'iniciante';
    case Basico = 'basico';
    case Intermediario = 'intermediario';
    case Experiente = 'experiente';
    case Profissional = 'profissional';
    
    public function label(): string { /* PT-BR */ }
    public function color(): string { /* badge color */ }
    public static function values(): array { /* array de valores */ }
}
```

### Cores por Nível

| Nível | Valor | Cor |
|-------|-------|-----|
| Iniciante | `iniciante` | 🔵 Azul |
| Básico | `basico` | 🔵 Ciano |
| Intermediário | `intermediario` | 🟡 Amarelo |
| Experiente | `experiente` | 🟠 Laranja |
| Profissional | `profissional` | 🔴 Vermelho |

---

## 📦 Models & Relacionamentos

### Model: Habilidade

```php
namespace App\Models;

class Habilidade extends Model {
    use SoftDeletes;
    
    protected $fillable = ['entidade_id', 'nome', 'descricao', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
    
    // Relacionamentos
    public function secretaria(): BelongsTo
    public function dirigentes(): BelongsToMany
    
    // Scopes
    public function scopeAtivas($query)
}
```

### Model: DirigenteHabilidade (Pivot)

```php
namespace App\Models;

class DirigenteHabilidade extends Pivot {
    protected $table = 'dirigente_habilidades';
    protected $fillable = ['dirigente_id', 'habilidade_id', 'nivel', 'observacao'];
    protected $casts = ['nivel' => NivelHabilidade::class];
}
```

### Relacionamentos nas Entidades

**Em Dirigente:**
```php
public function habilidades(): BelongsToMany {
    return $this->belongsToMany(Habilidade::class, 'dirigente_habilidades')
        ->withPivot(['nivel', 'observacao'])
        ->using(DirigenteHabilidade::class);
}
```

**Em Entidade:**
```php
public function habilidades(): HasMany {
    return $this->hasMany(Habilidade::class, 'entidade_id');
}
```

---

## 🚀 Controllers

### HabilidadeController

Gerencia criação, edição e deleção de habilidades das secretarias.

**Métodos:**
```php
store(Request $request, Entidade $entidade)
  // POST /secretarias/{entidade}/habilidades
  // Cria nova habilidade

update(Request $request, Habilidade $habilidade)
  // PUT /habilidades/{habilidade}
  // Atualiza nome/descricao/status

destroy(Habilidade $habilidade)
  // DELETE /habilidades/{habilidade}
  // Soft delete da habilidade
```

### DirigenteHabilidadeController

Gerencia vinculação de habilidades aos dirigentes.

**Métodos:**
```php
store(Request $request, Dirigente $dirigente)
  // POST /dirigentes/{dirigente}/habilidades
  // Vincula habilidade ao dirigente com nível

update(Request $request, Dirigente $dirigente, Habilidade $habilidade)
  // PUT /dirigentes/{dirigente}/habilidades/{habilidade}
  // Atualiza nível e observação

destroy(Dirigente $dirigente, Habilidade $habilidade)
  // DELETE /dirigentes/{dirigente}/habilidades/{habilidade}
  // Remove vínculo (detach)
```

---

## 🎨 Views & Componentes

### Componente: DirigenteHabilidadesForm

**Localização:** `resources/views/components/dirigente-habilidades-form.blade.php`

**Funcionalidades:**
- ✅ Lista habilidades atuais do dirigente
- ✅ Agrupa por secretaria
- ✅ Badges coloridas por nível
- ✅ Modal para editar nível/observação
- ✅ Formulário para adicionar habilidade
- ✅ Dropdown dinâmico de habilidades por secretaria (fetch AJAX)

**Props:**
```php
<x-dirigente-habilidades-form :dirigente="$dirigente" />
```

### Seção em Secretarias

**Arquivo:** `resources/views/secretarias/show.blade.php`

**Inclui:**
- Tabela com todas as habilidades da secretaria
- Botões de editar/deletar por habilidade
- Formulário para adicionar nova habilidade
- Badge de status (ativa/inativa)

### Coluna em Listagem de Secretarias

**Arquivo:** `resources/views/secretarias/index.blade.php`

**Inclui:**
- Coluna "Habilidades" com contagem
- Badge azul mostrando número de habilidades ativas
- Carregado dinamicamente

### Seção em Dirigentes

**Arquivo:** `resources/views/dirigentes/show.blade.php`

**Inclui:**
- Novo card/seção "Habilidades"
- Usa o componente `DirigenteHabilidadesForm`
- Posicionado após a seção de "Vínculos Adicionais"

---

## 🔗 Routes

```php
// Habilidades da Secretaria
Route::post('/secretarias/{entidade}/habilidades', 
    [HabilidadeController::class, 'store'])
    ->name('habilidades.store');

Route::put('/habilidades/{habilidade}', 
    [HabilidadeController::class, 'update'])
    ->name('habilidades.update');

Route::delete('/habilidades/{habilidade}', 
    [HabilidadeController::class, 'destroy'])
    ->name('habilidades.destroy');

// Habilidades do Dirigente
Route::post('/dirigentes/{dirigente}/habilidades', 
    [DirigenteHabilidadeController::class, 'store'])
    ->name('dirigentes.habilidades.store');

Route::put('/dirigentes/{dirigente}/habilidades/{habilidade}', 
    [DirigenteHabilidadeController::class, 'update'])
    ->name('dirigentes.habilidades.update');

Route::delete('/dirigentes/{dirigente}/habilidades/{habilidade}', 
    [DirigenteHabilidadeController::class, 'destroy'])
    ->name('dirigentes.habilidades.destroy');

// API - Carregar habilidades de secretaria
Route::get('/api/secretarias/{entidade}/habilidades', 
    [SearchController::class, 'secretariaHabilidades']);
```

---

## 📡 API Endpoint

### GET `/api/secretarias/{entidade}/habilidades`

Retorna todas as habilidades ativas de uma secretaria em JSON.

**Autenticação:** Sim (middleware `auth`)

**Resposta:**
```json
[
  { "id": 1, "nome": "Limpeza" },
  { "id": 2, "nome": "Decoração" },
  { "id": 3, "nome": "Culinária" },
  { "id": 4, "nome": "Desenho" },
  { "id": 5, "nome": "Experiência" }
]
```

**Usado por:** Componente `DirigenteHabilidadesForm` para carregar dropdown dinâmico

---

## 📊 Dados Iniciais (Seeder)

### HabilidadeSeeder

**Arquivo:** `database/seeders/HabilidadeSeeder.php`

**Habilidades por Secretaria:**

| Secretaria | Habilidades |
|-----------|------------|
| Apoio | Limpeza, Decoração, Culinária, Desenho, Experiência |
| Eventos | Organização, Fotografia, Comunicação, Logística |
| Música | Violão, Canto, Teclado, Percussão, Flauta |
| Espiritualidade e Formação | Catequese, Retiros, Liturgia, Formação Bíblica |
| Intercessão | Oração, Louvor, Meditação |

**Total:** ~25 habilidades iniciais

---

## ✅ Testes Realizados

### Teste de Banco de Dados
- ✅ Tabelas `habilidades` e `dirigente_habilidades` criadas
- ✅ Foreign keys funcionam
- ✅ Soft deletes funcionam
- ✅ Índices foram aplicados

### Teste de Models
- ✅ Relacionamentos funcionam (HasMany, BelongsToMany)
- ✅ Enum NivelHabilidade com casting
- ✅ Pivot model com dados corretos

### Teste de CRUD
- ✅ Criar habilidade na secretaria
- ✅ Adicionar habilidade ao dirigente
- ✅ Atualizar nível de habilidade
- ✅ Remover habilidade do dirigente
- ✅ Soft delete (habilidade continua existindo)

### Teste de Components
- ✅ Componente renderiza sem erro
- ✅ View de secretaria mostra seção
- ✅ View de dirigente integrada

---

## 🔒 Policy: HabilidadePolicy

```php
class HabilidadePolicy {
    public function create(User $user): bool { return true; }
    public function update(User $user, Habilidade $habilidade): bool { return true; }
    public function delete(User $user, Habilidade $habilidade): bool { return true; }
}
```

**Nota:** Atualmente permite para qualquer usuário autenticado. Pode ser refinado para verificar se é coordenador da secretaria.

---

## 📝 Validações

### Ao Criar Habilidade
- ✅ Nome obrigatório
- ✅ Entidade_id deve apontar para secretaria ativa
- ✅ Não duplica nome na mesma secretaria

### Ao Adicionar ao Dirigente
- ✅ Dirigente existe e está ativo
- ✅ Habilidade existe e está ativa
- ✅ Nível é um dos 5 valores válidos
- ✅ Não duplica (dirigente_id, habilidade_id)

### Ao Editar
- ✅ Nível válido
- ✅ Pivô existe

### Ao Remover
- ✅ Pivô existe antes de remover

---

## 📈 Estatísticas de Implementação

### Arquivos Criados
| Tipo | Quantidade |
|------|-----------|
| Enums | 1 |
| Migrations | 2 |
| Models | 2 |
| Controllers | 2 |
| Policies | 1 |
| Views/Componentes | 2 |
| Seeders | 1 |
| **Total** | **11 arquivos** |

### Linhas de Código
```
Enum:           ~45 linhas
Models:         ~60 linhas
Controllers:    ~120 linhas
Views:          ~280 linhas
Seeders:        ~90 linhas
Migrations:     ~50 linhas
─────────────────────────
TOTAL:          ~645 linhas
```

---

## 🚀 Como Usar

### 1. Adicionar Habilidade em uma Secretaria

1. Acesse **Secretarias** → clique na secretaria
2. Role até a seção **"Habilidades"**
3. Clique em **"Adicionar Habilidade"**
4. Preencha: Nome (obrigatório), Descrição (opcional)
5. Clique **"Adicionar Habilidade"**

### 2. Declarar Habilidade de um Dirigente

1. Acesse **Dirigentes** → clique no dirigente
2. Role até a seção **"Habilidades"**
3. Clique em **"Adicionar habilidade"**
4. Selecione **Secretaria** (carrega habilidades automaticamente)
5. Selecione **Habilidade**
6. Escolha **Nível** (dropdown com 5 opções coloridas)
7. Opcionalmente adicione **Observação**
8. Clique **"Adicionar habilidade"**

### 3. Editar Nível de Habilidade

1. No perfil do dirigente, seção "Habilidades"
2. Clique no botão ✏️ **"Editar"** da habilidade
3. Modal abre com dropdown de nível
4. Selecione novo nível
5. Opcionalmente atualize observação
6. Clique **"Salvar"**

### 4. Remover Habilidade do Dirigente

1. No perfil do dirigente, seção "Habilidades"
2. Clique no botão 🗑️ **"Deletar"**
3. Confirme a exclusão
4. Habilidade é removida (a habilidade em si permanece na secretaria)

---

## 🔧 Tecnologias Usadas

- **Backend:** Laravel 12, Eloquent ORM, PHP 8.2+
- **Database:** MySQL (suporta também PostgreSQL/SQLite)
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
- **Enums:** PHP 8.1+ Backed Enums
- **Padrões:** MVC, Pivot Models, Policy-based Authorization

---

## 📌 Observações Importantes

1. **Soft Deletes:** Habilidades não são realmente deletadas, apenas marcadas como inativas. Isso preserva o histórico.

2. **Unique Constraint:** Um dirigente não pode ter a mesma habilidade 2x (protegido pela constraint UNIQUE no banco).

3. **Carregamento Dinâmico:** O dropdown de habilidades carrega via fetch AJAX quando o usuário seleciona a secretaria, evitando carregar dados desnecessários.

4. **Agrupamento:** As habilidades do dirigente são exibidas agrupadas por secretaria, facilitando visualização.

5. **Cores:** Os níveis têm cores que ajudam na visualização rápida do nível de proficiência.

---

## 🐛 Troubleshooting

### Habilidades não aparecem no dropdown

**Problema:** Ao tentar adicionar habilidade no dirigente, a secretaria está vazia de habilidades.

**Solução:** 
1. Verifique se a secretaria tem habilidades registradas
2. Certifique-se de que estão marcadas como `ativo = true`
3. Verifique o console do browser para erros de fetch

### Erro ao deletar habilidade de dirigente

**Problema:** "Erro ao remover"

**Solução:**
1. Recarregue a página
2. Tente novamente
3. Verifique se o vínculo ainda existe no banco

---

## 📚 Referências

- [Documentação de Dirigentes](./dirigentes.md) - Seção "Sistema de Habilidades"
- Commits: b991da5, 6599235, ae2df25

