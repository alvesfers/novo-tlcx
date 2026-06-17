<?php

namespace App\Providers;

use App\Models\AlmoxarifadoCategoria;
use App\Models\AlmoxarifadoItem;
use App\Models\AlmoxarifadoMovimento;
use App\Models\Documento;
use App\Models\DocumentoCategoria;
use App\Models\Dirigente;
use App\Models\Entidade;
use App\Models\Evento;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use App\Models\Tarefa;
use App\Models\TarefaCategoria;
use App\Models\User;
use App\Policies\AlmoxarifadoCategoriaPolicy;
use App\Policies\AlmoxarifadoItemPolicy;
use App\Policies\AlmoxarifadoMovimentoPolicy;
use App\Policies\DirigentPolicy;
use App\Policies\DocumentoCategoriaPolicy;
use App\Policies\DocumentoPolicy;
use App\Policies\EntidadePolicy;
use App\Policies\EventoPolicy;
use App\Policies\FinanceiroCategoriaPolicy;
use App\Policies\FinanceiroMovimentoPolicy;
use App\Policies\TarefaCategoriaPolicy;
use App\Policies\TarefaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Entidade::class => EntidadePolicy::class,
        Dirigente::class => DirigentPolicy::class,
        Evento::class => EventoPolicy::class,
        FinanceiroCategoria::class => FinanceiroCategoriaPolicy::class,
        FinanceiroMovimento::class => FinanceiroMovimentoPolicy::class,
        AlmoxarifadoCategoria::class => AlmoxarifadoCategoriaPolicy::class,
        AlmoxarifadoItem::class => AlmoxarifadoItemPolicy::class,
        AlmoxarifadoMovimento::class => AlmoxarifadoMovimentoPolicy::class,
        Tarefa::class => TarefaPolicy::class,
        TarefaCategoria::class => TarefaCategoriaPolicy::class,
        Documento::class => DocumentoPolicy::class,
        DocumentoCategoria::class => DocumentoCategoriaPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
