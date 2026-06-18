# Documentação da API TLC Admin

**Versão:** 1.0  
**Data:** 17 de Junho de 2026  
**Framework:** Laravel 12 com Sanctum

---

## 📋 Índice

1. [Autenticação](#autenticação)
2. [Headers Necessários](#headers-necessários)
3. [Códigos de Erro](#códigos-de-erro)
4. [Endpoints de Autenticação](#endpoints-de-autenticação)
5. [Endpoints de Dirigentes](#endpoints-de-dirigentes)
6. [Endpoints de Eventos](#endpoints-de-eventos)
7. [Endpoints de Financeiro](#endpoints-de-financeiro)
8. [Rate Limiting](#rate-limiting)
9. [Exemplos de Uso](#exemplos-de-uso)

---

## Autenticação

A API utiliza **Laravel Sanctum** para autenticação via tokens. Todos os endpoints (exceto login) requerem um token de acesso válido.

### Fluxo de Autenticação

1. Fazer login com email e senha para obter um token
2. Incluir o token em todas as requisições subsequentes no header `Authorization: Bearer {token}`
3. O token permanece válido até ser revogado via logout

---

## Headers Necessários

Todos os endpoints (exceto POST /api/auth/login) requerem:

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## Códigos de Erro

| Código | Significado | Descrição |
|--------|-------------|-----------|
| 200 | OK | Requisição bem-sucedida |
| 201 | Created | Recurso criado com sucesso |
| 400 | Bad Request | Erro na validação de dados |
| 401 | Unauthorized | Token ausente ou inválido |
| 403 | Forbidden | Acesso negado (sem permissão) |
| 404 | Not Found | Recurso não encontrado |
| 422 | Unprocessable Entity | Erro de validação |
| 429 | Too Many Requests | Rate limit excedido |
| 500 | Internal Server Error | Erro interno do servidor |

---

## Endpoints de Autenticação

### 1. Login

**Endpoint:** `POST /api/auth/login`

**Acesso:** Público (sem autenticação)

**Request:**
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@tlc.local",
    "password": "password"
  }'
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "token": "1|Abc123XyzToken...",
    "user": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@tlc.local",
      "tipo_usuario": "admin"
    }
  },
  "message": "Login realizado com sucesso"
}
```

**Erros:**
- 401: Email ou senha incorretos

---

### 2. Usuário Atual

**Endpoint:** `GET /api/auth/me`

**Acesso:** Autenticado

**Request:**
```bash
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@tlc.local",
    "tipo_usuario": "admin",
    "entidade_id": null
  },
  "message": "Usuário atual recuperado"
}
```

---

### 3. Logout

**Endpoint:** `POST /api/auth/logout`

**Acesso:** Autenticado

**Request:**
```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logout realizado com sucesso"
}
```

---

## Endpoints de Dirigentes

### 1. Listar Dirigentes

**Endpoint:** `GET /api/dirigentes`

**Acesso:** Autenticado

**Parâmetros de Query:**
- `page` (int): Número da página (padrão: 1)
- `per_page` (int): Itens por página (padrão: 15)

**Request:**
```bash
curl -X GET http://localhost/api/dirigentes?page=1&per_page=10 \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "nome": "João Silva",
      "email": "joao@example.com",
      "data_nascimento": "1990-05-15",
      "ativo": true,
      "created_at": "2026-06-10T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 50,
    "per_page": 10
  }
}
```

---

### 2. Criar Dirigente

**Endpoint:** `POST /api/dirigentes`

**Acesso:** Autenticado

**Request:**
```json
{
  "nome": "Maria Santos",
  "email": "maria@example.com",
  "data_nascimento": "1995-03-20",
  "entidade_id": 1,
  "cargo": "dirigente",
  "tipo_vinculo": "principal"
}
```

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "uuid": "650e8400-e29b-41d4-a716-446655440001",
    "nome": "Maria Santos",
    "email": "maria@example.com",
    "created_at": "2026-06-17T15:30:00Z"
  },
  "message": "Dirigente criado com sucesso"
}
```

---

### 3. Detalhes do Dirigente

**Endpoint:** `GET /api/dirigentes/{uuid}`

**Acesso:** Autenticado

**Request:**
```bash
curl -X GET http://localhost/api/dirigentes/550e8400-e29b-41d4-a716-446655440000 \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "nome": "João Silva",
    "email": "joao@example.com",
    "data_nascimento": "1990-05-15",
    "ativo": true,
    "vinculos": [
      {
        "entidade_id": 1,
        "entidade_nome": "Diocese de São Paulo",
        "cargo": "dirigente",
        "tipo_vinculo": "principal"
      }
    ]
  }
}
```

---

### 4. Atualizar Dirigente

**Endpoint:** `PUT /api/dirigentes/{uuid}`

**Acesso:** Autenticado

**Request:**
```json
{
  "nome": "João Silva Atualizado",
  "email": "joao.novo@example.com"
}
```

**Response (200):** Dirigente atualizado

---

### 5. Deletar Dirigente

**Endpoint:** `DELETE /api/dirigentes/{uuid}`

**Acesso:** Autenticado

**Response (200):** Dirigente deletado (soft delete)

---

### 6. Vínculos do Dirigente

**Endpoint:** `GET /api/dirigentes/{uuid}/vinculos`

**Acesso:** Autenticado

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "entidade_id": 1,
      "entidade_nome": "Diocese de São Paulo",
      "cargo": "dirigente",
      "tipo_vinculo": "principal",
      "data_inicio": "2025-01-01",
      "data_fim": null
    }
  ]
}
```

---

## Endpoints de Eventos

### 1. Listar Eventos

**Endpoint:** `GET /api/eventos`

**Acesso:** Autenticado

**Request:**
```bash
curl -X GET http://localhost/api/eventos?page=1 \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nome": "Seminário Regional",
      "data_inicio": "2026-06-20T09:00:00Z",
      "data_fim": "2026-06-20T17:00:00Z",
      "local": "Centro de Convenções",
      "status": "publicado",
      "descricao": "Encontro de dirigentes...",
      "participantes_count": 45
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 20,
    "per_page": 15
  }
}
```

---

### 2. Criar Evento

**Endpoint:** `POST /api/eventos`

**Acesso:** Autenticado

**Request:**
```json
{
  "nome": "Novo Evento",
  "data_inicio": "2026-07-01T09:00:00Z",
  "data_fim": "2026-07-01T17:00:00Z",
  "local": "Auditório Principal",
  "descricao": "Descrição do evento",
  "entidade_criadora_id": 1,
  "tipo_evento_id": 1
}
```

**Response (201):** Evento criado

---

### 3. Detalhes do Evento

**Endpoint:** `GET /api/eventos/{id}`

**Acesso:** Autenticado

**Response (200):** Detalhes do evento com participantes

---

### 4. Inscrever em Evento

**Endpoint:** `POST /api/eventos/{id}/participar`

**Acesso:** Autenticado

**Request:**
```json
{
  "dirigente_id": 1
}
```

**Response (201):** Inscrição realizada

---

### 5. Check-in em Evento

**Endpoint:** `POST /api/eventos/{id}/checkin`

**Acesso:** Autenticado

**Request:**
```json
{
  "dirigente_uuid": "550e8400-e29b-41d4-a716-446655440000"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Check-in realizado com sucesso",
  "data": {
    "dirigente": "João Silva",
    "evento": "Seminário Regional",
    "checkin_em": "2026-06-20T10:30:00Z"
  }
}
```

---

## Endpoints de Financeiro

### 1. Listar Movimentações

**Endpoint:** `GET /api/financeiro/movimentos`

**Acesso:** Autenticado

**Request:**
```bash
curl -X GET http://localhost/api/financeiro/movimentos?page=1 \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "descricao": "Contribuição de membros",
      "valor": 1500.50,
      "tipo": "entrada",
      "categoria": "Dízimos",
      "data_movimento": "2026-06-15",
      "entidade": "Diocese de São Paulo"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 150,
    "per_page": 15
  }
}
```

---

### 2. Criar Movimentação

**Endpoint:** `POST /api/financeiro/movimentos`

**Acesso:** Autenticado

**Request:**
```json
{
  "descricao": "Doação especial",
  "valor": 500.00,
  "tipo": "entrada",
  "categoria_id": 1,
  "data_movimento": "2026-06-17",
  "forma_pagamento": "pix",
  "entidade_id": 1
}
```

**Response (201):** Movimentação criada

---

### 3. Extrato com Filtros

**Endpoint:** `GET /api/financeiro/extrato`

**Acesso:** Autenticado

**Parâmetros:**
- `inicio` (date): Data inicial (YYYY-MM-DD)
- `fim` (date): Data final (YYYY-MM-DD)

**Request:**
```bash
curl -X GET "http://localhost/api/financeiro/extrato?inicio=2026-06-01&fim=2026-06-30" \
  -H "Authorization: Bearer {token}"
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "entradas": 5000.00,
    "saidas": 2500.00,
    "saldo": 2500.00,
    "movimentacoes": [...]
  }
}
```

---

### 4. Saldo Consolidado

**Endpoint:** `GET /api/financeiro/saldo`

**Acesso:** Autenticado

**Response (200):**
```json
{
  "success": true,
  "data": {
    "saldo_atual": 2500.00,
    "total_entradas": 5000.00,
    "total_saidas": 2500.00,
    "entidades": [
      {
        "nome": "Diocese de São Paulo",
        "saldo": 1500.00
      },
      {
        "nome": "Núcleo Centro",
        "saldo": 1000.00
      }
    ]
  }
}
```

---

## Rate Limiting

A API implementa rate limiting básico para proteger contra abuso:

| Endpoint | Limite |
|----------|--------|
| POST /api/auth/login | 5 tentativas / 15 minutos |
| GET /api/* | 100 requisições / hora |
| POST /api/* | 30 requisições / hora |

Quando o limite é excedido, a API retorna:

```json
{
  "message": "Too Many Requests",
  "status": 429
}
```

Headers de resposta indicam o status:
- `X-RateLimit-Limit`: Número máximo de requisições
- `X-RateLimit-Remaining`: Requisições restantes
- `X-RateLimit-Reset`: Timestamp quando o limite reseta

---

## Exemplos de Uso

### JavaScript/Fetch

```javascript
// Login
const response = await fetch('http://localhost/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'admin@tlc.local',
    password: 'password'
  })
});

const data = await response.json();
const token = data.data.token;

// Usar token em próximas requisições
const dirigentes = await fetch('http://localhost/api/dirigentes', {
  method: 'GET',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
  }
});

const dirigentesData = await dirigentes.json();
console.log(dirigentesData.data);
```

### cURL

```bash
# Login
TOKEN=$(curl -s -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tlc.local","password":"password"}' \
  | jq -r '.data.token')

# Usar token
curl -X GET http://localhost/api/dirigentes \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

### Python

```python
import requests

# Login
response = requests.post('http://localhost/api/auth/login', json={
    'email': 'admin@tlc.local',
    'password': 'password'
})

token = response.json()['data']['token']

# Próximas requisições
headers = {
    'Authorization': f'Bearer {token}',
    'Accept': 'application/json'
}

dirigentes = requests.get('http://localhost/api/dirigentes', headers=headers)
print(dirigentes.json())
```

---

## Status da API

✅ **Autenticação** - Completo com Sanctum  
✅ **Dirigentes** - CRUD completo  
✅ **Eventos** - CRUD + Inscrição + Check-in  
✅ **Financeiro** - Movimentações + Extrato + Saldo  
✅ **Rate Limiting** - Implementado  

⏳ **Pendente:** OpenAPI/Swagger para documentação interativa

---

**Última atualização:** 17 de Junho de 2026  
**Versão API:** 1.0
