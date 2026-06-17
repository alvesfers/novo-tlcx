# Plano Detalhado Fase 5: Dashboard, API e Finalizações

**Versão:** 1.1  
**Data:** 2026-06-17  
**Status:** 🟡 MVP Implementada (~65% Completa)  
**Duração Realizada:** ~2 semanas (Primeiras 6 subfases)
**Duração Estimada para Conclusão:** ~2-3 semanas (Últimas pendências)

---

## 📋 Status Atual da Fase 5

### O Que Já Foi Implementado ✅
1. ✅ **Autenticação Web Real** - Login/logout funcionando
2. ✅ **API com Sanctum** - 15+ endpoints implementados
3. ✅ **Auditoria e Logs** - Sistema completo de logging
4. ✅ **Relatórios Avançados** - Financeiro, Eventos, Dirigentes (CSV)
5. ✅ **QR Code e Check-in** - Geração e leitura de QR funcionando
6. ✅ **Testes** - 37/37 testes passando

### O Que Falta Implementar ⏳
1. ⏳ **Dashboard Executivo** - Widgets e KPIs por tipo de usuário
2. ⏳ **Gráficos Interativos** - Chart.js/ApexCharts
3. ⏳ **Exportação PDF/Excel** - Formatação avançada
4. ⏳ **Documentação OpenAPI** - Swagger/OpenAPI

---

## 📋 Visão Geral da Fase 5

A Fase 5 é a última fase antes de produção. Foca em:
1. **Dashboard Executivo** - Visão consolidada por tipo de usuário (⏳ Próximo)
2. **API com Sanctum** - Endpoints para mobile/terceiros (✅ Concluído)
3. **Auditoria e Logs** - Rastreamento de ações (✅ Concluído)
4. **Relatórios Avançados** - Gráficos e exportações (⏳ Gráficos pendentes)
5. **Otimizações Finais** - Performance e segurança (⏳ Próximo)

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
- [ ] DashboardController com lógica por tipo_usuario (⏳ Estrutura base criada)
- [ ] Views: dashboard.blade.php (index), admin, diocese, nucleo, secretaria (⏳ Pendente)
- [ ] Componentes reutilizáveis (cards, gráficos) (⏳ Pendente)
- [ ] Rotas autenticadas (✅ Protegidas com auth middleware)
- [ ] Testes de acesso por tipo de usuário (⏳ Pendente)
- [ ] Documentação no README (⏳ Pendente)

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
- [x] Configurar Sanctum (sanctum:install) ✅
- [x] Criar AuthController com login/logout ✅
- [x] Criar ApiResources (DirigenteResource, EventoResource, etc) ✅
- [x] Implementar Controllers API ✅
- [x] Validações de Form Requests ✅
- [ ] Rate limiting por endpoint (⏳ Pendente)
- [ ] CORS configurado (⏳ Pendente)
- [ ] Documentação OpenAPI/Swagger (⏳ Pendente)
- [x] Testes de API (37/37 passando) ✅

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
- [x] Criar migration audit_logs ✅
- [x] Criar Observer para auto-logging ✅
- [x] Implementar em Dirigente, Evento, FinanceiroMovimento ✅
- [x] AuditLogController para visualização ✅
- [x] View: auditoria/index.blade.php com filtros ✅
- [x] Relatório: Ações por usuário, por período ✅
- [x] Testes ✅

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

## 4. Relatórios Avançados (1 semana - MVP concluída, gráficos pendentes)

### 4.1 Financeiro
- [x] Extrato e resumo (CSV) ✅
- [ ] Fluxo de Caixa (gráfico de tendência) (⏳ Gráfico pendente)
- [ ] Análise por Categoria (pizza chart) (⏳ Gráfico pendente)
- [ ] Análise por Forma de Pagamento (bar chart) (⏳ Gráfico pendente)
- [ ] Comparativo de Períodos (período a período) (⏳ Gráfico pendente)
- [x] Exportação em CSV ✅
- [ ] Exportação em PDF/Excel (⏳ Pendente)

### 4.2 Eventos
- [x] Taxa de presença por evento ✅
- [ ] Dirigentes mais ativos (⏳ Gráfico pendente)
- [ ] Distribuição por tipo de evento (⏳ Gráfico pendente)
- [ ] Timeline de eventos (passado/futuro) (⏳ Gráfico pendente)

### 4.3 Dirigentes
- [x] Distribuição por núcleo (⏳ Gráfico pendente)
- [ ] Por cargo/vínculo (⏳ Gráfico pendente)
- [ ] Dados demográficos (idade, gênero) (⏳ Gráfico pendente)
- [ ] Histório de vínculos (⏳ Implementado em tabela)

### 4.4 Tecnologia
- Chart.js ou ApexCharts para gráficos (⏳ Pendente)
- TCPDF ou Laravel DomPDF para PDF (⏳ Pendente)
- Maatwebsite/Excel para Excel (⏳ Pendente)
- Blade templates para impressão (✅ Base criada)

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

## 6. Cronograma Detalhado (Realizado + Pendente)

| Semana | Atividade | Horas | Status |
|--------|-----------|-------|--------|
| 1-2 | Autenticação, API, Auditoria, Relatórios, QR, Testes | 100h | ✅ CONCLUÍDO |
| 3 | Dashboard Executivo | 40h | ⏳ Próximo |
| 3 | Gráficos Interativos | 30h | ⏳ Próximo |
| 4 | Exportação PDF/Excel + Documentação + Otimizações | 50h | ⏳ Próximo |
| **TOTAL REALIZADO** | | **100h** | ✅ |
| **TOTAL PENDENTE** | | **120h** | ⏳ |
| **TOTAL PROJETO** | | **220h** | 🟡 ~55% |

---

## 7. Critérios de Conclusão

### Dashboard (⏳ Pendente)
- [ ] Todos os 4 tipos de usuário têm dashboard
- [ ] KPIs atualizando em tempo real
- [ ] Gráficos carregando corretamente
- [ ] Responsividade comprovada

### API (✅ Concluído)
- [x] Todos os endpoints testados ✅
- [x] Autenticação funcionando ✅
- [ ] Rate limiting ativo (⏳ Pendente)
- [ ] Documentação OpenAPI completa (⏳ Pendente)

### Auditoria (✅ Concluído)
- [x] Logs sendo registrados ✅
- [x] Interface de visualização funcionando ✅
- [x] Filtros aplicáveis ✅
- [x] Performance aceitável (< 1s para listar) ✅

### Relatórios (⏳ Parcialmente Concluído)
- [ ] Gráficos renderizando (⏳ Pendente)
- [x] Exportações funcionando (CSV) ✅
- [x] Filtros aplicáveis ✅
- [x] Performance aceitável ✅
- [ ] Exportação PDF/Excel (⏳ Pendente)

### Geral (✅ Parcialmente Concluído)
- [x] Todos os testes passando (37/37 = 100%) ✅
- [x] Coverage adequada ✅
- [x] 0 warnings PHP/Laravel ✅
- [ ] Documentação completa (⏳ API Swagger pendente)
- [ ] Pronto para staging (⏳ Faltam Dashboard e Gráficos)

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

**Concluído ✅:**
- [x] Testes passando (37/37 = 100%)
- [x] Coverage adequada
- [x] API testada e funcional
- [x] Auditoria ativa
- [x] Relatórios básicos gerando
- [x] Migrations testadas
- [x] Seeders funcionando
- [x] .env.example atualizado
- [x] Segurança base implementada

**Pendente ⏳:**
- [ ] Documentação API (Swagger/OpenAPI)
- [ ] Dashboard funcionando
- [ ] Gráficos interativos
- [ ] Exportação PDF/Excel
- [ ] README atualizado (completo)
- [ ] Performance otimizada
- [ ] Segurança avançada (Rate limiting, CORS)
- [ ] Cache configurado (Redis)
- [ ] Email configurado (opcional)
- [ ] Backup strategy definida
- [ ] Monitoring setup pronto

---

## Resumo do Progresso

**MVP Concluída (65% - 6 de 8 subfases):**
1. ✅ Autenticação Web Real
2. ✅ API com Sanctum (15+ endpoints)
3. ✅ Auditoria e Logs
4. ✅ Relatórios Avançados Básicos
5. ✅ QR Code e Check-in
6. ✅ Testes (37/37 passando)

**Pendências Críticas (35% - 2 de 8 subfases):**
1. ⏳ Dashboard Executivo (CRÍTICO para produção)
2. ⏳ Gráficos Interativos (CRÍTICO para relatórios)
3. ⏳ Exportação PDF/Excel (IMPORTANTE)
4. ⏳ Documentação OpenAPI (IMPORTANTE)

---

**Próximo Passo:** Implementar **Dashboard Executivo com Widgets e KPIs**  
**Data Estimada de Conclusão:** Junho/Julho de 2026  
**Deploy em Produção:** Segunda quinzena de julho de 2026

