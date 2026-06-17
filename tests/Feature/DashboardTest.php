<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Entidade;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_acessa_dashboard()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'admin',
        ]);

        $response = $this->actingAs($adminUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('resumo');
    }

    public function test_diocese_acessa_dashboard_da_estrutura()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $dioceseUser = User::create([
            'name' => 'Diocese User',
            'email' => 'diocese@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'diocese',
            'entidade_id' => $diocese->id,
        ]);

        $response = $this->actingAs($dioceseUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('resumo');
    }

    public function test_nucleo_acessa_dashboard_proprio()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $nucleo = Entidade::create([
            'nome' => 'Núcleo Teste',
            'tipo_entidade' => 'nucleo',
            'entidade_pai_id' => $diocese->id,
            'ativo' => true,
        ]);

        $nucleoUser = User::create([
            'name' => 'Nucleo User',
            'email' => 'nucleo@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'nucleo',
            'entidade_id' => $nucleo->id,
        ]);

        $response = $this->actingAs($nucleoUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('resumo');
    }

    public function test_dashboard_retorna_indicadores_financeiros()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $dioceseUser = User::create([
            'name' => 'Diocese User',
            'email' => 'diocese@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'diocese',
            'entidade_id' => $diocese->id,
        ]);

        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $diocese->id,
            'nome' => 'Entrada Teste',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $diocese->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Entrada Teste',
            'valor' => 1000.00,
            'data_movimento' => now(),
            'forma_pagamento' => 'dinheiro',
        ]);

        $response = $this->actingAs($dioceseUser)->get(route('dashboard'));

        $resumo = $response->viewData('resumo');

        $this->assertArrayHasKey('financeiro', $resumo);
        $this->assertArrayHasKey('total_entradas', $resumo['financeiro']);
        $this->assertEquals(1000.00, $resumo['financeiro']['total_entradas']);
    }

    public function test_dashboard_retorna_proximos_eventos()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $dioceseUser = User::create([
            'name' => 'Diocese User',
            'email' => 'diocese@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'diocese',
            'entidade_id' => $diocese->id,
        ]);

        $response = $this->actingAs($dioceseUser)->get(route('dashboard'));

        $resumo = $response->viewData('resumo');

        $this->assertArrayHasKey('proximos_eventos', $resumo);
        $this->assertIsArray($resumo['proximos_eventos']);
    }

    public function test_dashboard_nao_quebra_sem_movimentacoes()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $dioceseUser = User::create([
            'name' => 'Diocese User',
            'email' => 'diocese@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'diocese',
            'entidade_id' => $diocese->id,
        ]);

        $response = $this->actingAs($dioceseUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $resumo = $response->viewData('resumo');
        $this->assertEquals(0, $resumo['financeiro']['total_entradas']);
    }

    public function test_dashboard_nao_quebra_sem_eventos()
    {
        $diocese = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true,
        ]);

        $dioceseUser = User::create([
            'name' => 'Diocese User',
            'email' => 'diocese@test.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'diocese',
            'entidade_id' => $diocese->id,
        ]);

        $response = $this->actingAs($dioceseUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $resumo = $response->viewData('resumo');
        $this->assertEquals(0, $resumo['indicadores']['total_eventos']);
    }
}
