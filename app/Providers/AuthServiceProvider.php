<?php

namespace App\Providers;

use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\Evento;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use App\Models\User;
use App\Policies\EntidadePolicy;
use App\Policies\DirigentPolicy;
use App\Policies\EventoPolicy;
use App\Policies\FinanceiroCategoriaPolicy;
use App\Policies\FinanceiroMovimentoPolicy;
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
