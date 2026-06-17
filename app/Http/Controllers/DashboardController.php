<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

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

        return view('dashboard', compact('resumo'));
    }
}
