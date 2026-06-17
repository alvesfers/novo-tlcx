<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Enums\TipoUsuario;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service
    ) {
    }

    public function index()
    {
        if (!auth()->check()) {
            return view('pages.dashboard.ecommerce');
        }

        $user = auth()->user();
        $resumo = $this->service->getResumo($user);

        // Retorna view específica por tipo de usuário
        return match($user->tipo_usuario) {
            TipoUsuario::Admin => view('dashboard.admin', compact('resumo')),
            TipoUsuario::Diocese => view('dashboard.diocese', compact('resumo')),
            TipoUsuario::Nucleo => view('dashboard.nucleo', compact('resumo')),
            TipoUsuario::Secretaria => view('dashboard.secretaria', compact('resumo')),
            default => view('dashboard.generico', compact('resumo')),
        };
    }
}
