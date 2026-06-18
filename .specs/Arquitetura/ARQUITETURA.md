# Arquitetura do Sistema TLC Admin

## Visão Geral

Sistema web multi-entidade de gestão para o TLC, desenvolvido com **Laravel 12**, **TailAdmin Laravel**, **Blade**, **Tailwind CSS** e **Alpine.js**, com previsão de integração com **Laravel Sanctum** para API e autenticação avançada.

## Filosofia de Design

- **Multi-tenancy por Entidade**: Cada entidade (Diocese, Núcleo, Secretaria) possui seus próprios dados e autonomia operacional
- **Hierarquia com Autonomia**: Mantém estrutura hierárquica mas permite autonomia local
- **Rastreabilidade**: Soft deletes e timestamps em tabelas críticas
- **Segurança Granular**: Policies do Laravel para autorização em nível de entidade
- **Escalabilidade**: Preparado para crescimento de dados e usuários

## Modelo de Entidades

O sistema opera com três tipos principais de entidades, cada uma com autonomia para:
- Financeiro próprio
- Eventos próprios
- Dirigentes vinculados
- Categorias de dados próprias
- Relatórios próprios

```
Diocese (raiz da hierarquia)
├── Núcleos (múltiplos)
│   ├── Dirigentes vinculados
│   ├── Financeiro local
│   └── Eventos
└── Secretarias (múltiplas)
    ├── Dirigentes vinculados
    ├── Financeiro local
    └── Eventos
```

## Stack Tecnológico

### Backend
- **Framework**: Laravel 12
- **Validação**: Form Requests
- **Autorização**: Policies
- **Autenticação**: Laravel Sanctum (futura API)
- **Database**: Migrations com versionamento

### Frontend
- **Template Engine**: Blade
- **Admin UI**: TailAdmin Laravel
- **Styling**: Tailwind CSS
- **Interatividade**: Alpine.js
- **Forms**: Validação client-side com Alpine

### Database
- **Soft Deletes**: Models principais
- **UUID**: Dirigentes com UUID público
- **Timestamps**: created_at, updated_at
- **Índices**: Otimizados para queries frequentes

## Estrutura de Diretórios Esperada

```
app/
├── Models/
│   ├── User.php
│   ├── Entidade.php
│   ├── Dirigente.php
│   ├── DirigenteFundador.php
│   ├── Evento.php
│   ├── EventoEntidade.php
│   ├── EventoParticipante.php
│   ├── ParticipanteExterno.php
│   ├── FinanceiroCategoria.php
│   ├── FinanceiroMovimento.php
│   └── TipoEvento.php
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── EntidadeController.php
│   │   ├── DirigentController.php
│   │   ├── EventoController.php
│   │   └── FinanceiroController.php
│   ├── Requests/
│   │   └── [Formulários validados por feature]
│   └── Middleware/
│       └── [Middleware customizado se necessário]
├── Policies/
│   ├── EntidadePolicy.php
│   ├── DirigentPolicy.php
│   ├── EventoPolicy.php
│   └── FinanceiroPolicy.php
├── Services/
│   ├── DirigentService.php
│   ├── EventoService.php
│   └── FinanceiroService.php
└── Enums/
    ├── TipoEntidade.php
    ├── TipoVinculo.php
    ├── CargoEnum.php
    └── [Outras enumerações]

resources/views/
├── layouts/
├── components/
├── entidades/
├── dirigentes/
├── eventos/
└── financeiro/

database/
├── migrations/
│   └── [Migrations em ordem cronológica]
└── seeders/
```

## Padrões de Código

### Models
- Relationships explícitos e bem nomeados
- Scopes para queries comuns
- Casts para tipos adequados
- Mutators onde necessário

### Controllers
- Actions simples e focadas
- Delegação de lógica complexa para Services
- Responses estruturadas
- Tratamento de exceções apropriado

### Services
- Lógica de negócio pesada
- Transações de dados complexas
- Reutilização entre múltiplos controllers
- Testes unitários isolados

### Policies
- Verificação de autorização granular
- Respeita hierarquia de entidades
- Permite visualização quando apropriado
- Nega modificação sem permissão

### Form Requests
- Validação centralizada
- Autorização no método `authorize()`
- Regras customizadas conforme necessário
- Mensagens de erro claras

## Segurança

### Autenticação
- Usuários (users) acessam o sistema
- Vinculo com entidades via user_id
- Futura: Laravel Sanctum para API
- Session-based para web

### Autorização
- Policies para todos os recursos
- Verificação de entidade pai
- Soft deletes protegem dados
- Audit trail via timestamps

### Dados Sensíveis
- Senhas com bcrypt
- URLs de comprovantes validadas
- Documenti de externos não expostos desnecessariamente

## Escalabilidade

### Considerações Futuras
- Cache de entidades e relacionamentos
- Fila para eventos assíncronos
- API com Sanctum
- Relatórios assíncronos
- Backup automático
- Auditoria detalhada de modificações

### Performance
- Eager loading onde apropriado
- Índices em tabelas grandes
- Paginação em listagens
- Lazy loading de relacionamentos pesados
