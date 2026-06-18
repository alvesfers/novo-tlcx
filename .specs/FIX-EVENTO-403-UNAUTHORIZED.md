# 🔧 Fix: Erro 403 ao Visualizar Eventos

**Data:** 17 de Junho de 2026  
**Status:** ✅ RESOLVIDO  
**Eventos Corrigidos:** 49

---

## 🐛 Problema

Ao clicar em **Visualizar** um evento, recebia erro **403 Unauthorized**:

```
GET /eventos/24 → 403 THIS ACTION IS UNAUTHORIZED
```

---

## 🔍 Causa Raiz

A tabela `evento_entidades` estava **vazia para eventos antigos**.

**Fluxo do problema:**

1. Evento criado via banco de dados diretamente (seeder ou importação)
2. ✅ Registro criado em tabela `eventos`
3. ❌ Registro **NÃO** criado em `evento_entidades`
4. Policy tenta verificar:
   ```php
   $evento->entidades()->where('entidade_id', $entidadeUsuario->id)->exists()
   ```
5. Query retorna `false` → Acesso negado

**Database:**
```
eventos:
  ID | nome | entidade_criadora_id
  24 | Formação... | 26

evento_entidades:
  (vazio para ID 24) ← PROBLEMA!
```

---

## ✅ Solução Implementada

### 1. Atualizar EventoPolicy (Mais Inteligente)

**Arquivo:** `app/Policies/EventoPolicy.php`

**Mudança:** Adicionado check para verificar se o usuário **criou** o evento:

```php
public function view(User $user, Evento $evento): bool
{
    // ...
    if ($user->isNucleo() || $user->isSecretaria()) {
        $entidadeUsuario = $user->entidade;
        if (!$entidadeUsuario) {
            return false;
        }

        // ✅ NOVO: Pode visualizar se criou o evento
        if ($evento->entidade_criadora_id === $entidadeUsuario->id) {
            return true;
        }

        // Ou se a entidade participa
        return $evento->entidades()->where('entidade_id', $entidadeUsuario->id)->exists();
    }

    return false;
}
```

### 2. Comando Artisan para Limpar Dados

**Arquivo:** `app/Console/Commands/FixEventoEntidades.php`

**Funcionalidade:**
- Encontra todos os eventos sem entidades registradas
- Adiciona automaticamente a entidade criadora como "Organizadora"
- Mantém a transação segura

**Execução:**
```bash
php artisan fix:evento-entidades
```

**Resultado:**
```
🔍 Procurando eventos sem entidade registrada...
⚠️ Encontrados 49 eventos sem entidades
✅ Evento ID 1 (Formação...) - Entidade adicionada
✅ Evento ID 2 (Formação...) - Entidade adicionada
...
✅ Processo concluído! 49 eventos corrigidos
```

---

## 📊 O Que Foi Corrigido

| Antes | Depois |
|-------|--------|
| ❌ evento_entidades vazio | ✅ Adicionada entidade criadora |
| ❌ Policy rejeitava (não achava na pivot) | ✅ Policy aceita criadora |
| ❌ 49 eventos com erro 403 | ✅ Todos 49 eventos funcionam |

**Exemplo - Evento 24:**
```
Antes:
  evento_entidades.count() = 0 → 403 UNAUTHORIZED

Depois:
  evento_entidades.count() = 1 → 200 OK ✅
  evento_entidades.tipo_participacao = 'organizadora'
```

---

## 🚀 Como Testar

### Teste 1: Listar Eventos
```
1. Acesse: http://localhost:8000/eventos
2. Veja a lista de eventos
✅ Deve aparecer "Formação Secretaria da Música"
```

### Teste 2: Visualizar Evento
```
1. Clique no ícone "Olho" (Visualizar)
2. URL: http://localhost:8000/eventos/24
3. Página deve carregar normalmente (sem erro 403)
✅ Deve ver detalhes do evento
```

### Teste 3: Editar Evento
```
1. Clique no ícone "Editar" (lápis)
2. Modifique algo (ex: descrição)
3. Salve
✅ Deve funcionar normalmente
```

---

## 📁 Arquivos Modificados

### Modificados (1)

1. **`app/Policies/EventoPolicy.php`**
   - Método `view()` atualizado
   - Adicionado check: `if ($evento->entidade_criadora_id === $entidadeUsuario->id)`

### Criados (1)

1. **`app/Console/Commands/FixEventoEntidades.php`**
   - Novo comando Artisan
   - Corrige dados faltantes em banco

---

## 🔧 Implementação Técnica

### SQL Antes:
```sql
SELECT * FROM evento_entidades WHERE evento_id = 24;
-- Resultado: (vazio)
```

### SQL Depois:
```sql
SELECT * FROM evento_entidades WHERE evento_id = 24;
-- Resultado:
-- evento_id | entidade_id | tipo_participacao
-- 24        | 26          | organizadora
```

---

## 🛡️ Por Que Isso Funciona

**Hierarquia de Verificação (Nova):**

```
GET /eventos/24
    ↓
EventoController::show()
    ↓
authorize('view', $evento) 
    ↓
EventoPolicy::view()
    ├─ Admin? → SIM → ✅ Autorizado
    │
    ├─ Diocese?
    │  ├─ Criou o evento? → SIM → ✅ Autorizado
    │  └─ Filha criou? → SIM → ✅ Autorizado
    │
    └─ Núcleo/Secretaria?
       ├─ Criou o evento? → SIM → ✅ Autorizado (NOVO!)
       └─ Participa? (evento_entidades) → SIM → ✅ Autorizado
```

---

## 📈 Impacto

| Métrica | Valor |
|---------|-------|
| Eventos Corrigidos | 49 |
| Novos Registros em evento_entidades | 49 |
| Tempo de Execução | < 1s |
| Usuários Afetados | Todos (agora podem visualizar) |

---

## ⚠️ Prevenção Futura

### 1. EventoService Já Cria Corretamente
```php
public function criar(array $data): Evento
{
    return DB::transaction(function () use ($data) {
        $evento = Evento::create($data);

        // ✅ Isso já está aqui
        EventoEntidade::create([
            'evento_id' => $evento->id,
            'entidade_id' => $evento->entidade_criadora_id,
            'tipo_participacao' => TipoParticipacaoEvento::Organizadora->value,
        ]);

        return $evento;
    });
}
```

### 2. Migrações Futuras
Sempre criem o registro em `evento_entidades` quando criarem eventos:

```php
// Errado ❌
EventoModel::create([
    'nome' => 'Novo Evento',
    'entidade_criadora_id' => 5,
]);

// Correto ✅
$evento = EventoModel::create([
    'nome' => 'Novo Evento',
    'entidade_criadora_id' => 5,
]);

EventoEntidadeModel::create([
    'evento_id' => $evento->id,
    'entidade_id' => $evento->entidade_criadora_id,
    'tipo_participacao' => 'organizadora',
]);
```

---

## 📋 Checklist de Validação

- [x] Policy atualizada
- [x] Comando criado
- [x] 49 eventos corrigidos
- [x] Evento 24 agora tem entidade
- [x] Teste manual OK
- [x] Documentação completa

---

## 🔗 Relacionados

- [docs/permissoes.md](./docs/permissoes.md) - Matriz de permissões
- [TELAS_E_DOCUMENTACAO_COMPLETA.md](./TELAS_E_DOCUMENTACAO_COMPLETA.md) - Telas do sistema
- [docs/eventos.md](./docs/eventos.md) - Sistema de eventos

---

**Status Final:** 🟢 **RESOLVIDNO**

O erro 403 foi eliminado e todos os eventos funcionam normalmente!
