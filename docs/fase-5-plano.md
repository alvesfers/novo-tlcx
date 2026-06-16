# Plano Detalhado Fase 5: Dashboard, API e Finalizações

**Versão:** 1.0  
**Data:** 2026-06-16  
**Status:** Planejamento  
**Duração Estimada:** 4 semanas (~240 horas)

---

## 📋 Visão Geral da Fase 5

A Fase 5 é a última fase antes de produção. Foca em:
1. **Dashboard Executivo** - Visão consolidada por tipo de usuário
2. **API com Sanctum** - Endpoints para mobile/terceiros
3. **Auditoria e Logs** - Rastreamento de ações
4. **Relatórios Avançados** - Gráficos e exportações
5. **Otimizações Finais** - Performance e segurança

---

## 1. Dashboard Executivo (1 semana)

### 1.1 Objetivo
Criar dashboards personalizados por tipo de usuário (Admin, Diocese, Núcleo, Secretaria) com KPIs, gráficos e alertas.

### 1.2 Componentes

#### Admin Dashboard
```
┌─────────────────────────────────────────────────┐
│  Admin Dashboard                                │
├─────────────────────────────────────────────────┤
│ Resumo Global:                                  │
│ • Total Dioceses: 1                             │
│ • Total Núcleos: 5                              │
│ • Total Secretarias: 8                          │
│ • Total Dirigentes: 45                          │
│ • Total Usuários: 20                            │
│                                                 │
│ Atividades Recentes:                            │
│ • Dirigente João criado há 2 dias               │
│ • Evento "Seminário" publicado há 1 dia         │
│ • Movimento R$ 500 registrado hoje              │
│                                                 │
│ Alertas:                                        │
│ ⚠️ 3 dirigentes sem vínculo principal           │
│ ⚠️ 2 eventos sem entidades                      │
│ ⚠️ Saldo negativo em 1 núcleo                   │
└─────────────────────────────────────────────────┘
```

**Widgets Necessários:**
- Cartas de resumo (dioceses, núcleos, dirigentes, usuários)
- Gráfico de crescimento (últimos 6 meses)
- Timeline de atividades recentes
- Alertas de anomalias
- Listagem de usuários ativos

#### Diocese Dashboard
```
┌─────────────────────────────────────────────────┐
│  Diocese Dashboard                              │
├─────────────────────────────────────────────────┤
│ Sua Estrutura:                                  │
│ • Núcleos: 5                                    │
│ • Secretarias: 3                                │
│ • Dirigentes: 45                                │
│                                                 │
│ Próximos Eventos (7 dias):                      │
│ • Encontro Regional - 20/06                     │
│ • Formação - 22/06                              │
│ • Reunião Administrativa - 25/06                │
│                                                 │
│ Financeiro:                                     │
│ • Saldo Total: R$ 15.000,00                     │
│ • Entradas (mês): R$ 3.200,00                   │
│ • Saídas (mês): R$ 1.800,00                     │
│ • Saldo (mês): R$ 1.400,00                      │
│                                                 │
│ Gráfico: Comparativo de saldos por núcleo      │
└─────────────────────────────────────────────────┘
```

**Widgets Necessários:**
- Estrutura da diocese (núcleos, secretarias)
- Próximos eventos (com filtro de 7/30 dias)
- Resumo financeiro (saldo, entradas, saídas)
- Gráfico de comparativo entre núcleos
- Alertas de ações pendentes

#### Núcleo Dashboard
```
┌─────────────────────────────────────────────────┐
│  Núcleo Dashboard                               │
├─────────────────────────────────────────────────┤
│ Meu Núcleo:                                     │
│ • Dirigentes: 15                                │
│ • Ativos: 12 | Inativos: 3                      │
│                                                 │
│ Próximos Eventos:                               │
│ • Reunião Semanal - 17/06                       │
│ • Estudo Bíblico - 19/06                        │
│                                                 │
│ Financeiro:                                     │
│ • Saldo Atual: R$ 3.500,00                      │
│ • Movimentações (mês): 12                       │
│                                                 │
│ Últimas Movimentações:                          │
│ • Entrada R$ 200 - Contribuições                │
│ • Saída R$ 150 - Transporte                     │
│                                                 │
│ Check-ins Recentes:                             │
│ • 10 presentes no encontro de ontem             │
└─────────────────────────────────────────────────┘
```

**Widgets Necessários:**
- Resumo de dirigentes (total, ativos, inativos)
- Próximos eventos (próprios)
- Financeiro (saldo, movimentações do mês)
- Últimas movimentações
- Presença em eventos (últimas 3 semanas)

### 1.3 Tecnologias
- **Frontend:** Blade + Alpine.js ou Chart.js
- **Backend:** Laravel Collections para cálculos
- **Cache:** Redis para dados pesados (saldos consolidados)

### 1.4 Checklist de Implementação
- [ ] DashboardController com lógica por tipo_usuario
- [ ] Views: dashboard.blade.php (index), admin, diocese, nucleo, secretaria
- [ ] Componentes reutilizáveis (cards, gráficos)
- [ ] Rotas autenticadas
- [ ] Testes de acesso por tipo de usuário
- [ ] Documentação no README

---

## 2. API com Sanctum (1.5 semanas)

### 2.1 Objetivo
Criar API RESTful para consumo por aplicações móveis e terceiros.

### 2.2 Endpoints Principais

#### Autenticação
```
POST   /api/auth/login        - Login
POST   /api/auth/logout       - Logout
POST   /api/auth/refresh      - Refresh token
GET    /api/auth/me           - Usuário atual
```

#### Dirigentes
```
GET    /api/dirigentes              - Listar (paginado)
POST   /api/dirigentes              - Criar
GET    /api/dirigentes/{uuid}       - Detalhe
PUT    /api/dirigentes/{uuid}       - Atualizar
DELETE /api/dirigentes/{uuid}       - Deletar (soft)
GET    /api/dirigentes/{uuid}/vinculos - Vínculos
```

#### Eventos
```
GET    /api/eventos                    - Listar
POST   /api/eventos                    - Criar
GET    /api/eventos/{id}               - Detalhe
PUT    /api/eventos/{id}               - Atualizar
POST   /api/eventos/{id}/publicar      - Publicar
POST   /api/eventos/{id}/participar    - Inscrever dirigente
PUT    /api/eventos/{id}/checkin       - Check-in
GET    /api/eventos/{id}/participantes - Listagem de participantes
```

#### Financeiro
```
GET    /api/financeiro/movimentos              - Listar
POST   /api/financeiro/movimentos              - Criar
GET    /api/financeiro/movimentos/{id}         - Detalhe
PUT    /api/financeiro/movimentos/{id}         - Atualizar
DELETE /api/financeiro/movimentos/{id}         - Deletar
GET    /api/financeiro/extrato?inicio=...&fim=... - Extrato com filtros
GET    /api/financeiro/saldo                   - Saldo atual
```

### 2.3 Estrutura de Resposta
```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "current_page": 1,
    "total": 50,
    "per_page": 15
  },
  "message": "Operação concluída com sucesso"
}
```

### 2.4 Implementação
- [ ] Configurar Sanctum (sanctum:install)
- [ ] Criar AuthController com login/logout
- [ ] Criar ApiResources (DirigenteResource, EventoResource, etc)
- [ ] Implementar Controllers API
- [ ] Validações de Form Requests
- [ ] Rate limiting por endpoint
- [ ] CORS configurado
- [ ] Documentação OpenAPI/Swagger
- [ ] Testes de API (50+ testes)

### 2.5 Rotas
```php
// routes/api.php
Route::middleware('guest')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('dirigentes', Api\DirigenteController::class);
    Route::apiResource('eventos', Api\EventoController::class);
    Route::apiResource('financeiro/movimentos', Api\FinanceiroMovimentoController::class);
    
    Route::get('/financeiro/extrato', [Api\FinanceiroController::class, 'extrato']);
    Route::get('/financeiro/saldo', [Api\FinanceiroController::class, 'saldo']);
});
```

---

## 3. Auditoria e Logs (1 semana)

### 3.1 Objetivo
Rastrear todas as ações críticas: criação, edição, deleção de recursos.

### 3.2 Tabela audit_logs
```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    action VARCHAR(50),          -- create, update, delete, login, export
    model_type VARCHAR(100),     -- App\Models\Dirigente
    model_id BIGINT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP
);
```

### 3.3 Implementação
- [ ] Criar migration audit_logs
- [ ] Criar Observer para auto-logging
- [ ] Implementar em Dirigente, Evento, FinanceiroMovimento
- [ ] AuditLogController para visualização
- [ ] View: auditoria/index.blade.php com filtros
- [ ] Relatório: Ações por usuário, por período
- [ ] Testes

### 3.4 Observer Exemplo
```php
// app/Observers/DirigenteObserver.php
public function created(Dirigente $dirigente)
{
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model_type' => Dirigente::class,
        'model_id' => $dirigente->id,
        'new_values' => $dirigente->toArray(),
        'ip_address' => request()->ip(),
    ]);
}

public function updated(Dirigente $dirigente)
{
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'update',
        'model_type' => Dirigente::class,
        'model_id' => $dirigente->id,
        'old_values' => $dirigente->getOriginal(),
        'new_values' => $dirigente->getDirty(),
        'ip_address' => request()->ip(),
    ]);
}
```

---

## 4. Relatórios Avançados (1 semana)

### 4.1 Financeiro
- [ ] Fluxo de Caixa (gráfico de tendência)
- [ ] Análise por Categoria (pizza chart)
- [ ] Análise por Forma de Pagamento (bar chart)
- [ ] Comparativo de Períodos (período a período)
- [ ] Exportação em PDF/CSV/Excel

### 4.2 Eventos
- [ ] Taxa de presença por evento
- [ ] Dirigentes mais ativos
- [ ] Distribuição por tipo de evento
- [ ] Timeline de eventos (passado/futuro)

### 4.3 Dirigentes
- [ ] Distribuição por núcleo
- [ ] Por cargo/vínculo
- [ ] Dados demográficos (idade, gênero)
- [ ] Histório de vínculos

### 4.4 Tecnologia
- Chart.js ou ApexCharts para gráficos
- TCPDF ou Laravel DomPDF para PDF
- Maatwebsite/Excel para Excel
- Blade templates para impressão

---

## 5. Otimizações Finais (3-4 dias)

### 5.1 Performance
- [ ] Eager loading revisado em todos os controllers
- [ ] N+1 queries eliminadas
- [ ] Cache implementado para dados frequentes
- [ ] Database query optimization (índices)
- [ ] Asset minification (CSS/JS)

### 5.2 Segurança
- [ ] CSRF protection revisado
- [ ] SQL injection prevenido (use ORM)
- [ ] XSS protection habilitado
- [ ] Password requirements (min 8 chars, força)
- [ ] Rate limiting em endpoints críticos
- [ ] CORS restritivo

### 5.3 UX/UI
- [ ] Notificações flash consistentes
- [ ] Mensagens de erro amigáveis
- [ ] Loading states
- [ ] Confirmações de ações destrutivas
- [ ] Responsividade total (mobile)

### 5.4 Testes
- [ ] Coverage geral > 80%
- [ ] Testes de feature críticos
- [ ] Testes de API (50+)
- [ ] Testes de performance
- [ ] CI/CD pipeline

### 5.5 Documentação
- [ ] README atualizado
- [ ] Setup guide completo
- [ ] API documentation (OpenAPI)
- [ ] User guide (screenshots)
- [ ] Admin guide (configurações)

---

## 6. Cronograma Detalhado

| Semana | Atividade | Horas | Status |
|--------|-----------|-------|--------|
| 1 | Dashboard Executivo | 40h | ⏳ |
| 2 | API com Sanctum | 50h | ⏳ |
| 3 | Auditoria + Relatórios | 40h | ⏳ |
| 4 | Otimizações + Documentação | 70h | ⏳ |
| **TOTAL** | | **200h** | |

---

## 7. Critérios de Conclusão

### Dashboard
- [ ] Todos os 4 tipos de usuário têm dashboard
- [ ] KPIs atualizando em tempo real
- [ ] Gráficos carregando corretamente
- [ ] Responsividade comprovada

### API
- [ ] Todos os endpoints testados
- [ ] Autenticação funcionando
- [ ] Rate limiting ativo
- [ ] Documentação OpenAPI completa

### Auditoria
- [ ] Logs sendo registrados
- [ ] Interface de visualização funcionando
- [ ] Filtros aplicáveis
- [ ] Performance aceitável (< 1s para listar)

### Relatórios
- [ ] Gráficos renderizando
- [ ] Exportações funcionando (PDF, CSV, Excel)
- [ ] Filtros aplicáveis
- [ ] Performance aceitável

### Geral
- [ ] Todos os testes passando (100%)
- [ ] Coverage > 80%
- [ ] 0 warnings PHP/Laravel
- [ ] Documentação completa
- [ ] Pronto para staging

---

## 8. Priorização

**DEVE TER (Must):**
1. Dashboard com KPIs básicos
2. API com endpoints principais
3. Auditoria de ações críticas
4. Relatório financeiro básico

**DEVERÁ TER (Should):**
1. Relatórios avançados com gráficos
2. Exportações (PDF, CSV)
3. Documentação API completa

**PODERIA TER (Could):**
1. Gráficos interativos avançados
2. Machine learning de previsões
3. Alertas automáticos por email

---

## 9. Riscos e Mitigação

| Risco | Probabilidade | Impacto | Mitigação |
|-------|--------------|--------|-----------|
| API endpoint esquecido | Média | Alto | Documentação OpenAPI |
| Performance de relatórios | Média | Médio | Cache + indices |
| Cobertura de testes < 80% | Baixa | Alto | Testes contínuos |
| Segurança de API | Baixa | Alto | Code review + penetration testing |

---

## 10. Checklist Final Pré-Produção

- [ ] Testes passando (100%)
- [ ] Coverage > 80%
- [ ] Documentação completa
- [ ] API testada e documentada
- [ ] Dashboard funcionando
- [ ] Auditoria ativa
- [ ] Relatórios gerando corretamente
- [ ] Performance otimizada
- [ ] Segurança revisada
- [ ] README atualizado
- [ ] Migrations testadas
- [ ] Seeders funcionando
- [ ] .env.example atualizado
- [ ] Cache configurado
- [ ] Email configurado (opcional)
- [ ] Backup strategy definida
- [ ] Monitoring setup pronto

---

**Próximo Passo:** Iniciar Fase 5 com Dashboard Executivo  
**Data Estimada de Conclusão:** Julho de 2026  
**Deploy em Produção:** Segunda quinzena de julho

