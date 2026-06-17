<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\FinanceiroMovimento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatorioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function financial_report_page_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('relatorios.financeiro'));

        $response->assertStatus(200);
        $response->assertViewIs('relatorios.financeiro');
        $response->assertViewHas(['movimentos', 'resumo', 'porCategoria', 'porFormaPagamento']);
    }

    /** @test */
    public function event_report_page_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('relatorios.eventos'));

        $response->assertStatus(200);
        $response->assertViewIs('relatorios.eventos');
        $response->assertViewHas(['eventos', 'resumo', 'porTipo', 'taxaPresenca']);
    }

    /** @test */
    public function dirigentes_report_page_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('relatorios.dirigentes'));

        $response->assertStatus(200);
        $response->assertViewIs('relatorios.dirigentes');
        $response->assertViewHas(['dirigentes', 'porEntidade', 'porCargo', 'resumo']);
    }

    /** @test */
    public function financial_report_calculates_summary()
    {
        $user = User::factory()->create(['entidade_id' => 1]);

        FinanceiroMovimento::factory(3)->create([
            'entidade_id' => 1,
            'tipo' => 'entrada',
            'valor' => 100,
        ]);

        FinanceiroMovimento::factory(2)->create([
            'entidade_id' => 1,
            'tipo' => 'saida',
            'valor' => 50,
        ]);

        $response = $this->actingAs($user)->get(route('relatorios.financeiro'));

        $resumo = $response->viewData('resumo');
        $this->assertEqual(300, $resumo['entradas']);
        $this->assertEqual(100, $resumo['saidas']);
        $this->assertEqual(200, $resumo['saldo']);
        $this->assertEqual(5, $resumo['total_movimentos']);
    }

    /** @test */
    public function financial_report_filters_by_date()
    {
        $user = User::factory()->create(['entidade_id' => 1]);

        FinanceiroMovimento::factory()->create([
            'entidade_id' => 1,
            'tipo' => 'entrada',
            'valor' => 100,
            'data_movimento' => now()->subMonths(6),
        ]);

        FinanceiroMovimento::factory()->create([
            'entidade_id' => 1,
            'tipo' => 'entrada',
            'valor' => 200,
            'data_movimento' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('relatorios.financeiro', [
            'start_date' => now()->subDays(10),
            'end_date' => now(),
        ]));

        $response->assertStatus(200);
    }

    /** @test */
    public function can_export_financial_report_as_csv()
    {
        $user = User::factory()->create(['entidade_id' => 1]);

        FinanceiroMovimento::factory(2)->create([
            'entidade_id' => 1,
            'tipo' => 'entrada',
        ]);

        $response = $this->actingAs($user)->get(route('relatorios.export', [
            'tipo' => 'financeiro',
            'formato' => 'csv',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    }
}
