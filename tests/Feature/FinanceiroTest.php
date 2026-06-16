<?php

namespace Tests\Feature;

use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use App\Models\Entidade;
use App\Models\User;
use App\Services\FinanceiroService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceiroTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Entidade $entidade;
    private FinanceiroService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entidade = Entidade::create([
            'nome' => 'Núcleo Teste',
            'tipo_entidade' => 'nucleo',
            'ativo' => true,
        ]);

        $this->user = User::create([
            'name' => 'Teste Usuario',
            'email' => 'teste@example.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => 'nucleo',
            'entidade_id' => $this->entidade->id,
        ]);

        $this->service = new FinanceiroService();
    }

    public function test_criar_categoria_entrada_direto()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Dízimos',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        $this->assertDatabaseHas('financeiro_categorias', [
            'entidade_id' => $this->entidade->id,
            'nome' => 'Dízimos',
            'tipo' => 'entrada',
        ]);
        $this->assertTrue($categoria->ativo);
    }

    public function test_criar_categoria_saida_direto()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Transporte',
            'tipo' => 'saida',
            'ativo' => true,
        ]);

        $this->assertDatabaseHas('financeiro_categorias', [
            'entidade_id' => $this->entidade->id,
            'nome' => 'Transporte',
            'tipo' => 'saida',
        ]);
        $this->assertTrue($categoria->ativo);
    }

    public function test_criar_movimento_entrada_direto()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Entrada',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        $movimento = FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Coleta especial',
            'valor' => 500.00,
            'data_movimento' => today()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        $this->assertDatabaseHas('financeiro_movimentos', [
            'entidade_id' => $this->entidade->id,
            'descricao' => 'Coleta especial',
            'valor' => 500.00,
        ]);
        $this->assertEquals('entrada', $movimento->tipo->value);
    }

    public function test_criar_movimento_saida_direto()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Saida',
            'tipo' => 'saida',
            'ativo' => true,
        ]);

        $movimento = FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'saida',
            'descricao' => 'Frete para evento',
            'valor' => 150.00,
            'data_movimento' => today()->toDateString(),
            'forma_pagamento' => 'transferencia',
        ]);

        $this->assertDatabaseHas('financeiro_movimentos', [
            'entidade_id' => $this->entidade->id,
            'descricao' => 'Frete para evento',
            'valor' => 150.00,
        ]);
        $this->assertEquals('saida', $movimento->tipo->value);
    }

    public function test_calcular_saldo_entidade()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Entrada',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Teste',
            'valor' => 1000.00,
            'data_movimento' => today()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        $categoriaSaida = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Saida',
            'tipo' => 'saida',
            'ativo' => true,
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoriaSaida->id,
            'tipo' => 'saida',
            'descricao' => 'Teste',
            'valor' => 300.00,
            'data_movimento' => today()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        $saldo = $this->service->calcularSaldo($this->entidade->id);

        $this->assertEquals(700.00, $saldo);
    }

    public function test_calcular_saldo_periodo()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Entrada',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Teste',
            'valor' => 500.00,
            'data_movimento' => today(),
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Teste 2',
            'valor' => 300.00,
            'data_movimento' => today()->subDays(10),
            'forma_pagamento' => 'dinheiro',
        ]);

        $resultado = $this->service->calcularSaldoPeriodo(
            $this->entidade->id,
            today()->startOfMonth(),
            today()->endOfMonth()
        );

        $this->assertEquals(800.00, $resultado['entradas']);
        $this->assertEquals(0, $resultado['saidas']);
        $this->assertEquals(800.00, $resultado['saldo']);
    }

    public function test_listar_movimentos()
    {
        $categoria = FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Teste Entrada',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $this->entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'tipo' => 'entrada',
            'descricao' => 'Teste',
            'valor' => 100.00,
            'data_movimento' => today(),
            'forma_pagamento' => 'dinheiro',
        ]);

        $this->actingAs($this->user);

        $response = $this->get(route('financeiro-movimentos.index'));
        $response->assertStatus(200);
        $response->assertSee('Movimentações');
    }

    public function test_listar_categorias()
    {
        FinanceiroCategoria::create([
            'entidade_id' => $this->entidade->id,
            'nome' => 'Dízimos',
            'tipo' => 'entrada',
            'ativo' => true,
        ]);

        $this->actingAs($this->user);

        $response = $this->get(route('financeiro-categorias.index'));
        $response->assertStatus(200);
        $response->assertSee('Categorias Financeiras');
    }
}
